<?php

namespace App\Filament\Resources\DrivingLicenses;

use App\Filament\Resources\DrivingLicenses\Pages\CreateDrivingLicense;
use App\Filament\Resources\DrivingLicenses\Pages\EditDrivingLicense;
use App\Filament\Resources\DrivingLicenses\Pages\ListDrivingLicenses;
use App\Filament\Resources\DrivingLicenses\Pages\ViewDrivingLicense;
use App\Filament\Resources\DrivingLicenses\Schemas\DrivingLicenseForm;
use App\Filament\Resources\DrivingLicenses\Schemas\DrivingLicenseInfolist;
use App\Filament\Resources\DrivingLicenses\Tables\DrivingLicensesTable;
use App\Models\DrivingLicense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DrivingLicenseResource extends Resource
{
    protected static ?string $model = DrivingLicense::class;

    protected static ?string $navigationLabel = 'Quản lý giấy phép lái xe';
    protected static ?string $modelLabel = 'Giấy phép lái xe';
    protected static ?string $pluralModelLabel = 'Giấy phép lái xe';
    protected static ?string $recordTitleAttribute = 'name';
    protected static \UnitEnum|string|null $navigationGroup = 'Quản lý Người dùng';

    public static function form(Schema $schema): Schema
    {
        return DrivingLicenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DrivingLicensesTable::configure($table);
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
            'index' => ListDrivingLicenses::route('/'),
            'create' => CreateDrivingLicense::route('/create'),
            'view' => ViewDrivingLicense::route('/{record}'),
            'edit' => EditDrivingLicense::route('/{record}/edit'),
        ];
    }
}
