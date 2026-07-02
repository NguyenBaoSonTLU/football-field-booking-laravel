<?php

namespace Tests\Unit;

use App\Models\Booking;
use PHPUnit\Framework\TestCase;

class BookingLockKeyTest extends TestCase
{
    public function test_lock_key_is_deterministic(): void
    {
        $this->assertSame('3|2026-07-01|8', Booking::makeLockKey(3, '2026-07-01', 8));
    }
}
