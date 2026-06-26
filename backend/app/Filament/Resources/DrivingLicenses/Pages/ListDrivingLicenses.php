<?php

namespace App\Filament\Resources\DrivingLicenses\Pages;

use App\Filament\Resources\DrivingLicenses\DrivingLicenseResource;
// use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDrivingLicenses extends ListRecords
{
    protected static string $resource = DrivingLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Tất cả')
                ->badge(static::getResource()::getModel()::query()->count()),
            0 => Tab::make('Chờ duyệt')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 0))
                ->badge(static::getResource()::getModel()::query()->where('status', 0)->count())
                ->badgeColor('warning'),
            1  => Tab::make('Đã duyệt')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 1))
                ->badge(static::getResource()::getModel()::query()->where('status', 1)->count())
                ->badgeColor('success'),
            2 => Tab::make('Từ chối')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 2))
                ->badge(static::getResource()::getModel()::query()->where('status', 2)->count())
                ->badgeColor('danger'),
        ];
    }
}
