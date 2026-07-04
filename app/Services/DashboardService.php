<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\FootballField;
use App\Models\User;
use Carbon\Carbon;

class DashboardService
{
    public function summary(int $days = 30): array
    {
        $from = now()->subDays($days - 1)->startOfDay();

        $revenue = Booking::query()
            ->whereIn('status', [BookingStatus::CONFIRMED->value, BookingStatus::COMPLETED->value])
            ->where('created_at', '>=', $from)
            ->sum('total_price');

        $dailyRevenue = collect(range($days - 1, 0))->map(function (int $offset): array {
            $date = now()->subDays($offset)->format('Y-m-d');

            return [
                'date' => Carbon::parse($date)->format('d/m'),
                'value' => (float) Booking::query()
                    ->whereDate('created_at', $date)
                    ->whereIn('status', [BookingStatus::CONFIRMED->value, BookingStatus::COMPLETED->value])
                    ->sum('total_price'),
            ];
        });

        return [
            'totalFields' => FootballField::query()->count(),
            'totalBookings' => Booking::query()->count(),
            'revenue' => (float) $revenue,
            'newUsers' => User::query()->where('role', UserRole::CUSTOMER->value)->where('created_at', '>=', $from)->count(),
            'recentBookings' => Booking::query()->with(['user', 'footballField', 'timeSlot'])->latest()->limit(6)->get(),
            'dailyRevenue' => $dailyRevenue,
            'fieldUsage' => FootballField::query()
                ->withCount(['bookings' => fn ($query) => $query->where('created_at', '>=', $from)])
                ->orderByDesc('bookings_count')
                ->limit(5)
                ->get(),
        ];
    }
}
