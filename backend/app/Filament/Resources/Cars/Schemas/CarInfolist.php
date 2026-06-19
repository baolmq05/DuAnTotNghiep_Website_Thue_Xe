<?php

namespace App\Filament\Resources\Cars\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class CarInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // 1. Thông tin người đăng ký
            Section::make('Thông tin chủ sở hữu')
                ->icon('heroicon-o-user')
                ->description('Chi tiết thông tin liên hệ của chủ xe.')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('owner.name')
                            ->label('Họ và tên')
                            ->weight('bold'),
                        TextEntry::make('owner.email')
                            ->label('Email (Tài khoản)'),
                        TextEntry::make('owner.phone')
                            ->label('Số điện thoại')
                            ->copyable()
                            ->color('primary')
                            ->weight('bold'),
                    ]),
                ]),

            // 2. Thông tin chung & Kỹ thuật
            Section::make('Thông số kỹ thuật phương tiện')
                ->icon('heroicon-o-information-circle')
                ->description('Thông tin chi tiết về cấu hình và kỹ thuật xe.')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('name')
                            ->label('Tên xe')
                            ->weight('bold')
                            ->color('primary'),
                        
                        TextEntry::make('license_plate')
                            ->label('Biển số xe')
                            ->weight('bold'),

                        TextEntry::make('status')
                            ->label('Trạng thái duyệt')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                '2' => 'warning',
                                '1' => 'success',
                                '3' => 'danger',
                                '0' => 'gray',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                '2' => 'Chờ duyệt',
                                '1' => 'Đã duyệt',
                                '3' => 'Bị từ chối',
                                '0' => 'Dừng hoạt động',
                                default => 'Không xác định',
                            }),

                        TextEntry::make('carBrand.brand_name')
                            ->label('Thương hiệu'),

                        TextEntry::make('carType.type_name')
                            ->label('Loại xe'),

                        TextEntry::make('seat_count')
                            ->label('Số chỗ ngồi')
                            ->suffix(' chỗ'),

                        TextEntry::make('manufacture_year')
                            ->label('Năm sản xuất')
                            ->date('Y'),

                        TextEntry::make('transmission')
                            ->label('Hộp số / Truyền động')
                            ->badge()
                            ->color('info'),

                        TextEntry::make('fuel_type')
                            ->label('Loại nhiên liệu')
                            ->badge()
                            ->color('warning'),

                        TextEntry::make('fuel_consumption')
                            ->label('Mức tiêu thụ nhiên liệu')
                            ->suffix(' L/100km'),
                    ]),
                ]),

            // 3. Giá cả & Khuyến mãi
            Section::make('Giá thuê & Ưu đãi')
                ->icon('heroicon-o-currency-dollar')
                ->description('Thông tin về đơn giá thuê xe và giá trị giảm giá hiện hành.')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('unit_price')
                            ->label('Giá thuê cơ bản / ngày')
                            ->money('VND')
                            ->color('success')
                            ->weight('bold'),

                        TextEntry::make('discount_value')
                            ->label('Giá trị giảm giá / ngày')
                            ->money('VND')
                            ->color('danger')
                            ->weight('bold'),
                    ]),
                ]),

            // 4. Vị trí & Quy định giao nhận
            Section::make('Vị trí & Quy định giao nhận')
                ->icon('heroicon-o-map-pin')
                ->description('Địa chỉ xe và các quy định về giới hạn quãng đường di chuyển cũng như giao nhận.')
                ->schema([
                    Grid::make(2)->schema([
                        // Địa chỉ xe
                        Section::make('Địa chỉ xe')
                            ->schema([
                                TextEntry::make('carLocation.street_name')
                                    ->label('Địa chỉ chi tiết (Đường)'),
                                TextEntry::make('carLocation.ward_code')
                                    ->label('Mã phường/xã'),
                                TextEntry::make('carLocation.province_id')
                                    ->label('Mã tỉnh/thành phố'),
                            ])->columnSpan(1),

                        // Giao nhận xe & Giới hạn
                        Section::make('Chính sách vận hành')
                            ->schema([
                                TextEntry::make('deliveryOption.max_distance')
                                    ->label('Khoảng cách giao xe tối đa')
                                    ->suffix(' km'),
                                TextEntry::make('deliveryOption.fee_distance')
                                    ->label('Phí giao xe phụ trội')
                                    ->money('VND')
                                    ->suffix('/km'),
                                TextEntry::make('usageLimit.max_daily_distance')
                                    ->label('Quãng đường giới hạn / ngày')
                                    ->suffix(' km'),
                                TextEntry::make('usageLimit.extra_distance_fee')
                                    ->label('Phí phụ trội vượt giới hạn')
                                    ->money('VND')
                                    ->suffix('/km'),
                            ])->columnSpan(1),
                    ]),
                ]),

            // 5. Tính năng tiện ích
            Section::make('Tiện ích đi kèm')
                ->icon('heroicon-o-sparkles')
                ->description('Các tính năng bổ sung của phương tiện.')
                ->schema([
                    TextEntry::make('features.feature_name')
                        ->label('')
                        ->badge()
                        ->color('success')
                        ->placeholder('Không có tiện ích đi kèm nào.')
                        ->columnSpanFull(),
                ]),

            // 6. Mô tả & Điều khoản
            Section::make('Mô tả & Điều khoản')
                ->icon('heroicon-o-document-text')
                ->description('Mô tả chi tiết xe và các điều kiện bắt buộc đối với khách thuê.')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('description')
                            ->label('Mô tả chi tiết')
                            ->placeholder('Chưa cập nhật mô tả.')
                            ->html(),
                        TextEntry::make('rental_terms')
                            ->label('Điều khoản thuê xe')
                            ->placeholder('Chưa cập nhật điều khoản.')
                            ->html(),
                    ]),
                ]),

            // 7. Hình ảnh phương tiện
            Section::make('Hình ảnh phương tiện')
                ->icon('heroicon-o-photo')
                ->description('Danh sách hình ảnh chi tiết của xe.')
                ->schema([
                    ImageEntry::make('images.image_url')
                        ->label('')
                        ->extraImgAttributes([
                            'class' => 'rounded-lg shadow-md border object-cover',
                            'style' => 'aspect-ratio: 16/9; max-height: 200px; display: inline-block; margin: 4px;',
                        ])
                        ->columnSpanFull(),
                ]),
        ]);
    }
}