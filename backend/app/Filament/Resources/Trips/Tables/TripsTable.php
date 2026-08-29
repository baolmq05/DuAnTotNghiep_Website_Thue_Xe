<?php

namespace App\Filament\Resources\Trips\Tables;

use App\Enum\TripStatus;
use App\Filament\Resources\Trips\TripResource;
use App\Models\Trip;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class TripsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('car.name')
                    ->label('Xe thuê')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('car.owner.name')
                    ->label('Chủ xe')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Khách hàng')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('trip_code')
                    ->label('Mã chuyến đi')
                    ->getStateUsing(fn ($record) => $record->trip_code ?: ('#' . $record->id))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cost')
                    ->label('Chi phí')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') . ' VND')
                    ->sortable(),
                TextColumn::make('trip_type')
                    ->label('Loại thuê')
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Thuê theo km' : 'Thuê theo ngày')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->sortable(),
                TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->dateTime()
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
                SelectFilter::make('car_id')
                    ->label('Xe thuê')
                    ->relationship('car', 'name'),
                SelectFilter::make('user_id')
                    ->label('Khách hàng')
                    ->relationship('user', 'name'),
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        TripStatus::Pending->value => 'Chờ duyệt',
                        TripStatus::WaitingPayment->value => 'Chờ thanh toán',
                        TripStatus::Confirmed->value => 'Đã xác nhận',
                        TripStatus::Ongoing->value => 'Đang diễn ra',
                        TripStatus::Complete->value => 'Đã hoàn thành',
                        TripStatus::UserCancel->value => 'Người dùng hủy',
                        TripStatus::OwnerCancel->value => 'Chủ xe hủy',
                        TripStatus::WaitingExtension->value => 'Chờ gia hạn',
                        TripStatus::WaitingReturn->value => 'Chờ trả xe',
                        TripStatus::Disputed->value => 'Đang tranh chấp',
                    ]),
                SelectFilter::make('trip_type')
                    ->label('Loại thuê')
                    ->options([
                        0 => 'Thuê theo ngày',
                        1 => 'Thuê theo km',
                    ]),
                Filter::make('trip_duration')
                    ->label('Thời gian chuyến đi')
                    ->form([
                        DatePicker::make('start_at')
                            ->label('Ngày bắt đầu'),
                        DatePicker::make('end_at')
                            ->label('Ngày kết thúc'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['start_at'], fn($q) => $q->whereDate('start_at', '>=', $data['start_at']))
                            ->when($data['end_at'], fn($q) => $q->whereDate('end_at', '<=', $data['end_at']));
                    }),
            ])
            ->recordActions([
                Action::make('view_trip')
                    ->label('Xem chi tiết')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Trip $record): string => TripResource::getUrl('view', ['record' => $record])),
            ])
            ->recordUrl(
                fn (Trip $record): string => TripResource::getUrl('view', ['record' => $record])
            )
            ->toolbarActions([
                //
            ]);
    }
}
