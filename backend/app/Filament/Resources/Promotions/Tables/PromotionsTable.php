<?php

namespace App\Filament\Resources\Promotions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class PromotionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Tên khuyến mãi')
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Mã khuyến mãi')
                    ->searchable(),
                // TextColumn::make('discount_type')
                //     ->label('Loại khuyến mãi')
                //     ->badge(),
                TextColumn::make('discount_value')
                    ->label('Giá trị khuyến mãi')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->discount_type == 0
                            ? $state . '%'
                            : number_format($state, 0, ',', '.') . ' VNĐ'
                    )
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Ngày kết thúc')
                    ->date()
                    ->sortable(),
                // TextColumn::make('usage_limit')
                //     ->label('Giới hạn sử dụng')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('per_user_limit')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Hiện' : 'Ẩn'),
                // TextColumn::make('user_id')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        1 => 'Đang hoạt động',
                        0 => 'Tạm ẩn',
                    ]),
                SelectFilter::make('discount_type')
                    ->label('Loại khuyến mãi')
                    ->options([
                        0 => 'Giảm theo %',
                        1 => 'Giảm tiền mặt',
                    ]),
                Filter::make('active_now')
                    ->label('Hiệu lực hiện tại')
                    ->query(
                        fn($query) => $query
                            ->where('start_date', '<=', now())
                            ->where('end_date', '>=', now())
                    )
                    ->label('Đang hiệu lực'),
                Filter::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->form([
                        DatePicker::make('from')
                            ->label('Ngày bắt đầu'),
                        DatePicker::make('to')
                            ->label('Ngày kết thúc'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('start_date', '>=', $data['from']))
                            ->when($data['to'], fn($q) => $q->whereDate('start_date', '<=', $data['to']));
                    }),
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
            ->recordActionsColumnLabel('Hành động')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
