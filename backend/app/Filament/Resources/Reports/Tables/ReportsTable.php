<?php

namespace App\Filament\Resources\Reports\Tables;

use App\Enum\ReportStatus;
use App\Enum\ReportType;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

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

                TextColumn::make('trip.id')
                    ->label('Mã chuyến đi')
                    ->formatStateUsing(fn ($state) => '#' . $state)
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
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Xem chi tiết'),

                Action::make('resolve')
                    ->label('Giải quyết')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('admin_note')
                            ->label('Ghi chú xử lý của Admin')
                            ->rows(3)
                            ->placeholder('Nhập kết quả xử lý báo cáo/khiếu nại...')
                            ->required(),
                    ])
                    ->action(function (Report $record, array $data): void {
                        $record->update([
                            'status' => ReportStatus::Resolved,
                            'admin_note' => $data['admin_note'],
                            'resolved_at' => now(),
                            'resolved_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Báo cáo đã được đánh dấu là Đã giải quyết')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Report $record): bool => $record->status === ReportStatus::Pending),

                Action::make('reject')
                    ->label('Từ chối')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('admin_note')
                            ->label('Lý do từ chối')
                            ->rows(3)
                            ->placeholder('Nhập lý do từ chối báo cáo...')
                            ->required(),
                    ])
                    ->action(function (Report $record, array $data): void {
                        $record->update([
                            'status' => ReportStatus::Rejected,
                            'admin_note' => $data['admin_note'],
                            'resolved_at' => now(),
                            'resolved_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Báo cáo đã bị từ chối')
                            ->danger()
                            ->send();
                    })
                    ->visible(fn (Report $record): bool => $record->status === ReportStatus::Pending),
            ])
            ->recordUrl(
                fn (Report $record): string => ReportResource::getUrl('view', ['record' => $record])
            );
    }
}
