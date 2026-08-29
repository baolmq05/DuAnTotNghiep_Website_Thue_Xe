<?php

namespace App\Filament\Resources\Trips\Schemas;

use App\Enum\TripStatus;
use App\Models\Trip;
use Carbon\Carbon;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TripInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // 1. Tổng quan chuyến đi
                Section::make('Thông tin Tổng quan Chuyến đi')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Grid::make(4)->schema([
                            TextEntry::make('trip_code')
                                ->label('Mã chuyến đi')
                                ->getStateUsing(fn ($record) => $record->trip_code ?: ('#' . $record->id))
                                ->weight('bold')
                                ->color('primary'),

                            TextEntry::make('status')
                                ->label('Trạng thái chuyến đi')
                                ->badge(),

                            TextEntry::make('trip_type')
                                ->label('Hình thức thuê')
                                ->formatStateUsing(fn ($state) => $state == 1 ? 'Thuê theo km' : 'Thuê theo ngày')
                                ->badge()
                                ->color('info'),

                            TextEntry::make('created_at')
                                ->label('Thời gian tạo đơn')
                                ->dateTime('d/m/Y H:i:s'),
                        ]),
                    ]),

                // 2. Lịch trình & Địa điểm giao nhận
                Section::make('Lịch trình & Địa điểm')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('start_at')
                                ->label('Thời gian bắt đầu (Nhận xe)')
                                ->dateTime('d/m/Y H:i')
                                ->weight('medium'),

                            TextEntry::make('end_at')
                                ->label('Thời gian kết thúc (Trả xe)')
                                ->dateTime('d/m/Y H:i')
                                ->weight('medium'),

                            TextEntry::make('duration')
                                ->label('Thời lượng dự kiến')
                                ->getStateUsing(function ($record) {
                                    if (!$record->start_at || !$record->end_at) {
                                        return 'N/A';
                                    }
                                    $start = Carbon::parse($record->start_at);
                                    $end = Carbon::parse($record->end_at);
                                    
                                    $totalHours = (int) round($start->diffInMinutes($end) / 60);
                                    $days = intdiv($totalHours, 24);
                                    $hours = $totalHours % 24;

                                    if ($days > 0 && $hours > 0) {
                                        return "{$days} ngày {$hours} giờ";
                                    } elseif ($days > 0) {
                                        return "{$days} ngày";
                                    } elseif ($hours > 0) {
                                        return "{$hours} giờ";
                                    }
                                    return "0 giờ";
                                })
                                ->badge()
                                ->color('gray'),

                            TextEntry::make('delivery_address')
                                ->label('Địa chỉ giao / nhận xe')
                                ->placeholder('Tại vị trí xe của chủ xe')
                                ->columnSpanFull(),
                        ]),
                    ]),

                // 3. Hai bên liên quan: Chủ xe & Khách thuê
                Section::make('Thông tin Các bên liên quan')
                    ->icon('heroicon-o-users')
                    ->schema([
                        Grid::make(2)->schema([
                            // Khối Chủ xe & Xe
                            Section::make('Phương tiện & Chủ xe')
                                ->schema([
                                    TextEntry::make('car.name')
                                        ->label('Tên phương tiện')
                                        ->weight('bold'),

                                    TextEntry::make('car.license_plate')
                                        ->label('Biển số xe')
                                        ->badge()
                                        ->color('success'),

                                    TextEntry::make('car.owner.name')
                                        ->label('Họ tên Chủ xe')
                                        ->placeholder('N/A'),

                                    TextEntry::make('car.owner.phone')
                                        ->label('Số điện thoại')
                                        ->placeholder('Chưa cập nhật'),

                                    TextEntry::make('car.owner.email')
                                        ->label('Email chủ xe')
                                        ->placeholder('N/A'),
                                ])->columnSpan(1),

                            // Khối Khách thuê
                            Section::make('Khách hàng (Người thuê)')
                                ->schema([
                                    TextEntry::make('user.name')
                                        ->label('Họ tên Khách hàng')
                                        ->weight('bold'),

                                    TextEntry::make('user.phone')
                                        ->label('Số điện thoại')
                                        ->placeholder('Chưa cập nhật'),

                                    TextEntry::make('user.email')
                                        ->label('Email khách hàng')
                                        ->placeholder('N/A'),

                                    TextEntry::make('user.created_at')
                                        ->label('Ngày tham gia')
                                        ->dateTime('d/m/Y'),
                                ])->columnSpan(1),
                        ]),
                    ]),

                // 4. Chi tiết Tài chính & Dòng tiền
                Section::make('Chi tiết Chi phí & Thanh toán')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Grid::make(4)->schema([
                            TextEntry::make('cost')
                                ->label('Tổng chi phí gốc')
                                ->formatStateUsing(fn ($state) => number_format((float) $state, 0, ',', '.') . ' VNĐ')
                                ->weight('bold'),

                            TextEntry::make('car_discount_amount')
                                ->label('Giảm giá từ chủ xe')
                                ->formatStateUsing(fn ($state) => number_format((float) ($state ?? 0), 0, ',', '.') . ' VNĐ')
                                ->color('warning'),

                            TextEntry::make('promo_discount_amount')
                                ->label('Khuyến mãi Voucher')
                                ->formatStateUsing(fn ($state) => number_format((float) ($state ?? 0), 0, ',', '.') . ' VNĐ')
                                ->color('danger'),

                            TextEntry::make('final_amount')
                                ->label('Khách thực trả')
                                ->getStateUsing(function ($record) {
                                    $net = (float) $record->cost - (float) ($record->discount_amount ?? 0);
                                    return number_format(max(0, $net), 0, ',', '.') . ' VNĐ';
                                })
                                ->weight('bold')
                                ->color('success'),

                            TextEntry::make('owner_gross_revenue')
                                ->label('Doanh thu dự tính chủ xe')
                                ->formatStateUsing(fn ($state) => number_format((float) ($state ?? 0), 0, ',', '.') . ' VNĐ')
                                ->color('primary'),

                            TextEntry::make('owner_payment_note')
                                ->label('Trạng thái giải ngân / Lưu ví')
                                ->placeholder('Chưa có thông tin giải ngân')
                                ->columnSpan(3)
                                ->color('gray'),
                        ]),
                    ]),

                // 5. Hình ảnh chuyến đi
                Section::make('Hình ảnh Chuyến đi & Bàn giao xe')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        ImageEntry::make('images.image_url')
                            ->label('Ảnh chụp thực tế')
                            ->placeholder('Chưa có hình ảnh nào được tải lên cho chuyến đi này.')
                            ->extraImgAttributes(['class' => 'rounded-lg object-cover shadow-sm'])
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
