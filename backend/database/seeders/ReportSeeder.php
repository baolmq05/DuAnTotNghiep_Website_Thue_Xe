<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\OwnerPenalty;
use App\Enum\ReportStatus;
use App\Enum\ReportType;
use App\Enum\PenaltyType;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a trip to attach the report
        $trip = Trip::with('car')->first();

        if (!$trip) {
            return;
        }

        // 1. Create a resolved report
        $report1 = Report::create([
            'trip_id' => $trip->id,
            'reporter_id' => $trip->user_id, // Renter reports owner
            'report_type' => ReportType::WrongCar,
            'title' => 'Giao sai xe so với đăng ký',
            'description' => 'Chủ xe giao xe không đúng biển số đã đăng ký trên hệ thống. Xe thực tế giao là dòng xe cũ hơn và biển số khác.',
            'status' => ReportStatus::Resolved,
            'admin_note' => 'Đã xác minh hình ảnh đối chiếu thực tế. Tiến hành phạt cảnh cáo chủ xe vì vi phạm quy định bàn giao.',
            'resolved_at' => now(),
            'resolved_by' => 1, // Admin user id
        ]);

        // Add evidence image
        ReportImage::create([
            'report_id' => $report1->id,
            'image_url' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=600',
        ]);
        ReportImage::create([
            'report_id' => $report1->id,
            'image_url' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=600',
        ]);

        // Create penalty for owner
        OwnerPenalty::create([
            'user_id' => $trip->car->user_id, // Owner's user_id
            'trip_id' => $trip->id,
            'report_id' => $report1->id,
            'penalty_type' => PenaltyType::Warning1,
            'start_at' => now(),
            'end_at' => now()->addDays(30),
            'reason' => 'Cảnh cáo chủ xe vì hành vi bàn giao sai phương tiện đăng ký.',
            'resolved_by' => 1,
        ]);

        // 2. Create a pending report for simulation/testing of pending state
        // Let's attach to the same trip or another trip if available
        $secondTrip = Trip::with('car')->skip(1)->first() ?: $trip;
        
        $report2 = Report::create([
            'trip_id' => $secondTrip->id,
            'reporter_id' => $secondTrip->user_id,
            'report_type' => ReportType::Fraud,
            'title' => 'Nghi ngờ gian lận tiền cọc',
            'description' => 'Chủ xe yêu cầu chuyển thêm 1,000,000đ tiền cọc ngoài ứng dụng bằng tiền mặt nhưng không ghi vào biên nhận.',
            'status' => ReportStatus::Pending,
            'admin_note' => null,
            'resolved_at' => null,
            'resolved_by' => null,
        ]);

        ReportImage::create([
            'report_id' => $report2->id,
            'image_url' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&q=80&w=600',
        ]);
    }
}
