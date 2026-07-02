<?php

namespace App\Filament\Resources\Trips\Pages;

use App\Filament\Resources\Trips\TripResource;
use App\Enum\TripStatus;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTrips extends ListRecords
{
    protected static string $resource = TripResource::class;

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
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', TripStatus::Pending->value))
                ->badge(static::getResource()::getModel()::query()->where('status', TripStatus::Pending->value)->count())
                ->badgeColor('warning'),
            'waiting_payment' => Tab::make('Chờ thanh toán')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', TripStatus::WaitingPayment->value))
                ->badge(static::getResource()::getModel()::query()->where('status', TripStatus::WaitingPayment->value)->count())
                ->badgeColor('info'),
            'confirmed' => Tab::make('Đã xác nhận')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', TripStatus::Confirmed->value))
                ->badge(static::getResource()::getModel()::query()->where('status', TripStatus::Confirmed->value)->count())
                ->badgeColor('gray'),
            'ongoing' => Tab::make('Đang diễn ra')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', TripStatus::Ongoing->value))
                ->badge(static::getResource()::getModel()::query()->where('status', TripStatus::Ongoing->value)->count())
                ->badgeColor('primary'),
            'complete' => Tab::make('Đã hoàn thành')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', TripStatus::Complete->value))
                ->badge(static::getResource()::getModel()::query()->where('status', TripStatus::Complete->value)->count())
                ->badgeColor('success'),
            'cancelled' => Tab::make('Đã hủy')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [TripStatus::UserCancel->value, TripStatus::OwnerCancel->value]))
                ->badge(static::getResource()::getModel()::query()->whereIn('status', [TripStatus::UserCancel->value, TripStatus::OwnerCancel->value])->count())
                ->badgeColor('danger'),
        ];
    }
}
