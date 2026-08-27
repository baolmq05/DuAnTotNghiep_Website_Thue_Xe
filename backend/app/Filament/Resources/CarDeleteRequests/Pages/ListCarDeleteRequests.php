<?php

namespace App\Filament\Resources\CarDeleteRequests\Pages;

use App\Filament\Resources\CarDeleteRequests\CarDeleteRequestResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCarDeleteRequests extends ListRecords
{
    protected static string $resource = CarDeleteRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Tất cả')
                ->badge(static::getResource()::getModel()::query()->withTrashed()->where(function (Builder $query) {
                    $query->where('status', 4)->orWhereNotNull('deleted_at');
                })->count()),
            'pending' => Tab::make('Chờ duyệt xóa')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('deleted_at')->where('status', 4))
                ->badge(static::getResource()::getModel()::query()->whereNull('deleted_at')->where('status', 4)->count())
                ->badgeColor('danger'),
            'deleted' => Tab::make('Đã duyệt xóa (Lịch sử)')
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())
                ->badge(static::getResource()::getModel()::query()->onlyTrashed()->count())
                ->badgeColor('success'),
        ];
    }
}
