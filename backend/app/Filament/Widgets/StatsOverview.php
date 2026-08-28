<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Trip;
use App\Models\User;
use App\Models\Car;
use App\Models\Promotion;
use App\Models\Transaction;
use App\Models\Post;
use App\Models\CarBrand;
use App\Models\SystemSetting;
use App\Enum\TripStatus;

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
        // 1. Lấy các tỷ lệ phần trăm từ SystemSetting (hoa hồng: 18%, phạt nguội: 2%, VAT: 7%)
        $commissionRate = floatval(SystemSetting::get('commission_rate', 18));
        $penaltyRate    = floatval(SystemSetting::get('fee_2_percent', SystemSetting::get('hol_amount_rate', 2)));
        $vatRate        = floatval(SystemSetting::get('vat_rate', 7));

        $totalRatePercent = $commissionRate + $penaltyRate + $vatRate; // 18% + 2% + 7% = 27%
        $revenueRate      = $totalRatePercent / 100;

        // 2. Tính tổng doanh thu hệ thống từ tất cả các trip có trạng thái là 4 (Complete - Đã hoàn thành)
        $totalRevenue = max(0, (float) (Trip::where('status', TripStatus::Complete->value)
            ->selectRaw('SUM(cost * ? - COALESCE(promo_discount_amount, 0)) as total', [$revenueRate])
            ->value('total') ?? 0));

        // 3. Tính doanh thu tháng hiện tại và tháng trước để so sánh tỷ lệ tăng trưởng (%)
        $currentRevenue = max(0, (float) (Trip::where('status', TripStatus::Complete->value)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('SUM(cost * ? - COALESCE(promo_discount_amount, 0)) as total', [$revenueRate])
            ->value('total') ?? 0));

        $lastRevenue = max(0, (float) (Trip::where('status', TripStatus::Complete->value)
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->selectRaw('SUM(cost * ? - COALESCE(promo_discount_amount, 0)) as total', [$revenueRate])
            ->value('total') ?? 0));

        $growth = $lastRevenue > 0 ? (($currentRevenue - $lastRevenue) / $lastRevenue) * 100 : 0;

        // Tính số người dùng mới trong tháng hiện tại
        $newUsersThisMonth = User::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count();

        // Tính số đơn thuê trong tháng hiện tại
        $currentMonthTrips = Trip::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count();

        // Tính số khuyến mãi đang hoạt động
        $activePromotions = Promotion::where('start_date', '<=', now())->where('end_date', '>=', now())->count();

        return [
            Stat::make('Xe', Car::count())
                ->description('Tổng xe trong hệ thống')
                ->color('success')
                ->icon('heroicon-o-truck'),

            Stat::make('Người dùng', User::count())
                ->description("+{$newUsersThisMonth} người tháng này")
                ->color('success')
                ->icon('heroicon-o-users'),

            Stat::make('Đơn thuê', Trip::count())
                ->description("+{$currentMonthTrips} đơn tháng này")
                ->color('warning')
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Doanh thu', number_format($totalRevenue) . ' VND')
                ->description(number_format($growth, 1) . '% so với tháng trước')
                ->descriptionIcon($growth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($growth >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Giao dịch', Transaction::count())
                ->description('Tổng số giao dịch')
                ->color('info')
                ->icon('heroicon-o-credit-card'),

            Stat::make('Khuyến mãi', $activePromotions)
                ->description('Khuyến mãi đang hoạt động')
                ->color('primary')
                ->icon('heroicon-o-gift'),

            Stat::make('Bài viết', Post::count())
                ->description('Tổng số bài viết')
                ->color('warning')
                ->icon('heroicon-o-newspaper'),

            Stat::make('Hãng xe', CarBrand::count())
                ->description('Tổng số hãng xe')
                ->color('gray')
                ->icon('heroicon-o-building-office-2'),
        ];
    }
}
