<?php

namespace App\Filament\Resources\Promotions\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;

class PromotionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make(' Tổng quan khuyến mãi')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('code')
                            ->label('Mã khuyến mãi')
                            ->weight('bold')
                            ->copyable(),
                        TextEntry::make('status')
                            ->label('Trạng thái')
                            ->badge()
                            ->color(fn($state) => $state ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state ? 'Đang hoạt động' : 'Ẩn'),
                        TextEntry::make('name')
                            ->label('Tên khuyến mãi')
                            ->weight('bold'),
                        // ->columnSpanFull(),
                        TextEntry::make('per_user_limit')
                            ->label('Mỗi người dùng')
                            ->placeholder('Không giới hạn'),
                    ]),
                ]),

            Section::make(' Thông tin giảm giá')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('discount_type')
                            ->label('Loại khuyến mãi')
                            ->badge()
                            ->color('warning')
                            ->formatStateUsing(fn($state) => match ((string) $state) {
                                '0' => 'Giảm theo %',
                                '1' => 'Giảm tiền mặt',
                                default => 'Không xác định',
                            }),
                        TextEntry::make('discount_value')
                            ->label('Giá trị giảm')
                            ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                            ->suffix(
                                fn($record) =>
                                $record->discount_type == 0 ? '%' : ' VNĐ'
                            ),
                        TextEntry::make('usage_limit')
                            ->label('Tổng lượt sử dụng')
                            ->placeholder('Không giới hạn'),
                        TextEntry::make('description')
                            ->label('')
                            ->placeholder('Không có mô tả')
                            ->label('Mô tả')
                            ->columnSpanFull(),
                    ]),
                ]),

            Section::make(' Thời gian áp dụng')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('start_date')
                            ->label('Bắt đầu')
                            ->date('d/m/Y'),
                        TextEntry::make('end_date')
                            ->label('Kết thúc')
                            ->date('d/m/Y'),
                    ]),
                ]),

            Section::make(' Thông tin hệ thống')
                ->schema([
                    Grid::make()->schema([
                        TextEntry::make('created_at')
                            ->label('Ngày tạo')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Ngày cập nhật')
                            ->dateTime('d/m/Y H:i'),
                    ]),
                ])
                ->collapsible(),
        ]);
    }
}
