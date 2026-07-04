<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ xác nhận',
            self::CONFIRMED => 'Đã xác nhận',
            self::CANCELLED => 'Đã hủy',
            self::COMPLETED => 'Đã hoàn thành',
        };
    }

    public function cssClass(): string
    {
        return match ($this) {
            self::PENDING => 'status-pending',
            self::CONFIRMED => 'status-confirmed',
            self::CANCELLED => 'status-cancelled',
            self::COMPLETED => 'status-completed',
        };
    }
}
