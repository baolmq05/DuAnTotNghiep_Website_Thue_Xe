<?php

namespace App\Filament\Resources\DrivingLicenses\Pages;

use App\Filament\Resources\DrivingLicenses\DrivingLicenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDrivingLicenses extends ListRecords
{
    protected static string $resource = DrivingLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
