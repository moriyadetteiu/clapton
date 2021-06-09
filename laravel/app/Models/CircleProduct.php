<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany,
};

class CircleProduct extends Model
{
    use HasFactory;

    protected $fillable = ['circle_placement_id', 'circle_product_classification_id', 'name', 'price'];

    public function circlePlacement(): BelongsTo
    {
        return $this->belongsTo(CirclePlacement::class);
    }

    public function circleProductClassification(): BelongsTo
    {
        return $this->belongsTo(CircleProductClassification::class);
    }

    public function wantCircleProducts(): HasMany
    {
        return $this->hasMany(WantCircleProduct::class);
    }
}
