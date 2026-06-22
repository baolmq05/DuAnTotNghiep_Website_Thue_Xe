<?php
namespace App\Filament\Widgets\Dashboard;

use Filament\Widgets\ChartWidget;
class UserChart extends ChartWidget
{
    protected static ?int $sort = 4; 
    protected ?int $height = 80;

    protected int | string | array $columnSpan = '2';

    protected ?string $heading = 'Biểu đồ người dùng mới theo tháng';

    protected function getData(): array
    {
        $year = now()->year;
        $rawData = \App\Models\User::selectRaw('MONTH(created_at) as month, count(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $rawData[$i] ?? 0;
        }
        return [
            'datasets' => [
                [
                    'label' => 'Người dùng mới',
                    'data' => $data,
                ],
            ],
            'labels' => ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    
}
