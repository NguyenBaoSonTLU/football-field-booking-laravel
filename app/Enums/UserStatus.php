<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case LOCKED = 'locked';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Đang hoạt động',
            self::LOCKED => 'Đã khóa',
        };
    }
}
