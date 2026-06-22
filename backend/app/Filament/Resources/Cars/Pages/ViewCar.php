<?php

namespace App\Filament\Resources\Cars\Pages;

use App\Filament\Resources\Cars\CarResource;
use App\Models\Car;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

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
                ->visible(fn (Car $record) => $record->status == 2),

            Action::make('reject')
                ->label('Từ chối')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (Car $record) {
                    $record->update(['status' => 3]);
                })
                ->visible(fn (Car $record) => $record->status == 2),
        ];
    }
}
