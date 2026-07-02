<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingStatusRequest;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private readonly BookingService $bookingService)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'keyword' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:pending,confirmed,cancelled,completed'],
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $bookings = Booking::query()
            ->with(['user', 'footballField', 'timeSlot'])
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['date'] ?? null, fn (Builder $query, string $date) => $query->whereDate('booking_date', $date))
            ->when($filters['keyword'] ?? null, function (Builder $query, string $keyword): void {
                $query->where(function (Builder $builder) use ($keyword): void {
                    $builder->whereHas('user', fn (Builder $userQuery) => $userQuery
                        ->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('phone', 'like', "%{$keyword}%"))
                        ->orWhereHas('footballField', fn (Builder $fieldQuery) => $fieldQuery
                            ->where('name', 'like', "%{$keyword}%"));
                });
            })
            ->latest('booking_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $statuses = BookingStatus::cases();

        return view('admin.bookings.index', compact('bookings', 'statuses', 'filters'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['user', 'footballField.images', 'timeSlot']);
        $statuses = BookingStatus::cases();

        return view('admin.bookings.show', compact('booking', 'statuses'));
    }

    public function update(UpdateBookingStatusRequest $request, Booking $booking): RedirectResponse
    {
        $status = BookingStatus::from($request->validated('status'));
        $this->bookingService->updateStatus($booking, $status);

        return back()->with('success', 'Trạng thái đơn đặt sân đã được cập nhật.');
    }
}
