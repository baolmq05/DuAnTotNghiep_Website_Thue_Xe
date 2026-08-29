<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // 1. Thông tin giao dịch
                Section::make('Thông tin Giao dịch')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('transaction_code')
                                ->label('Mã giao dịch')
                                ->weight('bold')
                                ->color('primary'),

                            TextEntry::make('transaction_type')
                                ->label('Loại giao dịch')
                                ->getStateUsing(function ($record) {
                                    if (!$record->trip_id) {
                                        return 'Rút tiền';
                                    }
                                    return $record->prepay > 0 ? 'Đặt cọc chuyến đi' : 'Thanh toán chuyến đi';
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

                            TextEntry::make('amount')
                                ->label('Số tiền giao dịch')
                                ->formatStateUsing(function ($state) {
                                    $num = (float) $state;
                                    if ($num < 0) {
                                        return '-' . number_format(abs($num), 0, ',', '.') . ' VNĐ';
                                    }
                                    return '+' . number_format($num, 0, ',', '.') . ' VNĐ';
                                })
                                ->color(fn ($state) => (float) $state < 0 ? 'danger' : 'success')
                                ->weight('bold'),

                            TextEntry::make('prepay')
                                ->label('Tiền cọc trước (Nếu thuê xe)')
                                ->formatStateUsing(function ($state, $record) {
                                    if (!$record->trip_id) {
                                        return 'Không áp dụng (Giao dịch ví)';
                                    }
                                    return number_format((float) $state, 0, ',', '.') . ' VNĐ';
                                }),

                            TextEntry::make('created_at')
                                ->label('Thời gian thực hiện')
                                ->dateTime('d/m/Y H:i:s'),
                        ]),
                    ]),

                // 2. Thông tin Người thực hiện giao dịch
                Section::make('Người thực hiện Giao dịch')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('user.name')
                                ->label('Họ và tên')
                                ->weight('bold'),

                            TextEntry::make('user.phone')
                                ->label('Số điện thoại')
                                ->placeholder('Chưa cập nhật'),

                            TextEntry::make('user.email')
                                ->label('Email')
                                ->placeholder('N/A'),

                            TextEntry::make('user.bank_name')
                                ->label('Ngân hàng liên kết')
                                ->placeholder('Chưa liên kết')
                                ->visible(fn ($record) => str_starts_with($record->transaction_code, 'WD')),

                            TextEntry::make('user.bank_account_number')
                                ->label('Số tài khoản nhận tiền')
                                ->placeholder('N/A')
                                ->weight('bold')
                                ->visible(fn ($record) => str_starts_with($record->transaction_code, 'WD')),
                        ]),
                    ]),

                // 3. Chuyến đi liên quan
                Section::make('Chuyến đi Liên quan')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('trip.trip_code')
                                ->label('Mã chuyến đi')
                                ->getStateUsing(fn ($record) => $record->trip?->trip_code ?: ($record->trip_id ? ('#' . $record->trip_id) : 'Không có chuyến đi liên quan (Giao dịch ví)'))
                                ->placeholder('Không có chuyến đi liên quan (Giao dịch ví)')
                                ->weight('bold')
                                ->color('primary'),

                            TextEntry::make('trip.car.name')
                                ->label('Phương tiện thuê')
                                ->placeholder('—'),

                            TextEntry::make('trip.car.license_plate')
                                ->label('Biển số xe')
                                ->placeholder('—')
                                ->badge()
                                ->color('gray'),
                        ]),
                    ])
                    ->visible(fn ($record) => $record->trip_id != null),
            ]);
    }
}
