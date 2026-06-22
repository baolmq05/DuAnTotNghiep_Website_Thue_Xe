<?php

namespace App\Filament\Resources\DrivingLicenses\Pages;

use App\Filament\Resources\DrivingLicenses\DrivingLicenseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDrivingLicense extends ViewRecord
{
    protected static string $resource = DrivingLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
