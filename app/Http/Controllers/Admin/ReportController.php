<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from')?->startOfDay() ?? now()->startOfMonth();
        $to = $request->date('to')?->endOfDay() ?? now()->endOfMonth();

        $query = Booking::query()->whereBetween('booking_date', [$from->format('Y-m-d'), $to->format('Y-m-d')]);

        $summary = [
            'total' => (clone $query)->count(),
            'confirmed' => (clone $query)->where('status', BookingStatus::CONFIRMED->value)->count(),
            'completed' => (clone $query)->where('status', BookingStatus::COMPLETED->value)->count(),
            'cancelled' => (clone $query)->where('status', BookingStatus::CANCELLED->value)->count(),
            'revenue' => (float) (clone $query)->whereIn('status', [BookingStatus::CONFIRMED->value, BookingStatus::COMPLETED->value])->sum('total_price'),
        ];

        $byField = Booking::query()
            ->selectRaw('football_field_id, COUNT(*) as total_bookings, SUM(CASE WHEN status IN (?, ?) THEN total_price ELSE 0 END) as revenue', [BookingStatus::CONFIRMED->value, BookingStatus::COMPLETED->value])
            ->whereBetween('booking_date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
            ->with('footballField')
            ->groupBy('football_field_id')
            ->orderByDesc('total_bookings')
            ->get();

        return view('admin.reports.index', compact('from', 'to', 'summary', 'byField'));
    }
}
