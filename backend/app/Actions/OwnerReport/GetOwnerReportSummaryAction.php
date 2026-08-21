<?php

namespace App\Actions\OwnerReport;

use App\Enum\PenaltyType;
use App\Enum\ReportStatus;
use App\Models\OwnerPenalty;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;

class GetOwnerReportSummaryAction
{
    /**
     * Compute summary statistics of reports and strikes for a car owner.
     */
    public function execute(User $user): array
    {
        $now = Carbon::now();

        // 1. Thống kê Báo cáo (Reports)
        $reportsQuery = Report::whereHas('trip.car', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        $totalReports = (clone $reportsQuery)->count();
        $pendingReports = (clone $reportsQuery)->where('status', ReportStatus::Pending->value)->count();
        $resolvedReports = (clone $reportsQuery)->where('status', ReportStatus::Resolved->value)->count();
        $rejectedReports = (clone $reportsQuery)->where('status', ReportStatus::Rejected->value)->count();

        // 2. Thống kê Án phạt / Gậy (Strikes / Penalties)
        $penaltiesQuery = OwnerPenalty::where('user_id', $user->id);

        $totalStrikes = (clone $penaltiesQuery)->count();

        // Gậy còn hiệu lực: Chưa hết hạn (end_at IS NULL hoặc end_at > now) và đã bắt đầu (start_at IS NULL hoặc start_at <= now)
        $activePenaltiesQuery = (clone $penaltiesQuery)->where(function ($q) use ($now) {
            $q->whereNull('end_at')->orWhere('end_at', '>', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
        });

        $activeStrikes = (clone $activePenaltiesQuery)->count();

        // Phân loại số lượng theo từng loại hình phạt
        $warningsCount = (clone $penaltiesQuery)->where('penalty_type', PenaltyType::Warning->value)->count();
        $carSuspensionsCount = (clone $penaltiesQuery)->where('penalty_type', PenaltyType::CarSuspension->value)->count();
        $accountSuspensionsCount = (clone $penaltiesQuery)->where('penalty_type', PenaltyType::AccountSuspension->value)->count();

        // Danh sách các án phạt đang có hiệu lực
        $activePenalties = (clone $activePenaltiesQuery)
            ->with(['trip', 'report'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($penalty) => OwnerReportFormatter::formatPenalty($penalty));

        // 3. Trạng thái tài khoản (ACTIVE / SUSPENDED)
        $hasActiveAccountSuspension = (clone $activePenaltiesQuery)
            ->where('penalty_type', PenaltyType::AccountSuspension->value)
            ->exists();

        $isSuspended = ((int) $user->status === 0) || $hasActiveAccountSuspension;
        $accountStatus = $isSuspended ? 'SUSPENDED' : 'ACTIVE';

        // 4. 5 Báo cáo gần nhất
        $recentReports = (clone $reportsQuery)
            ->with(['trip.car.images', 'reporter', 'images', 'penalty'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn ($report) => OwnerReportFormatter::formatReportListItem($report));

        return [
            'account_status' => $accountStatus,
            'is_account_suspended' => $isSuspended,
            'active_strikes' => $activeStrikes,
            'total_strikes' => $totalStrikes,
            'reports' => [
                'total' => $totalReports,
                'pending' => $pendingReports,
                'resolved' => $resolvedReports,
                'rejected' => $rejectedReports,
            ],
            'penalties_breakdown' => [
                'warnings' => $warningsCount,
                'car_suspensions' => $carSuspensionsCount,
                'account_suspensions' => $accountSuspensionsCount,
            ],
            'active_penalties' => $activePenalties,
            'recent_reports' => $recentReports,
        ];
    }
}
