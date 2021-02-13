<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventDate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date', 'is_production_day'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
