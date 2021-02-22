<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JoinEventDate extends Model
{
    use HasFactory;

    protected $fillable = ['join_event_id', 'event_date_id', 'is_join', 'number_if_tickets'];

    protected $casts = [
        'is_join' => 'boolean',
    ];

    public function joinEvent(): BelongsTo
    {
        return $this->belongsTo(JoinEvent::class);
    }

    public function eventDate(): BelongsTo
    {
        return $this->belongsTo(EventDate::class);
    }
}
