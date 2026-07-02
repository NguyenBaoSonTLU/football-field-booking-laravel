<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\FootballFieldStatus;
use App\Models\Booking;
use App\Models\FootballField;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class AvailabilityService
{
    public function slotsForDate(FootballField $field, string $date): Collection
    {
        $bookingDate = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();

        if ($bookingDate->lt(today())) {
            throw ValidationException::withMessages([
                'booking_date' => 'Ngày đặt sân không được nhỏ hơn ngày hiện tại.',
            ]);
        }

        if ($field->status !== FootballFieldStatus::ACTIVE) {
            throw ValidationException::withMessages([
                'football_field_id' => 'Sân bóng đang ngừng hoạt động.',
            ]);
        }

        $occupiedSlotIds = Booking::query()
            ->where('football_field_id', $field->id)
            ->whereDate('booking_date', $date)
            ->whereIn('status', [BookingStatus::PENDING->value, BookingStatus::CONFIRMED->value])
            ->pluck('time_slot_id');

        return TimeSlot::query()
            ->active()
            ->where('start_time', '>=', $field->open_time)
            ->where('end_time', '<=', $field->close_time)
            ->orderBy('start_time')
            ->get()
            ->map(function (TimeSlot $slot) use ($field, $occupiedSlotIds): TimeSlot {
                $slot->setAttribute('available', ! $occupiedSlotIds->contains($slot->id));
                $slot->setAttribute('calculated_price', $this->calculatePrice($field, $slot));

                return $slot;
            });
    }

    public function calculatePrice(FootballField $field, TimeSlot $slot): float
    {
        return round((float) $field->price_per_hour * ($slot->duration_minutes / 60), 0);
    }
}
