<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventDate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date', 'is_production_day'];

    protected $dates = ['date'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getFullFormatDateAttribute(): string
    {
        $day = $this->date->getTranslatedDayName();
        $dateString = $this->date->format('Y/m/d');
        return "{$this->name}（{$dateString} {$day}）";
    }
}
