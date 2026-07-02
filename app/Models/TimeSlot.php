<?php

namespace App\Models;

use App\Enums\TimeSlotStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = ['start_time', 'end_time', 'status'];

    protected function casts(): array
    {
        return ['status' => TimeSlotStatus::class];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', TimeSlotStatus::ACTIVE->value);
    }

    public function getLabelAttribute(): string
    {
        return substr((string) $this->start_time, 0, 5).' - '.substr((string) $this->end_time, 0, 5);
    }

    public function getDurationMinutesAttribute(): int
    {
        $start = Carbon::createFromFormat('H:i:s', (string) $this->start_time);
        $end = Carbon::createFromFormat('H:i:s', (string) $this->end_time);

        return (int) $start->diffInMinutes($end);
    }
}
