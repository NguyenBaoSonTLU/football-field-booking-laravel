<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\CancelBookingRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\FootballField;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService,
        private readonly AvailabilityService $availabilityService
    ) {
    }

    public function create(FootballField $footballField, Request $request): View
    {
        abort_unless($footballField->status->value === 'active', 404);

        $date = $request->input('booking_date', today()->addDay()->format('Y-m-d'));
        $request->merge(['booking_date' => $date]);
        $request->validate(['booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today']]);

        $slots = $this->availabilityService->slotsForDate($footballField, $date);
        $selectedSlotId = $request->integer('time_slot_id') ?: null;

        return view('bookings.create', compact('footballField', 'date', 'slots', 'selectedSlotId'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $booking = $this->bookingService->create($request->user(), $request->validated());

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Đặt sân thành công. Đơn của bạn đang chờ quản trị viên xác nhận.');
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'keyword' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:pending,confirmed,cancelled,completed'],
        ]);

        $bookings = Booking::query()
            ->where('user_id', $request->user()->id)
            ->with(['footballField.mainImage', 'timeSlot'])
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['keyword'] ?? null, function (Builder $query, string $keyword): void {
                $query->whereHas('footballField', fn (Builder $fieldQuery) => $fieldQuery
                    ->where('name', 'like', "%{$keyword}%")
                    ->orWhere('address', 'like', "%{$keyword}%"));
            })
            ->latest('booking_date')
            ->latest('id')
            ->paginate(8)
            ->withQueryString();

        return view('bookings.index', compact('bookings', 'filters'));
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);
        $booking->load(['footballField.images', 'timeSlot', 'user']);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(CancelBookingRequest $request, Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);
        $this->bookingService->cancel($booking, $request->user());

        return back()->with('success', 'Đã hủy lịch đặt sân và giải phóng khung giờ.');
    }

    public function rebook(Booking $booking): RedirectResponse
    {
        $this->authorize('view', $booking);

        return redirect()->route('bookings.create', [
            'footballField' => $booking->football_field_id,
            'booking_date' => today()->addDay()->format('Y-m-d'),
            'time_slot_id' => $booking->time_slot_id,
        ]);
    }
}
