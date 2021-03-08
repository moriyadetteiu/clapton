<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
