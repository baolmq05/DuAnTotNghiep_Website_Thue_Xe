<?php

namespace App\Console\Commands;

use App\Enum\ReportStatus;
use App\Enum\TripStatus;
use App\Models\Notification;
use App\Models\Report;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoTimeoutReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:auto-timeout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động mở khóa các chuyến đi và đánh dấu hết hạn đối với các khiếu nại quá hạn xử lý (SLA)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = now();

        $expiredReports = Report::with('trip.car')
            ->where('status', ReportStatus::Pending)
            ->whereNotNull('deadline_at')
            ->where('deadline_at', '<=', $now)
            ->get();

        if ($expiredReports->isEmpty()) {
            $this->info('Không có khiếu nại nào bị quá hạn xử lý.');
            return Command::SUCCESS;
        }

        $count = 0;

        foreach ($expiredReports as $report) {
            $report->update([
                'status' => ReportStatus::Expired,
                'admin_note' => 'Hệ thống tự động đóng khiếu nại do quá hạn xử lý (SLA 72 giờ). Chuyến đi được mở khóa về trạng thái trước đó.',
                'resolved_at' => $now,
            ]);

            $trip = $report->trip;
            $tripStatusVal = $trip?->status instanceof TripStatus ? $trip->status->value : (int) ($trip?->status ?? 0);
            if ($trip && $tripStatusVal === TripStatus::Disputed->value) {
                $restoredStatus = $report->previous_trip_status ?? TripStatus::Confirmed->value;
                $trip->update(['status' => $restoredStatus]);

                $tripCode = $trip->trip_code ?? ('#' . $trip->id);

                // Notify reporter
                Notification::create([
                    'user_id' => $report->reporter_id,
                    'message' => "Khiếu nại cho chuyến đi {$tripCode} đã hết hạn xử lý (Timeout). Chuyến đi được khôi phục trạng thái để tiếp tục.",
                    'is_read' => '0',
                ]);

                // Notify target user
                $targetUserId = ($report->reporter_id == $trip->user_id) ? $trip->car?->user_id : $trip->user_id;
                if ($targetUserId) {
                    Notification::create([
                        'user_id' => $targetUserId,
                        'message' => "Khiếu nại chuyến đi {$tripCode} đã tự động hết hạn xử lý. Chuyến đi đã được mở khóa và tiếp tục bình thường.",
                        'is_read' => '0',
                    ]);
                }
            }

            $count++;
        }

        $this->info("Đã xử lý timeout thành công cho {$count} khiếu nại.");
        Log::info("AutoTimeoutReportsCommand: Đã tự động hết hạn và khôi phục cho {$count} khiếu nại.");

        return Command::SUCCESS;
    }
}
