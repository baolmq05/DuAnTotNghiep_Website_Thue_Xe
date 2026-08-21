<?php

namespace App\Services;

use App\Enum\PenaltyType;
use App\Enum\ReportStatus;
use App\Mail\OwnerPenaltyMail;
use App\Mail\ReportRejectedMail;
use App\Mail\ReportResolvedMail;
use App\Models\OwnerPenalty;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReportService
{
    /**
     * Tự động xác định loại hình phạt dựa trên số lần vi phạm (strike) trong 90 ngày gần nhất.
     */
    public static function getPenaltyTypeForOwner(?int $ownerId): PenaltyType
    {
        if (!$ownerId) {
            return PenaltyType::Warning1;
        }

        $strikeCount = OwnerPenalty::where('user_id', $ownerId)
            ->where('created_at', '>=', now()->subDays(90))
            ->count();

        if ($strikeCount === 0) {
            return PenaltyType::Warning1; // Lần 1: Cảnh cáo lần 1
        } elseif ($strikeCount === 1) {
            return PenaltyType::Warning2; // Lần 2: Cảnh cáo lần 2
        } else {
            return PenaltyType::AccountSuspension; // Lần 3+: Khóa tài khoản
        }
    }

    /**
     * Xử lý Duyệt/Chấp nhận báo cáo (Approve/Resolve).
     */
    public static function resolveReport(Report $report, string $adminNote, string $penaltyReason): void
    {
        $ownerId = $report->trip?->car?->user_id;

        // 1. Cập nhật trạng thái báo cáo
        $report->update([
            'status' => ReportStatus::Resolved,
            'admin_note' => $adminNote,
            'resolved_at' => now(),
            'resolved_by' => Auth::id(),
        ]);

        if ($ownerId) {
            // 2. Tự động xác định loại hình phạt dựa trên 90 ngày gần nhất
            $penaltyType = self::getPenaltyTypeForOwner($ownerId);

            // 3. Tạo bản ghi xử phạt
            $penalty = OwnerPenalty::create([
                'user_id' => $ownerId,
                'trip_id' => $report->trip_id,
                'report_id' => $report->id,
                'penalty_type' => $penaltyType,
                'start_at' => now(),
                'reason' => $penaltyReason,
                'resolved_by' => Auth::id(),
            ]);

            // 4. Đếm tổng số strike trong 90 ngày gần nhất
            $totalStrikes = OwnerPenalty::where('user_id', $ownerId)
                ->where('created_at', '>=', now()->subDays(90))
                ->count();

            // Áp dụng khóa xe nếu hình phạt là Cảnh cáo lần 2
            if ($penaltyType === PenaltyType::Warning2 && $report->trip?->car) {
                try {
                    $report->trip->car->update(['status' => 0]);
                } catch (\Exception $e) {
                    Log::warning("Không thể cập nhật trạng thái xe ID {$report->trip->car->id}: " . $e->getMessage());
                }
            }

            // Áp dụng khóa tài khoản nếu đủ 3 strike trong 90 ngày hoặc penalty_type là AccountSuspension
            $owner = User::find($ownerId);
            if ($totalStrikes >= 3 || $penaltyType === PenaltyType::AccountSuspension) {
                if ($owner) {
                    $owner->update(['status' => 0]); // 0: Bị khóa
                }
            }

            // 5. Gửi email thông báo hình phạt vi phạm trực tiếp cho chủ xe
            if ($owner && $owner->email) {
                try {
                    Mail::to($owner->email)->send(new OwnerPenaltyMail($penalty, $report));
                } catch (\Exception $e) {
                    Log::error("Gửi mail thông báo vi phạm tới chủ xe {$owner->email} thất bại: " . $e->getMessage());
                }
            }
        }

        // 5. Gửi email thông báo cho người báo cáo biết báo cáo đã được duyệt
        $reporter = $report->reporter;
        if ($reporter && $reporter->email) {
            try {
                Mail::to($reporter->email)->send(new ReportResolvedMail($report));
            } catch (\Exception $e) {
                Log::error("Gửi mail duyệt báo cáo vi phạm thất bại tới {$reporter->email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Xử lý Từ chối báo cáo (Reject) và gửi email thông báo.
     */
    public static function rejectReport(Report $report, string $adminNote): void
    {
        // 1. Cập nhật trạng thái báo cáo
        $report->update([
            'status' => ReportStatus::Rejected,
            'admin_note' => $adminNote,
            'resolved_at' => now(),
            'resolved_by' => Auth::id(),
        ]);

        // 2. Gửi email thông báo về cho người báo cáo
        $reporter = $report->reporter;
        if ($reporter && $reporter->email) {
            try {
                Mail::to($reporter->email)->send(new ReportRejectedMail($report));
            } catch (\Exception $e) {
                Log::error("Gửi mail từ chối báo cáo vi phạm thất bại tới {$reporter->email}: " . $e->getMessage());
            }
        }
    }
}
