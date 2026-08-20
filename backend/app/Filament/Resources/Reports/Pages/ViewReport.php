<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Enum\ReportStatus;
use App\Filament\Resources\Reports\ReportResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

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
                ->form([
                    Textarea::make('admin_note')
                        ->label('Kết luận & Ghi chú xử lý')
                        ->placeholder('Nhập chi tiết biện pháp giải quyết...')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'status' => ReportStatus::Resolved,
                        'admin_note' => $data['admin_note'],
                        'resolved_at' => now(),
                        'resolved_by' => Auth::id(),
                    ]);

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
                    $this->record->update([
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
                ->visible(fn (): bool => $this->record->status === ReportStatus::Pending),
        ];
    }
}
