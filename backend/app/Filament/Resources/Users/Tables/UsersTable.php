<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Họ và tên')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->placeholder('Chưa có')
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('Giới tính')
                    ->formatStateUsing(fn ($state): string => match ((string) $state) {
                        '0' => 'Nữ',
                        '1' => 'Nam',
                        default => 'Khác',
                    })
                    ->color(fn ($state): string => match ((string) $state) {
                        '0' => 'danger',
                        '1' => 'info',
                        default => 'gray',
                    })
                    ->badge(),
                TextColumn::make('role.name')
                    ->label('Vai trò')
                    ->formatStateUsing(fn ($state): string => match ((string) $state) {
                        'Admin' => 'Quản trị viên',
                        'User' => 'Người dùng',
                        'Car Owner' => 'Chủ xe',
                        default => 'Unknown',
                    })
                    ->color(fn ($state): string => match ((string) $state) {
                        'Admin' => 'danger',
                        'User' => 'warning',
                        default => 'info',
                    })
                    ->badge(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn ($state): string => (string) $state === '1' ? 'Đang hoạt động' : 'Bị khóa')
                    ->color(fn ($state): string => (string) $state === '1' ? 'success' : 'danger')
                    ->badge(),
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
                    SelectFilter::make('role_id')
                        ->label('Vai trò')
                        ->options([
                            '1' => 'Quản trị viên',
                            '2' => 'Người dùng',
                            '3' => 'Chủ xe',
                        ]),
    
                    SelectFilter::make('status')
                        ->label('Trạng thái')
                        ->options([
                            '1' => 'Đang hoạt động',
                            '0' => 'Bị khóa',
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
