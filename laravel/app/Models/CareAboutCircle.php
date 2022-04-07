<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareAboutCircle extends Model
{
    use HasFactory;

    protected $fillable = ['circle_placement_id', 'join_event_id'];

    public function circlePlacement(): BelongsTo
    {
        return $this->belongsTo(CirclePlacement::class);
    }

    public function joinEvent(): BelongsTo
    {
        return $this->belongsTo(JoinEvent::class);
    }
}
