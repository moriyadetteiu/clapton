<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function eventAffiliationTeams(): HasMany
    {
        return $this->hasMany(EventAffiliationTeam::class);
    }
}
