<?php

namespace App\Filament\Widgets\Dashboard;

use Filament\Widgets\ChartWidget;
class RevenueChart extends ChartWidget
{
    protected static ?int $sort = 3; 
    protected ?int $height = 80;
    protected int | string | array $columnSpan = '1';
    protected ?string $heading = 'Biểu đồ doanh thu theo tháng';

    protected function getData(): array
    {
        $year = now()->year;

        $commissionRate = floatval(\App\Models\SystemSetting::get('commission_rate', 18));
        $penaltyRate    = floatval(\App\Models\SystemSetting::get('fee_2_percent', \App\Models\SystemSetting::get('hol_amount_rate', 2)));
        $vatRate        = floatval(\App\Models\SystemSetting::get('vat_rate', 7));
        $revenueRate    = ($commissionRate + $penaltyRate + $vatRate) / 100;

        $isSqlite = \Illuminate\Support\Facades\DB::connection()->getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? "CAST(strftime('%m', created_at) AS INTEGER)" : "MONTH(created_at)";

        $rawData = \App\Models\Trip::selectRaw("{$monthExpr} as month, SUM(cost * ? - COALESCE(promo_discount_amount, 0)) as total", [$revenueRate])
            ->where('status', \App\Enum\TripStatus::Complete->value)
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = max(0, (float) ($rawData[$i] ?? 0));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Doanh thu',
                    'data' => $data,
                ],
            ],
            'labels' => ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    
}
