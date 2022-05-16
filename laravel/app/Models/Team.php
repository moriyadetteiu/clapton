<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function UserAffiliationTeams(): HasMany
    {
        return $this->hasMany(UserAffiliationTeam::class);
    }

    public function eventAffiliationTeams(): HasMany
    {
        return $this->hasMany(EventAffiliationTeam::class);
    }

    public function underwayEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_affiliation_teams');
    }
}
