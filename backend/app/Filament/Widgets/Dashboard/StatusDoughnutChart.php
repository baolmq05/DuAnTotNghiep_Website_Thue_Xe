<?php

namespace App\Filament\Widgets\Dashboard;

use App\Enum\TripStatus;
use App\Models\Trip;
use Filament\Widgets\ChartWidget;

class StatusDoughnutChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?int $height = 80;
    protected int | string | array $columnSpan = '1';
    protected ?string $heading = 'Biểu đồ trạng thái đơn thuê';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Trạng thái đơn thuê',
                    'data' => [
                        Trip::where('status', TripStatus::Pending->value)->count(),
                        Trip::where('status', TripStatus::WaitingPayment->value)->count(),
                        Trip::where('status', TripStatus::Confirmed->value)->count(),
                        Trip::where('status', TripStatus::Ongoing->value)->count(),
                        Trip::where('status', TripStatus::Complete->value)->count(),
                        Trip::where('status', TripStatus::UserCancel->value)->count(),
                        Trip::where('status', TripStatus::OwnerCancel->value)->count(),
                        Trip::where('status', TripStatus::WaitingExtension->value)->count(),
                        Trip::where('status', TripStatus::WaitingReturn->value)->count(),
                    ],
                    'backgroundColor' => [
                        '#f59e0b', // Pending (Chờ duyệt)
                        '#0284c7', // WaitingPayment (Chờ thanh toán)
                        '#64748b', // Confirmed (Đã xác nhận)
                        '#10b981', // Ongoing (Đang diễn ra)
                        '#3b82f6', // Complete (Đã hoàn thành)
                        '#ef4444', // UserCancel (Người dùng hủy)
                        '#ec4899', // OwnerCancel (Chủ xe hủy)
                        '#6366f1', // WaitingExtension (Chờ gia hạn)
                        '#06b6d4', // WaitingReturn (Chờ trả xe)
                    ],
                ],
            ],
            'labels' => ['Chờ duyệt', 'Chờ thanh toán', 'Đã xác nhận', 'Đang diễn ra', 'Đã hoàn thành', 'Người dùng hủy', 'Chủ xe hủy', 'Chờ gia hạn', 'Chờ trả xe'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
