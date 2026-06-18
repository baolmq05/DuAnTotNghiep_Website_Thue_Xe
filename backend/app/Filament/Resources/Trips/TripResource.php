<?php

namespace App\Filament\Resources\Trips;

use App\Filament\Resources\Trips\Pages\EditTrip;
use App\Filament\Resources\Trips\Pages\ListTrips;
use App\Filament\Resources\Trips\Schemas\TripForm;
use App\Filament\Resources\Trips\Tables\TripsTable;
use App\Models\Trip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;
    protected static ?string $navigationLabel = 'Chuyến đi';
    protected static ?string $modelLabel = 'Chuyến đi';
    protected static ?string $pluralModelLabel = 'Chuyến đi';
    protected static ?string $recordTitleAttribute = 'id';
    protected static \UnitEnum|string|null $navigationGroup = 'Quản lý Vận hành';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return TripForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TripsTable::configure($table);
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
            'index' => ListTrips::route('/'),
            'edit' => EditTrip::route('/{record}/edit'),
        ];
    }
}
