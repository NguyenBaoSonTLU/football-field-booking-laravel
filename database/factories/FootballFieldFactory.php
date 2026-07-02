<?php

namespace Database\Factories;

use App\Enums\FootballFieldStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class FootballFieldFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Sân bóng '.fake()->unique()->company(),
            'address' => fake()->address(),
            'description' => fake()->paragraphs(2, true),
            'amenities' => ['Đèn chiếu sáng', 'Bãi đỗ xe', 'Phòng thay đồ'],
            'price_per_hour' => fake()->randomElement([350000, 400000, 450000, 500000, 600000]),
            'open_time' => '06:00:00',
            'close_time' => '23:00:00',
            'status' => FootballFieldStatus::ACTIVE,
        ];
    }
}
