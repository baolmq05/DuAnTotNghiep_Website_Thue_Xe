<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Tất cả')
                ->badge(static::getResource()::getModel()::query()->count()),

            'trips' => Tab::make('Thuê xe (Chuyến đi)')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('trip_id'))
                ->badge(static::getResource()::getModel()::query()->whereNotNull('trip_id')->count())
                ->badgeColor('success'),

            'withdrawals' => Tab::make('Rút tiền')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('trip_id'))
                ->badge(static::getResource()::getModel()::query()->whereNull('trip_id')->count())
                ->badgeColor('danger'),
        ];
    }
}
