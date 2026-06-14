<?php

namespace App\Filament\Resources\CarBrands;

use App\Filament\Resources\CarBrands\Pages\CreateCarBrand;
use App\Filament\Resources\CarBrands\Pages\EditCarBrand;
use App\Filament\Resources\CarBrands\Pages\ListCarBrands;
use App\Filament\Resources\CarBrands\Pages\ViewCarBrand;
use App\Filament\Resources\CarBrands\Schemas\CarBrandForm;
use App\Filament\Resources\CarBrands\Schemas\CarBrandInfolist;
use App\Filament\Resources\CarBrands\Tables\CarBrandsTable;
use App\Models\CarBrand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\CarBrands\RelationManagers\CarTypesRelationManager;
class CarBrandResource extends Resource
{
    protected static ?string $model = CarBrand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'brand_name';

    public static function form(Schema $schema): Schema
    {
        return CarBrandForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CarBrandInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarBrandsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CarTypesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCarBrands::route('/'),
            'create' => CreateCarBrand::route('/create'),
            'view' => ViewCarBrand::route('/{record}'),
            'edit' => EditCarBrand::route('/{record}/edit'),

        ];
    }
}
