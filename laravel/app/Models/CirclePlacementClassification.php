<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CirclePlacementClassification extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'name', 'cost'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
