<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotParticipationCircle extends Model
{
    use HasFactory;

    protected $fillable = ['circle_id', 'event_id'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }


    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }
}
