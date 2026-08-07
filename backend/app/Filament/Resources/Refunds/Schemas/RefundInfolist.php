<?php

namespace App\Filament\Resources\Refunds\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;

class RefundInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                // Cột trái: QR Code (5 cols)
                Section::make('Quét mã QR Chuyển khoản')
                    ->description('Dùng ứng dụng ngân hàng quét mã QR để chuyển khoản nhanh.')
                    ->schema([
                        ViewEntry::make('qr_code')
                            ->label('')
                            ->view('filament.resources.refunds.components.qr-code'),
                    ])
                    ->columnSpan(5),

                // Cột phải: Thông tin chi tiết (7 cols)
                Section::make('Thông tin chi tiết yêu cầu')
                    ->description('Thông tin người thụ hưởng và số tiền yêu cầu rút.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('user.name')
                                ->label('Họ và tên khách hàng')
                                ->weight('bold'),
                            TextEntry::make('user.phone')
                                ->label('Số điện thoại liên hệ')
                                ->copyable(),
                            TextEntry::make('user.bank_name')
                                ->label('Ngân hàng thụ hưởng')
                                ->weight('bold'),
                            TextEntry::make('user.bank_account_number')
                                ->label('Số tài khoản')
                                ->copyable()
                                ->weight('bold'),
                            TextEntry::make('amount')
                                ->label('Số tiền rút')
                                ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') . ' VNĐ')
                                ->color('danger')
                                ->weight('bold'),
                            TextEntry::make('status')
                                ->label('Trạng thái')
                                ->badge(),
                            TextEntry::make('transaction_id')
                                ->label('Mã giao dịch ngân hàng')
                                ->placeholder('Chưa có mã giao dịch')
                                ->copyable(),
                            TextEntry::make('description')
                                ->label('Mô tả/Ghi chú')
                                ->placeholder('Không có mô tả')
                                ->columnSpanFull(),
                        ]),
                      ])
                    ->columnSpan(7),
            ]);
    }
}
