<?php

namespace App\Filament\Resources\Cars;

use App\Filament\Resources\Cars\Pages\CreateCar;
use App\Filament\Resources\Cars\Pages\EditCar;
use App\Filament\Resources\Cars\Pages\ListCars;
use App\Filament\Resources\Cars\Pages\ViewCar;
use App\Filament\Resources\Cars\Schemas\CarForm;
use App\Filament\Resources\Cars\Schemas\CarInfolist;
use App\Filament\Resources\Cars\Tables\CarsTable;
use App\Models\Car;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;
    protected static ?string $navigationLabel = 'Quản lý xe';
    protected static ?string $modelLabel = 'xe';
    protected static ?string $pluralModelLabel = 'Quản lý xe';
    protected static ?string $slug = 'quan-ly-xe';
    protected static ?string $recordTitleAttribute = 'name';
    protected static \UnitEnum|string|null $navigationGroup = 'Quản lý Phương tiện';

    public static function form(Schema $schema): Schema
    {
        return CarForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CarInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCars::route('/'),
            'view' => ViewCar::route('/{record}'),
        ];
    }
}