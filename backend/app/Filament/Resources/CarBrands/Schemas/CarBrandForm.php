<?php

namespace App\Filament\Resources\CarBrands\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CarBrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('brand_name')
                    ->required(),
            ]);
    }
}
