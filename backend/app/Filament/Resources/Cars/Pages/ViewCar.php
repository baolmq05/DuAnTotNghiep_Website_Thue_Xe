<?php

namespace App\Filament\Resources\Cars\Pages;

use App\Filament\Resources\Cars\CarResource;
use App\Models\Car;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Textarea;

class ViewCar extends ViewRecord
{
    protected static string $resource = CarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Duyệt xe')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (Car $record) {
                    $record->update(['status' => 1]);
                    $owner = $record->owner;
                    if ($owner && $owner->role_id !== 3 && $owner->role_id !== 1) {
                        $owner->update(['role_id' => 3]);
                    }
                })
                ->visible(fn (Car $record) => $record->status == 2 && !$record->trashed()),

            Action::make('reject')
                ->label('Từ chối')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Textarea::make('rejection_reason')
                        ->label('Lý do từ chối')
                        ->placeholder('Nhập lý do từ chối phê duyệt xe này...')
                        ->required(),
                ])
                ->action(function (Car $record, array $data) {
                    $record->update([
                        'status' => 3,
                        'rejection_reason' => $data['rejection_reason'],
                    ]);
                })
                ->visible(fn (Car $record) => $record->status == 2 && !$record->trashed()),

            Action::make('approveDelete')
                ->label('Duyệt xóa xe')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (Car $record) {
                    // Gửi thông báo cho chủ xe trước khi xóa xe khỏi DB
                    $owner = $record->owner;
                    if ($owner) {
                        \App\Models\Notification::create([
                            'user_id' => $owner->id,
                            'message' => "Yêu cầu xóa xe '{$record->name}' (Biển số: {$record->license_plate}) của bạn đã được ban quản trị phê duyệt. Xe của bạn đã được xóa khỏi hệ thống.",
                            'is_read' => '0',
                        ]);
                    }
                    
                    // Tiến hành xóa xe
                    $record->delete();
                })
                ->successRedirectUrl(fn() => CarResource::getUrl('index'))
                ->visible(fn (Car $record) => $record->status == 4 && !$record->trashed()),

            Action::make('rejectDelete')
                ->label('Từ chối yêu cầu xóa')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function (Car $record) {
                    $record->update(['status' => 0]); // Trả về trạng thái dừng hoạt động
                    
                    // Gửi thông báo cho chủ xe
                    $owner = $record->owner;
                    if ($owner) {
                        \App\Models\Notification::create([
                            'user_id' => $owner->id,
                            'message' => "Yêu cầu xóa xe '{$record->name}' (Biển số: {$record->license_plate}) của bạn đã bị từ chối bởi ban quản trị. Xe đã được chuyển về trạng thái dừng hoạt động.",
                            'is_read' => '0',
                        ]);
                    }
                })
                ->visible(fn (Car $record) => $record->status == 4 && !$record->trashed()),
        ];
    }

    protected function resolveRecord(int|string $key): \Illuminate\Database\Eloquent\Model
    {
        $record = static::getResource()::getEloquentQuery()
            ->withTrashed()
            ->find($key);

        if (! $record) {
            throw (new \Illuminate\Database\Eloquent\ModelNotFoundException())->setModel(
                static::getResource()::getModel(),
                [$key]
            );
        }

        return $record;
    }
}
