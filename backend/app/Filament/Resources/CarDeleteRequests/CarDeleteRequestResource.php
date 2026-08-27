<?php

namespace App\Filament\Resources\CarDeleteRequests;

use App\Filament\Resources\CarDeleteRequests\Pages\ListCarDeleteRequests;
use App\Filament\Resources\CarDeleteRequests\Schemas\CarDeleteRequestForm;
use App\Filament\Resources\CarDeleteRequests\Tables\CarDeleteRequestsTable;
use App\Models\Car;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CarDeleteRequestResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static string|\BackedEnum|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Yêu cầu xóa xe';

    protected static ?string $modelLabel = 'yêu cầu xóa';

    protected static ?string $pluralModelLabel = 'Yêu cầu xóa xe';

    protected static ?string $slug = 'yeu-cau-xoa-xe';

    protected static \UnitEnum|string|null $navigationGroup = 'Quản lý Phương tiện';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withTrashed()
            ->where(function (Builder $query) {
                $query->where('status', 4)
                    ->orWhereNotNull('deleted_at');
            });
    }

    public static function form(Schema $schema): Schema
    {
        return CarDeleteRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarDeleteRequestsTable::configure($table);
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
            'index' => ListCarDeleteRequests::route('/'),
        ];
    }
}
