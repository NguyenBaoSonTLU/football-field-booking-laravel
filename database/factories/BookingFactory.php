<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\FootballField;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(BookingStatus::cases());
        $field = FootballField::factory()->create();
        $slot = TimeSlot::query()->inRandomOrder()->first();
        $date = fake()->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d');

        return [
            'user_id' => User::factory(),
            'football_field_id' => $field->id,
            'time_slot_id' => $slot?->id ?? TimeSlot::factory(),
            'booking_date' => $date,
            'total_price' => 675000,
            'status' => $status,
            'note' => fake()->optional()->sentence(),
            'slot_lock_key' => in_array($status, [BookingStatus::PENDING, BookingStatus::CONFIRMED], true)
                ? $field->id.'|'.$date.'|'.$slot?->id
                : null,
        ];
    }
}
