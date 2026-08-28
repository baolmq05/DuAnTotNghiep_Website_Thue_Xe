<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Trip;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();

        $carIds = Car::where('user_id', $user->id)->pluck('id');
        $totalTrips = Trip::whereIn('car_id', $carIds)->count();

        $completedTrips = Trip::whereIn('car_id', $carIds)
            ->where('status', 4)
            ->count();

        $cancelledTrips = Trip::whereIn('car_id', $carIds)
            ->whereIn('status', [5, 6])
            ->count();

        $approvedCars = Car::where('user_id', $user->id)
            ->where('status', 1)
            ->count();

        $trips = Trip::whereIn('car_id', $carIds)
            ->where('status', 4)
            ->get();

        // doanh thu theo tháng và năm hiện tại
        $year = now()->year;

        $commissionRate = floatval(\App\Models\SystemSetting::get('commission_rate', 18));
        $penaltyRate    = floatval(\App\Models\SystemSetting::get('fee_2_percent', \App\Models\SystemSetting::get('hol_amount_rate', 2)));
        $vatRate        = floatval(\App\Models\SystemSetting::get('vat_rate', 7));
        $revenueRate    = ($commissionRate + $penaltyRate + $vatRate) / 100;

        $isSqlite = \Illuminate\Support\Facades\DB::connection()->getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? "CAST(strftime('%m', created_at) AS INTEGER)" : "MONTH(created_at)";

        $rawData = Trip::selectRaw("{$monthExpr} as month, SUM(cost * ? - COALESCE(promo_discount_amount, 0)) as total", [$revenueRate])
            ->whereIn('car_id', $carIds)
            ->where('status', \App\Enum\TripStatus::Complete->value)
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = max(0, (float) ($rawData[$i] ?? 0));
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_trips' => $totalTrips,
                'completed_trips' => $completedTrips,
                'cancelled_trips' => $cancelledTrips,
                'approved_cars' => $approvedCars,
                'chart' => [
                    'datasets' => [
                        [
                            'label' => 'Doanh thu',
                            'data' => $chartData,
                        ]
                    ],
                    'labels' => [
                        'T1', 'T2', 'T3', 'T4', 'T5', 'T6',
                        'T7', 'T8', 'T9', 'T10', 'T11', 'T12'
                    ]
                ]
            ]
        ]);
    }
}