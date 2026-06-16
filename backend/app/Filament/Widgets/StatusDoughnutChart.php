<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Trip;
use Filament\Widgets\ChartWidget;

class StatusDoughnutChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected ?int $height = 80;
    protected int | string | array $columnSpan = '1/2';
    protected ?string $heading = 'Biểu đồ trạng thái đơn thuê';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Trạng thái đơn thuê',
                    'data' => [
                        Trip::where('status', 0)->count(),
                        Trip::where('status', 1)->count(),
                        Trip::where('status', 2)->count(),
                        Trip::where('status', 3)->count(),
                        Trip::where('status', 4)->count(),
                    ],
                    'backgroundColor' => [
                        '#f59e0b',
                        '#3b82f6',
                        '#22c55e',
                        '#ef4444',
                        '#8b5cf6',
                    ],
                ],
            ],
            'labels' => ['Chưa bắt đầu', 'Đang diễn ra', 'Đã hoàn thành', 'Người dùng hủy', 'Chủ xe hủy'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
