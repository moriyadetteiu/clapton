<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Circle extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'kana', 'memo'];

    public function circlePlacements(): HasMany
    {
        return $this->hasMany(CirclePlacement::class);
    }
}
