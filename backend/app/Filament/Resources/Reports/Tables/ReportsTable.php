<?php

namespace App\Filament\Resources\Reports\Tables;

use App\Enum\ReportStatus;
use App\Enum\ReportType;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([           
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->limit(35),

                TextColumn::make('report_type')
                    ->label('Loại báo cáo')
                    ->badge()
                    ->sortable(),

                TextColumn::make('reporter.name')
                    ->label('Người báo cáo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('trip.car.owner.name')
                    ->label('Chủ xe (Bị báo cáo)')
                    ->searchable()
                    ->placeholder('N/A')
                    ->sortable(),

                TextColumn::make('trip.trip_code')
                    ->label('Mã chuyến đi')
                    ->getStateUsing(fn ($record) => $record->trip?->trip_code ?: ('#' . $record->trip_id))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->sortable(),

                TextColumn::make('resolver.name')
                    ->label('Người xử lý')
                    ->placeholder('Chưa xử lý')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('resolved_at')
                    ->label('Thời gian xử lý')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('report_type')
                    ->label('Loại báo cáo')
                    ->options([
                        ReportType::WrongCar->value => 'Giao sai xe',
                        ReportType::NoShow->value => 'Không đến giao/nhận xe',
                        ReportType::Fraud->value => 'Gian lận',
                        ReportType::Other->value => 'Khác',
                    ]),

                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        ReportStatus::Pending->value => 'Chờ xử lý',
                        ReportStatus::Resolved->value => 'Đã giải quyết',
                        ReportStatus::Rejected->value => 'Từ chối',
                        ReportStatus::Cancelled->value => 'Thu hồi',
                        ReportStatus::Expired->value => 'Hết hạn',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Xem chi tiết'),
            ])
            ->recordUrl(
                fn (Report $record): string => ReportResource::getUrl('view', ['record' => $record])
            );
    }
}
