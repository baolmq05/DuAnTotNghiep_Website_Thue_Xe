<?php

namespace App\Filament\Resources\DrivingLicenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DrivingLicensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Họ và tên')
                    ->searchable(),
                ImageColumn::make('image')
                    ->label(new \Illuminate\Support\HtmlString('Ảnh giấy phép lái xe<style>.hover-zoom-image:hover { transform: scale(4.5); z-index: 99999 !important; position: relative !important; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.3); cursor: zoom-in; } .hover-zoom-image { transition: all 0.2s ease-in-out; } td:has(.hover-zoom-image:hover), td:has(.hover-zoom-image:hover) * { overflow: visible !important; z-index: 9999 !important; }</style>'))
                    ->circular()
                    ->extraImgAttributes([
                        'class' => 'hover-zoom-image',
                    ]),
                TextColumn::make('driving_license_number')
                    ->label('Số giấy phép')
                    ->searchable(),
                TextColumn::make('DOB')
                    ->label('Ngày sinh')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn(string $state): string => match ((string) $state) {
                        '0' => 'warning',
                        '1' => 'success',
                        '2' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ((string) $state) {
                        '0' => 'Chờ duyệt',
                        '1' => 'Đã duyệt',
                        '2' => 'Bị từ chối',
                        default => 'Không xác định',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
