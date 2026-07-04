<?php

namespace Database\Seeders;

use App\Enums\TimeSlotStatus;
use App\Models\TimeSlot;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    public function run(): void
    {
        $slots = [
            ['06:00:00', '07:30:00'],
            ['08:00:00', '09:30:00'],
            ['10:00:00', '11:30:00'],
            ['14:00:00', '15:30:00'],
            ['16:00:00', '17:30:00'],
            ['17:30:00', '19:00:00'],
            ['19:30:00', '21:00:00'],
            ['21:00:00', '22:30:00'],
        ];

        foreach ($slots as [$start, $end]) {
            TimeSlot::query()->updateOrCreate(
                ['start_time' => $start, 'end_time' => $end],
                ['status' => TimeSlotStatus::ACTIVE]
            );
        }
    }
}
