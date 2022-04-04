<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WantCircleProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'want_priority_id',
        'user_id',
        'circle_product_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wantPriority(): BelongsTo
    {
        return $this->belongsTo(WantPriority::class);
    }

    public function circleProduct(): BelongsTo
    {
        return $this->belongsTo(CircleProduct::class);
    }
}
