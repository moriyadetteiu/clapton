<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function scopeInEvent(Builder $builder, string $eventId): Builder
    {
        $eventDateIds = Event::findOrFail($eventId)
            ->eventDates()
            ->pluck('id');
        return $builder->whereIn('event_date_id', $eventDateIds);
    }
}
