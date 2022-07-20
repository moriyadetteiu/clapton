<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class CareAboutCircle extends Model
{
    use HasFactory;

    protected $fillable = ['circle_placement_id', 'join_event_id', 'memo'];

    public function circlePlacement(): BelongsTo
    {
        return $this->belongsTo(CirclePlacement::class);
    }

    public function joinEvent(): BelongsTo
    {
        return $this->belongsTo(JoinEvent::class);
    }

    public function scopeWhereHasUser(Builder $builder, string $userId)
    {
        return $builder->whereHas(
            'joinEvent',
            fn (Builder $whereHasBuilder) => $whereHasBuilder->where('user_id', $userId)
        );
    }
}
