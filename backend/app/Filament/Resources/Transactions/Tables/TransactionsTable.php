<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Khách hàng')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Số tiền')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') . ' VNĐ')
                    ->sortable(),
                TextColumn::make('prepay')
                    ->label('Đặt cọc trước')
                    ->formatStateUsing(function ($state, $record) {
                        $trip = $record->trip;
                        if (!$trip) {
                            if (!$record->amount) {
                                return '0%';
                            }
                            $percentage = ($state / $record->amount) * 100;
                            return round($percentage) . '%';
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
                TextColumn::make('trip.id')
                    ->label('Mã chuyến đi (ID)')
                    ->searchable()
                    ->sortable(),
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
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
