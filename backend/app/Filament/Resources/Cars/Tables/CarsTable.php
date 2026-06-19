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



class CarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images.image_url')
                    ->label(new \Illuminate\Support\HtmlString('Ảnh đại diện<style>.hover-zoom-image:hover { transform: scale(4.5); z-index: 99999 !important; position: relative !important; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.3); cursor: zoom-in; } .hover-zoom-image { transition: all 0.2s ease-in-out; } td:has(.hover-zoom-image:hover), td:has(.hover-zoom-image:hover) * { overflow: visible !important; z-index: 9999 !important; }</style>'))
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
