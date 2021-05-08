<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany
};

class CirclePlacement extends Model
{
    use HasFactory;

    protected $fillable = [
        'circle_id',
        'event_date_id',
        'hole',
        'line',
        'number',
        'a_or_b',
        'circle_placement_classification_id',
    ];

    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }

    public function eventDate(): BelongsTo
    {
        return $this->belongsTo(EventDate::class);
    }

    public function circleProducts(): HasMany
    {
        return $this->hasMany(CircleProduct::class);
    }

    public function scopeInEvent(Builder $builder, string $eventId): Builder
    {
        $eventDateIds = Event::findOrFail($eventId)
            ->eventDates()
            ->pluck('id');
        return $builder->whereIn('event_date_id', $eventDateIds);
    }

    public function getFormattedNumberAttribute(): string
    {
        return str_pad((string)$this->number, 2, '0', STR_PAD_LEFT);
    }

    public function getFormattedPlacementAttribute(): string
    {
        $eventDate = $this->eventDate;
        return "{$eventDate->name} {$this->hole} {$this->line}-{$this->formatted_number}{$this->a_or_b}";
    }
}
