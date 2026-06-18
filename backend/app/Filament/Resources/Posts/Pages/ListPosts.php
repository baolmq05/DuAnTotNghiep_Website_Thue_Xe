<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Enum\PostStatus;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'published' => Tab::make('Công khai')
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->where('status', PostStatus::Active))
                ->badge(static::getResource()::getModel()::query()->withoutTrashed()->where('status', PostStatus::Active)->count()),
            'draft' => Tab::make('Bản nháp')
                ->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->where('status', PostStatus::Inactive))
                ->badge(static::getResource()::getModel()::query()->withoutTrashed()->where('status', PostStatus::Inactive)->count()),
            'deleted' => Tab::make('Thùng rác')
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())
                ->badge(static::getResource()::getModel()::query()->onlyTrashed()->count()),
        ];
    }
}
