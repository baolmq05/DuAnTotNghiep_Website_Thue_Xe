<?php

namespace App\Filament\Resources\CarDeleteRequests\Pages;

use App\Filament\Resources\CarDeleteRequests\CarDeleteRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCarDeleteRequest extends EditRecord
{
    protected static string $resource = CarDeleteRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
