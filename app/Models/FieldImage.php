<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FieldImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'football_field_id',
        'image_url',
        'is_main',
    ];

    protected function casts(): array
    {
        return ['is_main' => 'boolean'];
    }

    public function footballField(): BelongsTo
    {
        return $this->belongsTo(FootballField::class);
    }

    public function getUrlAttribute(): string
    {
        if (Str::startsWith($this->image_url, ['http://', 'https://', '/images/'])) {
            return Str::startsWith($this->image_url, '/images/')
                ? asset(ltrim($this->image_url, '/'))
                : $this->image_url;
        }

        return asset('storage/'.$this->image_url);
    }
}
