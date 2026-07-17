<?php

namespace App\Filament\Resources\Refunds\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RefundForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('wallet_id')
                    ->label('Khách hàng yêu cầu')
                    ->relationship('user', 'name')
                    ->disabled(),
                TextInput::make('amount')
                    ->label('Số tiền rút')
                    ->numeric()
                    ->suffix('VNĐ')
                    ->disabled(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Chờ xử lý (Pending)',
                        'processing' => 'Đang xử lý (Processing)',
                        'completed' => 'Hoàn thành (Completed)',
                        'failed' => 'Thất bại (Failed)',
                        'canceled' => 'Đã hủy (Canceled)',
                    ])
                    ->required(),
                TextInput::make('transaction_id')
                    ->label('Mã giao dịch MoMo/Ngân hàng')
                    ->placeholder('Nhập mã giao dịch chuyển tiền...'),
                Textarea::make('description')
                    ->label('Mô tả/Chi tiết')
                    ->rows(3),
            ]);
    }
}
