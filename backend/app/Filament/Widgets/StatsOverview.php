<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = '2';

    protected function getColumns(): int
    {
        return 4;
    }
    protected function getStats(): array
    {
        //tính tháng hiện tại và tháng trước để so sánh doanh thu
        $currentRevenue = \App\Models\Transaction::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->sum('amount');
        $lastRevenue = \App\Models\Transaction::whereYear('created_at', now()->subMonth()->year)->whereMonth('created_at', now()->subMonth()->month)->sum('amount');
        $growth = $lastRevenue > 0 ? (($currentRevenue - $lastRevenue) / $lastRevenue) * 100 : 0;

        // tính số người dùng mới trong tháng hiện tại
        $newUsersThisMonth = \App\Models\User::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count();

        // tính số đơn thuê trong tháng hiện tại
        $currentMonthTrips = \App\Models\Trip::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count();

        // tính số khuyến mãi đang hoạt động
        $activePromotions = \App\Models\Promotion::where('start_date', '<=', now())->where('end_date', '>=', now())->count();
        
        return [
            Stat::make('Xe', \App\Models\Car::count())
                ->description('Tổng xe trong hệ thống')
                ->color('success')
                ->icon('heroicon-o-truck'),

            Stat::make('Người dùng', \App\Models\User::count())
                ->description("+{$newUsersThisMonth} người tháng này")
                ->color('success')
                ->icon('heroicon-o-users'),

            Stat::make('Đơn thuê', \App\Models\Trip::count())
                ->description("+{$currentMonthTrips} đơn tháng này")
                ->color('warning')
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Doanh thu', number_format(\App\Models\Transaction::sum('amount')) . ' VND')
                ->description(number_format($growth, 1) . '% so với tháng trước')
                ->descriptionIcon($growth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($growth >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Giao dịch', \App\Models\Transaction::count())
                ->description('Tổng số giao dịch')
                ->color('info')
                ->icon('heroicon-o-credit-card'),

            Stat::make('Khuyến mãi', $activePromotions)
                ->description('Khuyến mãi đang hoạt động')
                ->color('primary')
                ->icon('heroicon-o-gift'),

            Stat::make('Bài viết', \App\Models\Post::count())
                ->description('Tổng số bài viết')
                ->color('warning')
                ->icon('heroicon-o-newspaper'),

            Stat::make('Hãng xe', \App\Models\CarBrand::count())
                ->description('Tổng số hãng xe')
                ->color('gray')
                ->icon('heroicon-o-building-office-2'),
        ];
    }
}
