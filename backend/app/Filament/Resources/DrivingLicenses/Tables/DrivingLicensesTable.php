<?php

namespace App\Filament\Resources\DrivingLicenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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
                    ->label(new HtmlString('Ảnh giấy phép lái xe<style>.hover-zoom-image{transition:0.2s}.hover-zoom-image:hover{transform:scale(2.8);z-index:999999!important;position:relative!important;border-radius:8px!important;box-shadow:0 10px 15px rgba(0,0,0,0.3);cursor:zoom-in}tr:has(.hover-zoom-image:hover){position:relative!important;z-index:9998!important}td:has(.hover-zoom-image:hover){position:relative!important;z-index:9999!important}table:has(.hover-zoom-image:hover) th,table:has(.hover-zoom-image:hover) td:not(:has(.hover-zoom-image:hover)){z-index:1!important}*:has(.hover-zoom-image:hover){overflow:visible!important}</style>'))
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
