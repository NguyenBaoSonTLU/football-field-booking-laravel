<?php

namespace Tests\Feature;

use App\Enums\FootballFieldStatus;
use App\Enums\TimeSlotStatus;
use App\Models\Booking;
use App\Models\FootballField;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_booking_for_available_slot(): void
    {
        $user = User::factory()->create();
        $field = FootballField::factory()->create();
        $slot = TimeSlot::query()->create([
            'start_time' => '18:00:00',
            'end_time' => '19:30:00',
            'status' => TimeSlotStatus::ACTIVE,
        ]);

        $response = $this->actingAs($user)->post('/dat-san', [
            'football_field_id' => $field->id,
            'time_slot_id' => $slot->id,
            'booking_date' => today()->addDay()->format('Y-m-d'),
            'note' => 'Test booking',
        ]);

        $booking = Booking::query()->first();
        $response->assertRedirect(route('bookings.show', $booking));
        $this->assertNotNull($booking->slot_lock_key);
    }

    public function test_duplicate_active_booking_is_rejected(): void
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();
        $field = FootballField::factory()->create(['status' => FootballFieldStatus::ACTIVE]);
        $slot = TimeSlot::query()->create([
            'start_time' => '18:00:00',
            'end_time' => '19:30:00',
            'status' => TimeSlotStatus::ACTIVE,
        ]);
        $date = today()->addDays(2)->format('Y-m-d');

        $payload = [
            'football_field_id' => $field->id,
            'time_slot_id' => $slot->id,
            'booking_date' => $date,
        ];

        $this->actingAs($firstUser)->post('/dat-san', $payload)->assertSessionHasNoErrors();
        $this->actingAs($secondUser)->from(route('bookings.create', $field))->post('/dat-san', $payload)
            ->assertSessionHasErrors('time_slot_id');

        $this->assertSame(1, Booking::query()->count());
    }
}
