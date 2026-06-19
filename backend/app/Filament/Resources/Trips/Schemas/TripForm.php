<?php

namespace App\Filament\Resources\Trips\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('car_id')
                    ->label('Xe thuê')
                    ->relationship('car', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Placeholder::make('owner_name')
                    ->label('Chủ xe')
                    ->content(fn ($record) => $record?->car?->owner?->name ?? 'N/A'),
                Select::make('user_id')
                    ->label('Khách hàng')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                TextInput::make('cost')
                    ->label('Chi phí chuyến đi')
                    ->required()
                    ->numeric()
                    ->suffix(' VND')
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                TextInput::make('discount_amount')
                    ->label('Số tiền giảm giá')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->suffix(' VND')
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Select::make('trip_type')
                    ->label('Loại thuê')
                    ->options([
                        0 => 'Thuê theo ngày',
                        1 => 'Thuê theo km',
                    ])
                    ->default(0)
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        0 => 'Chưa bắt đầu',
                        1 => 'Đang diễn ra',
                        2 => 'Đã hoàn thành',
                        3 => 'Đã hủy bởi người dùng',
                        4 => 'Đã hủy bởi chủ xe',
                    ])
                    ->default(0)
                    ->required(),
                DateTimePicker::make('start_at')
                    ->label('Thời gian bắt đầu')
                    ->required(),
                DateTimePicker::make('end_at')
                    ->label('Thời gian kết thúc')
                    ->required(),
            ]);
    }
}
