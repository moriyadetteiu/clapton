<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WantCircleProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'want_priority_id',
        'care_about_circle_id',
        'circle_product_id',
        'memo',
    ];

    public function careAboutCircle(): BelongsTo
    {
        return $this->belongsTo(CareAboutCircle::class);
    }

    public function wantPriority(): BelongsTo
    {
        return $this->belongsTo(WantPriority::class);
    }

    public function circleProduct(): BelongsTo
    {
        return $this->belongsTo(CircleProduct::class);
    }

    public function scopeWhereHasUser(Builder $builder, string $userId): Builder
    {
        return $builder->whereHas(
            'careAboutCircle',
            fn (Builder $whereHasBuilder) => $whereHasBuilder->whereHasUser($userId)
        );
    }
}
