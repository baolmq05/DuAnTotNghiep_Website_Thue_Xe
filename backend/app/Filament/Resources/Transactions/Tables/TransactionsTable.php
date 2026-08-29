<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_code')
                    ->label('Mã giao dịch')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Loại giao dịch')
                    ->getStateUsing(function ($record) {
                        if (!$record->trip_id) {
                            return 'Rút tiền';
                        }
                        return $record->prepay > 0 ? 'Đặt cọc chuyến' : 'Thanh toán chuyến';
                    })
                    ->badge()
                    ->color(function ($record) {
                        if (!$record->trip_id) {
                            return 'danger';
                        }
                        return $record->prepay > 0 ? 'warning' : 'success';
                    })
                    ->icon(function ($record) {
                        if (!$record->trip_id) {
                            return 'heroicon-o-arrow-up-right';
                        }
                        return $record->prepay > 0 ? 'heroicon-o-shield-check' : 'heroicon-o-check-circle';
                    }),

                TextColumn::make('user.name')
                    ->label('Người thực hiện')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Số tiền')
                    ->formatStateUsing(function ($state) {
                        $num = (float) $state;
                        if ($num < 0) {
                            return '-' . number_format(abs($num), 0, ',', '.') . ' VNĐ';
                        }
                        return '+' . number_format($num, 0, ',', '.') . ' VNĐ';
                    })
                    ->color(fn ($state) => (float) $state < 0 ? 'danger' : 'success')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('prepay')
                    ->label('Đặt cọc trước')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->trip_id) {
                            return '—';
                        }

                        $trip = $record->trip;
                        if (!$trip) {
                            return $state ? number_format($state, 0, ',', '.') . ' VNĐ' : '0%';
                        }

                        $netCost = $trip->cost - ($trip->discount_amount ?? 0);
                        if ($netCost <= 0) {
                            return '0%';
                        }

                        $percentage = ($state / $netCost) * 100;
                        $rounded = round($percentage);
                        if ($rounded > 100) {
                            return '100%';
                        }

                        return $rounded . '%';
                    })
                    ->sortable(),

                TextColumn::make('trip.trip_code')
                    ->label('Mã chuyến đi')
                    ->getStateUsing(fn ($record) => $record->trip?->trip_code ?: ($record->trip_id ? ('#' . $record->trip_id) : '—'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Xem chi tiết')
                    ->modalHeading('Chi tiết Giao dịch'),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
