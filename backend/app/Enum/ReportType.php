<?php

namespace App\Enum;

use BackedEnum;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ReportType: int implements HasLabel
{
    case WrongCar = 0;
    case NoShow = 1;
    case Fraud = 2;
    case Other = 3;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::WrongCar => 'Giao sai xe',
            self::NoShow => 'Không đến giao/nhận xe',
            self::Fraud => 'Gian lận',
            self::Other => 'Khác',
        };
    }
}
