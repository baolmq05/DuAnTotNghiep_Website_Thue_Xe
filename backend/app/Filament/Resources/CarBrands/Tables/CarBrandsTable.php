<?php

namespace App\Filament\Resources\CarBrands\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class CarBrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('brand_name')
                    ->label('Tên thương hiệu')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-building-office-2')
                    ->color('primary'),
                TextColumn::make('car_types_count')
                    ->label('Số loại xe')
                    ->counts('carTypes')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([

                SelectFilter::make('brand_name')
                    ->label('Thương hiệu'),
            ])

            ->recordActions([
                ViewAction::make()
                    ->label('')
                    ->tooltip('Xem chi tiết')
                    ->icon('heroicon-o-eye')
                    ->color('info'),

                EditAction::make()
                    ->label('')
                    ->tooltip('Chỉnh sửa')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
