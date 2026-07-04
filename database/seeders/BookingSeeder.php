<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\FootballField;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->where('role', 'customer')->get();
        $fields = FootballField::query()->get();
        $slots = TimeSlot::query()->orderBy('start_time')->get();

        if ($users->isEmpty() || $fields->isEmpty() || $slots->isEmpty()) {
            return;
        }

        $records = [
            [1, 0, 5, 2, BookingStatus::CONFIRMED],
            [2, 1, 7, 4, BookingStatus::PENDING],
            [3, 2, -5, 3, BookingStatus::COMPLETED],
            [4, 3, -12, 1, BookingStatus::CANCELLED],
            [0, 4, 10, 6, BookingStatus::CONFIRMED],
        ];

        foreach ($records as [$userIndex, $fieldIndex, $dayOffset, $slotIndex, $status]) {
            $user = $users[$userIndex % $users->count()];
            $field = $fields[$fieldIndex % $fields->count()];
            $slot = $slots[$slotIndex % $slots->count()];
            $date = today()->addDays($dayOffset)->format('Y-m-d');
            $price = round((float) $field->price_per_hour * ($slot->duration_minutes / 60));
            $active = in_array($status, [BookingStatus::PENDING, BookingStatus::CONFIRMED], true);

            Booking::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'football_field_id' => $field->id,
                    'time_slot_id' => $slot->id,
                    'booking_date' => $date,
                ],
                [
                    'total_price' => $price,
                    'status' => $status,
                    'note' => 'Dữ liệu mẫu phục vụ demo đồ án.',
                    'slot_lock_key' => $active ? Booking::makeLockKey($field->id, $date, $slot->id) : null,
                ]
            );
        }
    }
}
