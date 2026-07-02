<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'football_field_id',
        'time_slot_id',
        'booking_date',
        'total_price',
        'status',
        'note',
        'slot_lock_key',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'total_price' => 'decimal:2',
            'status' => BookingStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function footballField(): BelongsTo
    {
        return $this->belongsTo(FootballField::class);
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function scopeActiveLock(Builder $query): Builder
    {
        return $query->whereIn('status', [
            BookingStatus::PENDING->value,
            BookingStatus::CONFIRMED->value,
        ]);
    }

    public function canBeCancelledBy(User $user): bool
    {
        if ($user->isAdmin()) {
            return in_array($this->status, [BookingStatus::PENDING, BookingStatus::CONFIRMED], true);
        }

        if ($this->user_id !== $user->id || $this->status !== BookingStatus::PENDING) {
            return false;
        }

        $start = Carbon::parse(
            $this->booking_date->format('Y-m-d').' '.substr((string) $this->timeSlot->start_time, 0, 8),
            config('app.timezone')
        );

        return now()->addHours(config('booking.cancellation_hours', 24))->lte($start);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format((float) $this->total_price, 0, ',', '.').' ₫';
    }

    public function getLockKeyAttribute(): string
    {
        return self::makeLockKey($this->football_field_id, $this->booking_date->format('Y-m-d'), $this->time_slot_id);
    }

    public static function makeLockKey(int $fieldId, string $date, int $slotId): string
    {
        return $fieldId.'|'.$date.'|'.$slotId;
    }
}
