<?php

namespace App\Filament\Resources\Refunds;

use App\Filament\Resources\Refunds\Pages\ViewRefund;
use App\Filament\Resources\Refunds\Pages\ListRefunds;
use App\Filament\Resources\Refunds\Schemas\RefundForm;
use App\Filament\Resources\Refunds\Tables\RefundsTable;
use App\Models\Refund;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RefundResource extends Resource
{
    protected static ?string $model = Refund::class;
    protected static ?string $navigationLabel = 'Yêu cầu rút/hoàn tiền';
    protected static ?string $modelLabel = 'yêu cầu rút/hoàn tiền';
    protected static ?string $pluralModelLabel = 'yêu cầu rút/hoàn tiền';
    protected static ?string $recordTitleAttribute = 'id';
    protected static \UnitEnum|string|null $navigationGroup = 'Quản lý Vận hành';

    public static function form(Schema $schema): Schema
    {
        return RefundForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return \App\Filament\Resources\Refunds\Schemas\RefundInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RefundsTable::configure($table);
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
            'index' => ListRefunds::route('/'),
            'view' => ViewRefund::route('/{record}'),
        ];
    }
}
