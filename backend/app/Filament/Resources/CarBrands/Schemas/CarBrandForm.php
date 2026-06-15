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
                    ->label('Tên thương hiệu')
                    ->required()
                    // Kiểm tra trùng lặp
                    ->unique(table: 'car_brands', column: 'brand_name', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Thương hiệu này đã tồn tại trong hệ thống!',
                    ]),
            ]);
    }
}
