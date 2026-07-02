<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\FootballField;
use App\Models\TimeSlot;
use App\Services\AvailabilityService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FootballFieldController extends Controller
{
    public function __construct(private readonly AvailabilityService $availabilityService)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'keyword' => ['nullable', 'string', 'max:255'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'gte:min_price'],
            'booking_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
            'time_slot_id' => ['nullable', 'integer', 'exists:time_slots,id'],
        ]);

        $fields = FootballField::query()
            ->active()
            ->with('mainImage')
            ->search($filters['keyword'] ?? null)
            ->when(isset($filters['min_price']), fn (Builder $query) => $query->where('price_per_hour', '>=', $filters['min_price']))
            ->when(isset($filters['max_price']), fn (Builder $query) => $query->where('price_per_hour', '<=', $filters['max_price']))
            ->when(
                isset($filters['booking_date'], $filters['time_slot_id']),
                function (Builder $query) use ($filters): void {
                    $query->whereDoesntHave('bookings', function (Builder $bookingQuery) use ($filters): void {
                        $bookingQuery->whereDate('booking_date', $filters['booking_date'])
                            ->where('time_slot_id', $filters['time_slot_id'])
                            ->whereIn('status', [BookingStatus::PENDING->value, BookingStatus::CONFIRMED->value]);
                    });
                }
            )
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        $timeSlots = TimeSlot::query()->active()->orderBy('start_time')->get();

        return view('fields.index', compact('fields', 'timeSlots', 'filters'));
    }

    public function show(FootballField $footballField, Request $request): View
    {
        abort_unless($footballField->status->value === 'active', 404);
        $footballField->load(['images', 'mainImage']);

        $date = $request->input('booking_date', today()->format('Y-m-d'));
        $request->merge(['booking_date' => $date]);
        $request->validate(['booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today']]);

        $slots = $this->availabilityService->slotsForDate($footballField, $date);
        $relatedFields = FootballField::query()
            ->active()
            ->where('id', '!=', $footballField->id)
            ->with('mainImage')
            ->limit(4)
            ->get();

        return view('fields.show', compact('footballField', 'date', 'slots', 'relatedFields'));
    }

    public function availability(FootballField $footballField, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $slots = $this->availabilityService->slotsForDate($footballField, $validated['booking_date']);

        return response()->json([
            'data' => $slots->map(fn (TimeSlot $slot): array => [
                'id' => $slot->id,
                'label' => $slot->label,
                'available' => (bool) $slot->getAttribute('available'),
                'price' => (float) $slot->getAttribute('calculated_price'),
                'formatted_price' => number_format((float) $slot->getAttribute('calculated_price'), 0, ',', '.').' ₫',
            ]),
        ]);
    }
}
