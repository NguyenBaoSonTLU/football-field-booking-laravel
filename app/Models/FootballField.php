<?php

namespace App\Models;

use App\Enums\FootballFieldStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FootballField extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'description',
        'amenities',
        'price_per_hour',
        'open_time',
        'close_time',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'price_per_hour' => 'decimal:2',
            'status' => FootballFieldStatus::class,
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(FieldImage::class)->orderByDesc('is_main')->orderBy('id');
    }

    public function mainImage(): HasOne
    {
        return $this->hasOne(FieldImage::class)->where('is_main', true);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', FootballFieldStatus::ACTIVE->value);
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (! $keyword) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($keyword): void {
            $builder->where('name', 'like', "%{$keyword}%")
                ->orWhere('address', 'like', "%{$keyword}%");
        });
    }

    public function getMainImageUrlAttribute(): string
    {
        $image = $this->relationLoaded('mainImage') ? $this->mainImage : $this->mainImage()->first();

        return $image?->url ?? asset('images/field-default.svg');
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format((float) $this->price_per_hour, 0, ',', '.').' ₫/giờ';
    }
}
