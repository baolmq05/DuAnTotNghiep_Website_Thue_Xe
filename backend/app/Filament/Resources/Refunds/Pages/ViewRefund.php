<?php

namespace App\Filament\Resources\Refunds\Pages;

use App\Filament\Resources\Refunds\RefundResource;
use App\Enum\RefundStatus;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class ViewRefund extends ViewRecord
{
    protected static string $resource = RefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('complete_transfer')
                ->label('Xác nhận đã chuyển tiền')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->form([
                    TextInput::make('transaction_id')
                        ->label('Mã giao dịch ngân hàng')
                        ->placeholder('Ví dụ: FT123456...')
                        ->required(),
                    Textarea::make('description')
                        ->label('Ghi chú của Admin')
                        ->rows(2)
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => RefundStatus::Completed,
                        'transaction_id' => $data['transaction_id'],
                        'description' => $data['description'] ?: $this->record->description,
                    ]);

                    Notification::make()
                        ->title('Đã xác nhận hoàn thành rút tiền')
                        ->success()
                        ->send();
                })
                ->visible(fn () => in_array($this->record->status, [RefundStatus::Pending, RefundStatus::Processing])),

            Action::make('reject_transfer')
                ->label('Từ chối / Hủy yêu cầu')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->form([
                    Textarea::make('description')
                        ->label('Lý do từ chối (Không bắt buộc)')
                        ->rows(2)
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => RefundStatus::Canceled,
                        'description' => $data['description'] ?: 'Yêu cầu bị từ chối bởi Admin.',
                    ]);

                    Notification::make()
                        ->title('Đã hủy yêu cầu rút tiền')
                        ->danger()
                        ->send();
                })
                ->visible(fn () => in_array($this->record->status, [RefundStatus::Pending, RefundStatus::Processing])),
        ];
    }
}
