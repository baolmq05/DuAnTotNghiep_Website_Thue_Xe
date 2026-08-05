<?php

namespace App\Filament\Resources\SystemSettings;

use App\Filament\Resources\SystemSettings\Pages\CreateSystemSetting;
use App\Filament\Resources\SystemSettings\Pages\EditSystemSetting;
use App\Filament\Resources\SystemSettings\Pages\ListSystemSettings;
use App\Filament\Resources\SystemSettings\Schemas\SystemSettingForm;
use App\Filament\Resources\SystemSettings\Tables\SystemSettingsTable;
use App\Models\SystemSetting;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SystemSettingResource extends Resource
{
    protected static ?string $model = SystemSetting::class;
    protected static ?string $recordTitleAttribute = 'key';
    protected static ?string $navigationLabel = 'Cài đặt hệ thống';
    protected static ?string $modelLabel = 'Cài đặt';
    protected static ?string $pluralModelLabel = 'Cài đặt hệ thống';
    protected static \UnitEnum|string|null $navigationGroup = 'Cấu hình Hệ thống';

    public static function form(Schema $schema): Schema
    {
        return SystemSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SystemSettingsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSystemSettings::route('/'),
            'create' => CreateSystemSetting::route('/create'),
            'edit'   => EditSystemSetting::route('/{record}/edit'),
        ];
    }
}
