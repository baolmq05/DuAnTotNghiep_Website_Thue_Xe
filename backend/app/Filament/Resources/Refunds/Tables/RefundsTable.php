<?php

namespace App\Filament\Resources\Refunds\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Refunds\RefundResource;

class RefundsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Khách hàng')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.phone')
                    ->label('Số điện thoại')
                    ->searchable(),
                TextColumn::make('user.bank_name')
                    ->label('Ngân hàng')
                    ->searchable(),
                TextColumn::make('user.bank_account_number')
                    ->label('Số tài khoản')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Số tiền rút')
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.') . ' VNĐ')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->sortable(),
                TextColumn::make('transaction_id')
                    ->label('Mã GD chuyển tiền')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Ngày yêu cầu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Xử lý'),
            ])
            ->recordUrl(
                fn (\App\Models\Refund $record): string => RefundResource::getUrl('view', ['record' => $record])
            )
            ->toolbarActions([
                //
            ]);
    }
}
