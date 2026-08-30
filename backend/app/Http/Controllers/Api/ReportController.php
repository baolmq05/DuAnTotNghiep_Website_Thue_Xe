<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\Trip;
use App\Enum\ReportType;
use App\Enum\ReportStatus;
use App\Enum\TripStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Http\Requests\Report\StoreReportRequest;
use App\Models\Notification;

class ReportController extends Controller
{
    /**
     * Store a newly created report in storage.
     * POST /api/reports
     */
    public function store(StoreReportRequest $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện hành động này.'
            ], 401);
        }

        try {
            $trip = Trip::with('car')->find($request->trip_id);

            // User must be renter (user_id) or owner of the car (car->user_id)
            if ($trip->user_id != $user->id && $trip->car->user_id != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền báo cáo/khiếu nại chuyến đi này.'
                ], 403);
            }

            // Only allow reports on confirmed, ongoing, waiting return, or completed trips
            $allowedStatuses = [
                TripStatus::Confirmed->value,
                TripStatus::Ongoing->value,
                TripStatus::WaitingReturn->value,
                TripStatus::WaitingExtension->value,
                TripStatus::Complete->value,
            ];
            $tripStatusVal = $trip->status instanceof TripStatus ? $trip->status->value : (int) $trip->status;
            if (!in_array($tripStatusVal, $allowedStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ có thể khiếu nại đối với các chuyến đi đã thanh toán, đang diễn ra hoặc đã hoàn thành.'
                ], 400);
            }

            // Generate report title
            $reportTypeEnum = ReportType::tryFrom((int) $request->report_type);
            $reportTypeLabel = $reportTypeEnum ? $reportTypeEnum->getLabel() : 'Khiếu nại';
            $tripCode = $trip->trip_code ?? ('#' . $trip->id);
            $title = "Khiếu nại chuyến đi " . $tripCode . " - " . $reportTypeLabel;

            $previousStatus = $tripStatusVal;

            // Create Report
            $report = Report::create([
                'trip_id' => $trip->id,
                'reporter_id' => $user->id,
                'report_type' => $reportTypeEnum,
                'title' => $title,
                'description' => $request->description,
                'status' => ReportStatus::Pending,
                'previous_trip_status' => $previousStatus,
                'deadline_at' => now()->addHours(72),
            ]);

            // Save report images if present
            if ($request->has('images') && is_array($request->images)) {
                foreach ($request->images as $imageUrl) {
                    $report->images()->create([
                        'image_url' => $imageUrl
                    ]);
                }
            }

            // Lock trip status to Disputed (if not already completed)
            if ($previousStatus != TripStatus::Complete->value) {
                $trip->update(['status' => TripStatus::Disputed->value]);
            }

            // Send notification to Reporter
            Notification::create([
                'user_id' => $user->id,
                'message' => "Khiếu nại cho chuyến đi {$tripCode} đã được ghi nhận. Chuyến đi tạm thời ở trạng thái tranh chấp trong khi CSKH xác minh.",
                'is_read' => '0',
            ]);

            // Send notification to the other party
            $targetUserId = ($user->id == $trip->user_id) ? $trip->car?->user_id : $trip->user_id;
            if ($targetUserId) {
                Notification::create([
                    'user_id' => $targetUserId,
                    'message' => "Chuyến đi {$tripCode} có khiếu nại mới từ đối phương. Hệ thống tạm thời khóa các thao tác chuyến đi để CSKH xử lý.",
                    'is_read' => '0',
                ]);
            }

            // Load relations to return
            $report->load('images');

            return response()->json([
                'success' => true,
                'message' => 'Gửi khiếu nại thành công! Chuyến đi đã được đưa vào trạng thái tranh chấp để CSKH xác minh và xử lý.',
                'data' => $report
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi khiếu nại.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revoke an existing report.
     * POST /api/reports/{id}/revoke
     */
    public function revoke($id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện hành động này.'
            ], 401);
        }

        try {
            $report = Report::with('trip.car')->find($id);

            if (!$report) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy khiếu nại này.'
                ], 404);
            }

            // Only the reporter can revoke the report
            if ($report->reporter_id != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền thu hồi khiếu nại này.'
                ], 403);
            }

            // Check if report status is Pending
            if ($report->status != ReportStatus::Pending) {
                return response()->json([
                    'success' => false,
                    'message' => 'Khiếu nại này không ở trạng thái chờ xử lý nên không thể thu hồi.'
                ], 400);
            }

            // Update status to Cancelled
            $report->update([
                'status' => ReportStatus::Cancelled,
            ]);

            // Restore trip status from previous_trip_status if trip is currently Disputed
            $trip = $report->trip;
            $tripCode = $trip->trip_code ?? ('#' . $trip->id);

            $tripStatusVal = $trip?->status instanceof TripStatus ? $trip->status->value : (int) ($trip?->status ?? 0);
            if ($trip && $tripStatusVal === TripStatus::Disputed->value && $report->previous_trip_status !== null) {
                $trip->update([
                    'status' => $report->previous_trip_status
                ]);
            }

            // Notify opposite party
            $targetUserId = ($user->id == $trip->user_id) ? $trip->car?->user_id : $trip->user_id;
            if ($targetUserId) {
                Notification::create([
                    'user_id' => $targetUserId,
                    'message' => "Đối phương đã thu hồi khiếu nại đối với chuyến đi {$tripCode}. Chuyến đi tiếp tục bình thường.",
                    'is_read' => '0',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Thu hồi khiếu nại thành công. Chuyến đi đã được khôi phục trạng thái.',
                'data' => $report->fresh('images')
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thu hồi khiếu nại.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
