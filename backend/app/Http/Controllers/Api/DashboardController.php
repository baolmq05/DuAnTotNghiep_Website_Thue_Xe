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

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để xem thống kê.'
            ], 401);
        }

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

        $rawData = Trip::selectRaw('MONTH(created_at) as month, SUM(cost - discount_amount) as total')
            ->whereIn('car_id', $carIds)
            ->where('status', 4)
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $rawData[$i] ?? 0;
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