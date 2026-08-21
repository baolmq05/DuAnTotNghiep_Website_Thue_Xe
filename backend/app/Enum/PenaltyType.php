<?php

namespace App\Enum;

use BackedEnum;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Illuminate\Contracts\Support\Htmlable;

enum PenaltyType: int implements HasLabel, HasColor, HasIcon
{
    case Warning1 = 0;
    case Warning2 = 1;
    case AccountSuspension = 2;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Warning1 => 'Cảnh cáo lần 1',
            self::Warning2 => 'Cảnh cáo lần 2',
            self::AccountSuspension => 'Khóa tài khoản',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::Warning1 => 'warning',
            self::Warning2 => 'orange',
            self::AccountSuspension => 'danger',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Warning1 => 'heroicon-o-exclamation-triangle',
            self::Warning2 => 'heroicon-o-no-symbol',
            self::AccountSuspension => 'heroicon-o-x-circle',
        };
    }
}
