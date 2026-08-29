<?php

namespace App\Filament\Resources\Reports\Tables;

use App\Enum\PenaltyType;
use App\Enum\ReportStatus;
use App\Enum\ReportType;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use App\Services\ReportService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
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
                        ReportStatus::Cancelled->value => 'Thu hồi',
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
                    ->form(fn (Report $record): array => [
                        Select::make('fault_side')
                            ->label('1. Kết luận bên có lỗi')
                            ->options([
                                'owner' => 'Lỗi từ phía Chủ xe (Hủy miễn phí, hoàn 100% tiền cho khách & phạt chủ xe)',
                                'renter' => 'Lỗi từ phía Khách thuê (Hủy chuyến, trừ tiền cọc & đền bù cho chủ xe)',
                            ])
                            ->default('owner')
                            ->required()
                            ->helperText('Hệ thống sẽ tự động hủy chuyến và điều chuyển tiền tương ứng.'),

                        Textarea::make('admin_note')
                            ->label('2. Ghi chú xử lý của Admin (Hiển thị trong kết quả)')
                            ->rows(3)
                            ->placeholder('Nhập chi tiết biện pháp và công việc Admin đã xử lý...')
                            ->required(),

                        Textarea::make('reason')
                            ->label('3. Lý do vi phạm (Lưu thông tin phạt chủ xe)')
                            ->rows(3)
                            ->placeholder('Nhập lý do vi phạm để ghi nhận phạt chủ xe...')
                            ->visible(fn (Get $get): bool => $get('fault_side') == 'owner')
                            ->required(fn (Get $get): bool => $get('fault_side') == 'owner'),

                        Select::make('penalty_type')
                            ->label('4. Hình thức xử phạt chủ xe (Tự động theo strike 90 ngày)')
                            ->options(array_combine(
                                array_map(fn ($case) => $case->value, PenaltyType::cases()),
                                array_map(fn ($case) => $case->getLabel(), PenaltyType::cases())
                            ))
                            ->default(function () use ($record) {
                                $ownerId = $record->trip?->car?->user_id;
                                return ReportService::getPenaltyTypeForOwner($ownerId)->value;
                            })
                            ->visible(fn (Get $get): bool => $get('fault_side') == 'owner')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->action(function (Report $record, array $data): void {
                        $faultSide = $data['fault_side'] ?? 'owner';
                        $penaltyReason = $data['reason'] ?? $data['admin_note'];
                        ReportService::resolveReport($record, $data['admin_note'], $penaltyReason, $faultSide);

                        Notification::make()
                            ->title('Báo cáo đã được đánh dấu là Đã giải quyết')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Report $record): bool => $record->status == ReportStatus::Pending),

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
                        ReportService::rejectReport($record, $data['admin_note']);

                        Notification::make()
                            ->title('Báo cáo đã bị từ chối và email thông báo đã được gửi')
                            ->danger()
                            ->send();
                    })
                    ->visible(fn (Report $record): bool => $record->status == ReportStatus::Pending),
            ])
            ->recordUrl(
                fn (Report $record): string => ReportResource::getUrl('view', ['record' => $record])
            );
    }
}
