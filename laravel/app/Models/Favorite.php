<?php

namespace App\Models;

use Auth;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['circle_id', 'user_id', 'memo'];

    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function participateInEventState(string $eventId): string
    {
        $eventDates = Event::findOrFail($eventId)->eventDates()->pluck('id');
        $circlePlacement = $this->circle->circlePlacements()->whereIn('event_date_id', $eventDates)->first();
        if ($circlePlacement && $circlePlacement->careAboutCircles()->whereHasUser($this->user_id)->exists()) {
            return 'チェック済';
        }

        return $this->circle->participateInEventState($eventId);
    }
}
