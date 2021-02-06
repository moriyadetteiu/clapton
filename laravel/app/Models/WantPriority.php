<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WantPriority extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'name'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
