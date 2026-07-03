<?php

namespace App\Filament\Resources\Cars\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\HtmlString;



class CarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images.image_url')
                    ->label(new HtmlString('Ảnh đại diện<style>.hover-zoom-image{transition:0.2s}.hover-zoom-image:hover{transform:scale(2.8);z-index:999999!important;position:relative!important;border-radius:8px!important;box-shadow:0 10px 15px rgba(0,0,0,0.3);cursor:zoom-in}tr:has(.hover-zoom-image:hover){position:relative!important;z-index:9998!important}td:has(.hover-zoom-image:hover){position:relative!important;z-index:9999!important}table:has(.hover-zoom-image:hover) th,table:has(.hover-zoom-image:hover) td:not(:has(.hover-zoom-image:hover)){z-index:1!important}*:has(.hover-zoom-image:hover){overflow:visible!important}</style>'))
                    ->circular()
                    ->extraImgAttributes([
                        'class' => 'hover-zoom-image',
                    ])
                    ->limit(1),
                TextColumn::make('owner.name')
                    ->label('Chủ sở hữu')
                    ->searchable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('owner.email')
                    ->label('Email liên hệ')
                    ->searchable()
                    ->copyable()
                    ->tooltip('Click để copy'),

                TextColumn::make('name')
                    ->label('Tên xe')
                    ->searchable(),

                TextColumn::make('license_plate')
                    ->label('Biển số')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn(string $state): string => match ((string) $state) {
                        '2' => 'warning',
                        '1' => 'success',
                        '3' => 'danger',
                        '0' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ((string) $state) {
                        '2' => 'Chờ duyệt',
                        '1' => 'Đã duyệt',
                        '3' => 'Bị từ chối',
                        '0' => 'Dừng hoạt động',
                        default => 'Không xác định',
                    }),

                TextColumn::make('created_at')
                    ->label('Ngày gửi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Lọc trạng thái')
                    ->options([
                        '2' => 'Chờ duyệt',
                        '1' => 'Đã duyệt',
                        '3' => 'Bị từ chối',
                        '0' => 'Dừng hoạt động',
                    ])
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Xem chi tiết')
                    ->icon('heroicon-o-eye')
                    ->color('info'),
            ])                
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    
    }
}   
