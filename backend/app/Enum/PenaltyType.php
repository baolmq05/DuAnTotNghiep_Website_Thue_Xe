<?php

namespace App\Enum;

use BackedEnum;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Illuminate\Contracts\Support\Htmlable;

enum PenaltyType: int implements HasLabel, HasColor, HasIcon
{
    case Warning = 0;
    case CarSuspension = 1;
    case AccountSuspension = 2;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Warning => 'Cảnh cáo (Warning)',
            self::CarSuspension => 'Khóa xe (Car Suspension)',
            self::AccountSuspension => 'Khóa tài khoản (Account Suspension)',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::Warning => 'warning',
            self::CarSuspension => 'orange',
            self::AccountSuspension => 'danger',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Warning => 'heroicon-o-exclamation-triangle',
            self::CarSuspension => 'heroicon-o-no-symbol',
            self::AccountSuspension => 'heroicon-o-x-circle',
        };
    }
}
