<?php

namespace App\Enum;

use BackedEnum;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Illuminate\Contracts\Support\Htmlable;

enum RefundStatus: int implements HasLabel, HasColor, HasIcon
{
    case Pending = 0;
    case Processing = 1;
    case Completed = 2;
    case Failed = 3;
    case Canceled = 4;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Pending => 'Chờ xử lý',
            self::Processing => 'Đang xử lý',
            self::Completed => 'Hoàn thành',
            self::Failed => 'Thất bại',
            self::Canceled => 'Đã hủy',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Processing => 'info',
            self::Completed => 'success',
            self::Failed, self::Canceled => 'danger',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Processing => 'heroicon-o-arrow-path',
            self::Completed => 'heroicon-o-check-circle',
            self::Failed => 'heroicon-o-x-circle',
            self::Canceled => 'heroicon-o-minus-circle',
        };
    }
}
