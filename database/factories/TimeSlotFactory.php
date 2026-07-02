<?php

namespace Database\Factories;

use App\Enums\TimeSlotStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeSlotFactory extends Factory
{
    public function definition(): array
    {
        return [
            'start_time' => '06:00:00',
            'end_time' => '07:30:00',
            'status' => TimeSlotStatus::ACTIVE,
        ];
    }
}
