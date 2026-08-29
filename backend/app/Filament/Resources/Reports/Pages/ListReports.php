<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Enum\ReportStatus;
use App\Filament\Resources\Reports\ReportResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

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
            'pending' => Tab::make('Chờ xử lý')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ReportStatus::Pending->value))
                ->badge(static::getResource()::getModel()::query()->where('status', ReportStatus::Pending->value)->count())
                ->badgeColor('warning'),
            'resolved' => Tab::make('Đã giải quyết')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ReportStatus::Resolved->value))
                ->badge(static::getResource()::getModel()::query()->where('status', ReportStatus::Resolved->value)->count())
                ->badgeColor('success'),
            'rejected' => Tab::make('Từ chối')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ReportStatus::Rejected->value))
                ->badge(static::getResource()::getModel()::query()->where('status', ReportStatus::Rejected->value)->count())
                ->badgeColor('danger'),
            'cancelled' => Tab::make('Thu hồi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ReportStatus::Cancelled->value))
                ->badge(static::getResource()::getModel()::query()->where('status', ReportStatus::Cancelled->value)->count())
                ->badgeColor('gray'),
            'expired' => Tab::make('Hết hạn')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ReportStatus::Expired->value))
                ->badge(static::getResource()::getModel()::query()->where('status', ReportStatus::Expired->value)->count())
                ->badgeColor('warning'),
        ];
    }
}
