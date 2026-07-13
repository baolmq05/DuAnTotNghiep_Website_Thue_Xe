<?php

namespace App\Filament\Resources\DrivingLicenses\Pages;

use App\Filament\Resources\DrivingLicenses\DrivingLicenseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDrivingLicense extends EditRecord
{
    protected static string $resource = DrivingLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),
        ];
    }
}
