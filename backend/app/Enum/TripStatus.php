<?php

namespace App\Enum;

enum TripStatus: int
{
    case Pending = 0;
    case WaitingPayment = 1;
    case Confirmed = 2;
    case Ongoing = 3;
    case Complete = 4;
    case UserCancel = 5;
    case OwnerCancel = 6;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Chờ duyệt',
            self::WaitingPayment => 'Chờ thanh toán',
            self::Confirmed => 'Đã xác nhận',
            self::Ongoing => 'Đang diễn ra',
            self::Complete => 'Đã hoàn thành',
            self::UserCancel => 'Người dùng hủy',
            self::OwnerCancel => 'Chủ xe hủy',
        };
    }
}
