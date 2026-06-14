<?php

namespace App\Filament\Resources\CarBrands\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class CarBrandInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Thông tin chung')
                ->description('Các thông tin cơ bản về thương hiệu xe.')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('brand_name')
                            ->label('Tên thương hiệu')
                            ->weight('bold')
                            ->color('primary'),

                        TextEntry::make('status')
                            ->label('Trạng thái')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                '1' => 'success',
                                '0' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                '1' => 'Đang hoạt động',
                                '0' => 'Tạm ẩn',
                                default => 'Không xác định',
                            }),
                    ]),
                ]),

            Section::make('Thời gian hệ thống')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('created_at')
                            ->label('Ngày tạo')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Ngày cập nhật')
                            ->dateTime('d/m/Y H:i'),
                    ]),
                ])
                ->collapsed(), 
        ]);
    }
}
