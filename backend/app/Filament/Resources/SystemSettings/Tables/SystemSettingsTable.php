<?php

namespace App\Filament\Resources\SystemSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SystemSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->label('Nhóm')
                    ->badge()
                    ->sortable(),

                TextColumn::make('key')
                    ->label('Mã Cấu Hình (Key)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Đã sao chép key'),

                TextColumn::make('value')
                    ->label('Giá trị (Value)')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('updatedBy.name')
                    ->label('Người cập nhật')
                    ->default('Hệ thống')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label('Nhóm cấu hình')
                    ->options([
                        'finance' => 'Tài chính & Phí',
                        'general' => 'Cấu hình chung',
                        'mail'    => 'Cấu hình Email',
                        'payment' => 'Thanh toán',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
