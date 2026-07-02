<?php

namespace App\Enums;

enum TimeSlotStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Đang sử dụng',
            self::INACTIVE => 'Tạm ngừng',
        };
    }
}
