<?php

namespace App\Enum;

use BackedEnum;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Illuminate\Contracts\Support\Htmlable;

enum ReportStatus: int implements HasLabel, HasColor, HasIcon
{
    case Pending = 0;
    case Resolved = 1;
    case Rejected = 2;
    case Cancelled = 3;
    case Expired = 4;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Pending => 'Chờ xử lý',
            self::Resolved => 'Đã giải quyết',
            self::Rejected => 'Từ chối',
            self::Cancelled => 'Thu hồi',
            self::Expired => 'Hết hạn (Timeout)',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Resolved => 'success',
            self::Rejected => 'danger',
            self::Cancelled => 'gray',
            self::Expired => 'warning',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Resolved => 'heroicon-o-check-circle',
            self::Rejected => 'heroicon-o-x-circle',
            self::Cancelled => 'heroicon-o-minus-circle',
            self::Expired => 'heroicon-o-clock',
        };
    }
}
