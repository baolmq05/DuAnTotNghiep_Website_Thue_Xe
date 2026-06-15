<?php
namespace App\Enum;
use BackedEnum;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum PostStatus: int implements HasColor, HasLabel
{
    case Inactive = 0;
    case Active = 1;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Inactive => 'Nháp',
            self::Active => 'Công khai',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Inactive => Heroicon::LockClosed,
            self::Active => Heroicon::Check,
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::Inactive => 'danger',
            self::Active => 'success',
        };
    }
}