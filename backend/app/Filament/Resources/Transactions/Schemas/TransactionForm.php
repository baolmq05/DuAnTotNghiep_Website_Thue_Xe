<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Khách hàng')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('transaction_code')
                    ->label('Mã giao dịch')
                    ->required(),
                TextInput::make('amount')
                    ->label('Số tiền')
                    ->numeric()
                    ->suffix('VNĐ')
                    ->required(),
                TextInput::make('prepay')
                    ->label('Đặt cọc trước')
                    ->numeric()
                    ->suffix('VNĐ')
                    ->required(),
                Select::make('trip_id')
                    ->label('Mã chuyến đi')
                    ->relationship('trip', 'trip_code')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->trip_code ? "{$record->trip_code} (#{$record->id})" : "#{$record->id}")
                    ->searchable()
                    ->preload()
                    ->default(null),
            ]);
    }
}
