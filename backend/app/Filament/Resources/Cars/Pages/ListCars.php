<?php

namespace App\Filament\Resources\Cars\Pages;

use App\Filament\Resources\Cars\CarResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCars extends ListRecords
{
    protected static string $resource = CarResource::class;

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
            'pending' => Tab::make('Chờ duyệt')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 2))
                ->badge(static::getResource()::getModel()::query()->where('status', 2)->count())
                ->badgeColor('warning'),
            'approved' => Tab::make('Đã duyệt')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 1))
                ->badge(static::getResource()::getModel()::query()->where('status', 1)->count())
                ->badgeColor('success'),
            'rejected' => Tab::make('Bị từ chối')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 3))
                ->badge(static::getResource()::getModel()::query()->where('status', 3)->count())
                ->badgeColor('danger'),
            'inactive' => Tab::make('Dừng hoạt động')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 0))
                ->badge(static::getResource()::getModel()::query()->where('status', 0)->count())
                ->badgeColor('gray'),
        ];
    }
} 