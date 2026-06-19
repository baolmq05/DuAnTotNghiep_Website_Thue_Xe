<?php

namespace App\Filament\Resources\Trips\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TripsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('car.name')
                    ->label('Xe thuê')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('car.owner.name')
                    ->label('Chủ xe')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Khách hàng')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cost')
                    ->label('Chi phí chuyến đi')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') . ' VNĐ')
                    ->sortable(),
                TextColumn::make('discount_amount')
                    ->label('Giảm giá')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') . ' VNĐ')
                    ->sortable(),
                TextColumn::make('trip_type')
                    ->label('Loại thuê')
                    ->formatStateUsing(fn ($state): string => match ((int) $state) {
                        0 => 'Thuê theo ngày',
                        1 => 'Thuê theo km',
                        default => 'Không xác định',
                    })
                    ->color(fn ($state): string => match ((int) $state) {
                        0 => 'info',
                        1 => 'warning',
                        default => 'gray',
                    })
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn ($state): string => match ((int) $state) {
                        0 => 'Chưa bắt đầu',
                        1 => 'Đang diễn ra',
                        2 => 'Đã hoàn thành',
                        3 => 'Hủy bởi khách',
                        4 => 'Hủy bởi chủ xe',
                        default => 'Không xác định',
                    })
                    ->color(fn ($state): string => match ((int) $state) {
                        0 => 'gray',
                        1 => 'info',
                        2 => 'success',
                        3 => 'danger',
                        4 => 'danger',
                        default => 'gray',
                    })
                    ->badge()
                    ->sortable(),
                TextColumn::make('start_at')
                    ->label('Thời gian bắt đầu')
                    ->dateTime('H:i d/m/Y')
                    ->sortable(),
                TextColumn::make('end_at')
                    ->label('Thời gian kết thúc')
                    ->dateTime('H:i d/m/Y')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('H:i d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime('H:i d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('car_id')
                    ->label('Xe thuê')
                    ->relationship('car', 'name'),
                \Filament\Tables\Filters\SelectFilter::make('user_id')
                    ->label('Khách hàng')
                    ->relationship('user', 'name'),
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        0 => 'Chưa bắt đầu',
                        1 => 'Đang diễn ra',
                        2 => 'Đã hoàn thành',
                        3 => 'Hủy bởi khách',
                        4 => 'Hủy bởi chủ xe',
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('trip_type')
                    ->label('Loại thuê')
                    ->options([
                        0 => 'Thuê theo ngày',
                        1 => 'Thuê theo km',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
