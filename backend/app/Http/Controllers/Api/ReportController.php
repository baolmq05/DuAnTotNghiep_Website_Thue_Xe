<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\Trip;
use App\Enum\ReportType;
use App\Enum\ReportStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Http\Requests\Report\StoreReportRequest;

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
            if ($trip->user_id !== $user->id && $trip->car->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền báo cáo/khiếu nại chuyến đi này.'
                ], 403);
            }

            // Generate report title
            $reportTypeEnum = ReportType::tryFrom((int) $request->report_type);
            $reportTypeLabel = $reportTypeEnum ? $reportTypeEnum->getLabel() : 'Khiếu nại';
            $title = "Khiếu nại chuyến đi #" . $trip->id . " - " . $reportTypeLabel;

            // Create Report
            $report = Report::create([
                'trip_id' => $trip->id,
                'reporter_id' => $user->id,
                'report_type' => $reportTypeEnum,
                'title' => $title,
                'description' => $request->description,
                'status' => ReportStatus::Pending,
            ]);

            // Save report images if present
            if ($request->has('images') && is_array($request->images)) {
                foreach ($request->images as $imageUrl) {
                    $report->images()->create([
                        'image_url' => $imageUrl
                    ]);
                }
            }

            // Load relations to return
            $report->load('images');

            return response()->json([
                'success' => true,
                'message' => 'Gửi khiếu nại thành công! Chúng tôi sẽ xem xét và phản hồi sớm nhất.',
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
}
