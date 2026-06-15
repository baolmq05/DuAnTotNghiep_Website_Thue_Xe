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
            Section::make('Thông tin người đăng ký')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('owner.name')->label('Họ và tên'),
                        TextEntry::make('owner.email')->label('Email (Tài khoản)'),
                    ]),
                ]),
                Section::make('Hình ảnh phương tiện')
                ->schema([
                    Grid::make(3) 
                        ->schema([
                            ImageEntry::make('images.image_url')
                                ->label('')
                                ->columnSpanFull() 
                                ->extraAttributes(['class' => 'rounded-lg shadow-md']),
                        ]),
                ]),
            Section::make('Chi tiết phương tiện')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('name')->label('Tên xe')->weight('bold')->color('primary'),
                        TextEntry::make('license_plate')->label('Biển số xe'),
                        TextEntry::make('unit_price')->label('Giá thuê cơ bản/ngày')->money('VND'),
                        
                        TextEntry::make('carBrand.brand_name')->label('Thương hiệu'),
                        TextEntry::make('carType.type_name')->label('Loại xe'),
                        TextEntry::make('seat_count')->label('Số chỗ ngồi'),
                    ]),
                ]),
        ]);
    }
}