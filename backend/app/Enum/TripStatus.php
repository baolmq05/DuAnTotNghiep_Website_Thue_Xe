<?php

namespace App\Enum;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum TripStatus: int implements HasLabel, HasColor, HasIcon
{
    case Pending = 0;
    case WaitingPayment = 1;
    case Confirmed = 2;
    case Ongoing = 3;
    case Complete = 4;
    case UserCancel = 5;
    case OwnerCancel = 6;
    case WaitingExtension = 7;
    case WaitingReturn = 8;
    case Disputed = 9;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Pending => 'Chờ duyệt',
            self::WaitingPayment => 'Chờ thanh toán',
            self::Confirmed => 'Đã xác nhận',
            self::Ongoing => 'Đang diễn ra',
            self::Complete => 'Đã hoàn thành',
            self::UserCancel => 'Người dùng hủy',
            self::OwnerCancel => 'Chủ xe hủy',
            self::WaitingExtension => 'Chờ gia hạn',
            self::WaitingReturn => 'Chờ trả xe',
            self::Disputed => 'Đang tranh chấp',
        };
    }

    public function label(): string
    {
        return (string) $this->getLabel();
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::WaitingPayment => 'info',
            self::Confirmed => 'gray',
            self::Ongoing => 'primary',
            self::Complete => 'success',
            self::UserCancel, self::OwnerCancel => 'danger',
            self::WaitingExtension => 'info',
            self::WaitingReturn => 'warning',
            self::Disputed => 'danger',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::WaitingPayment => 'heroicon-o-credit-card',
            self::Confirmed => 'heroicon-o-check',
            self::Ongoing => 'heroicon-o-play',
            self::Complete => 'heroicon-o-check-circle',
            self::UserCancel, self::OwnerCancel => 'heroicon-o-x-circle',
            self::WaitingExtension => 'heroicon-o-arrow-path',
            self::WaitingReturn => 'heroicon-o-arrow-uturn-left',
            self::Disputed => 'heroicon-o-shield-exclamation',
        };
    }
}
