<?php

namespace App\Models;

use Auth;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['circle_id', 'user_id', 'memo'];

    public function circle(): BelongsTo
    {
        return $this->belongsTo(Circle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
