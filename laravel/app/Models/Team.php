<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    // code生成
    public static function generateCode()
    {
        return uniqid();
    }

    public function circlePlacementClassifications(): HasMany
    {
        return $this->hasMany(CirclePlacementClassification::class);
    }

    public function circleProductClassifications(): HasMany
    {
        return $this->hasMany(CircleProductClassification::class);
    }

    public function wantPriorities(): HasMany
    {
        return $this->hasMany(WantPriority::class);
    }
}
