<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Enum\PenaltyType;
use App\Enum\ReportStatus;
use App\Filament\Resources\Reports\ReportResource;
use App\Services\ReportService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                    Textarea::make('admin_note')
                        ->label('1. Ghi chú xử lý của Admin')
                        ->rows(3)
                        ->placeholder('Nhập chi tiết biện pháp và công việc Admin đã xử lý...')
                        ->required(),

                    Textarea::make('reason')
                        ->label('2. Lý do vi phạm (Lưu thông tin phạt chủ xe)')
                        ->rows(3)
                        ->placeholder('Nhập lý do vi phạm để ghi nhận phạt chủ xe...')
                        ->required(),

                    Select::make('penalty_type')
                        ->label('3. Hình thức xử phạt (Tự động xác định theo số vi phạm trong 90 ngày)')
                        ->options(array_combine(
                            array_map(fn ($case) => $case->value, PenaltyType::cases()),
                            array_map(fn ($case) => $case->getLabel(), PenaltyType::cases())
                        ))
                        ->default(function () {
                            $ownerId = $this->record->trip?->car?->user_id;
                            return ReportService::getPenaltyTypeForOwner($ownerId)->value;
                        })
                        ->disabled()
                        ->dehydrated(),
                ])
                ->action(function (array $data): void {
                    ReportService::resolveReport($this->record, $data['admin_note'], $data['reason']);

                    Notification::make()
                        ->title('Báo cáo đã được xử lý và đánh dấu hoàn tất')
                        ->success()
                        ->send();
                })
                ->visible(fn (): bool => $this->record->status === ReportStatus::Pending),

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
                ->visible(fn (): bool => $this->record->status === ReportStatus::Pending),
        ];
    }
}
