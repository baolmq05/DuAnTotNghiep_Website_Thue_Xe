<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Enum\PenaltyType;
use App\Enum\ReportStatus;
use App\Filament\Resources\Reports\ReportResource;
use App\Services\ReportService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('resolve_report')
                ->label('Giải quyết')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->form(fn (): array => [
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
                        ->default(function () {
                            $ownerId = $this->record->trip?->car?->user_id;
                            return ReportService::getPenaltyTypeForOwner($ownerId)->value;
                        })
                        ->visible(fn (Get $get): bool => $get('fault_side') == 'owner')
                        ->disabled()
                        ->dehydrated(),
                ])
                ->action(function (array $data): void {
                    $faultSide = $data['fault_side'] ?? 'owner';
                    $penaltyReason = $data['reason'] ?? $data['admin_note'];
                    ReportService::resolveReport($this->record, $data['admin_note'], $penaltyReason, $faultSide);

                    Notification::make()
                        ->title('Báo cáo đã được xử lý và đánh dấu hoàn tất')
                        ->success()
                        ->send();
                })
                ->visible(fn (): bool => $this->record->status == ReportStatus::Pending),

            Action::make('reject_report')
                ->label('Từ chối khiếu nại/báo cáo')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->form([
                    Textarea::make('admin_note')
                        ->label('Lý do từ chối')
                        ->placeholder('Nhập lý do từ chối báo cáo...')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    ReportService::rejectReport($this->record, $data['admin_note']);

                    Notification::make()
                        ->title('Báo cáo đã bị từ chối và email thông báo đã được gửi')
                        ->danger()
                        ->send();
                })
                ->visible(fn (): bool => $this->record->status == ReportStatus::Pending),
        ];
    }
}
