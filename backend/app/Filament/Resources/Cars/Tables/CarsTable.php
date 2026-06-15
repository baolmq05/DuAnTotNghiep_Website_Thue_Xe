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
                    ->label('Ảnh đại diện')
                    ->circular()
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
                ViewAction::make('xem_chi_tiet')
                    ->label('Xem chi tiết')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Thông tin chi tiết xe')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng')
                    ->modalActions([
                        Action::make('approve')
                            ->label('Duyệt xe')
                            ->icon('heroicon-o-check-circle')
                            ->color('success')
                            ->requiresConfirmation()
                            ->action(function ($record, $livewire) {
                                $record->update(['status' => 1]);
                                $livewire->dispatch('refresh'); 
                            })
                            ->visible(fn ($record) => $record->status == 2),
            
                        Action::make('reject')
                            ->label('Từ chối')
                            ->icon('heroicon-o-x-circle')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->action(function ($record, $livewire) {
                                $record->update(['status' => 3]);
                                $livewire->dispatch('refresh'); 
                            })
                            ->visible(fn ($record) => $record->status == 2),
                    ]),
            ])                
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    
    }
}   
