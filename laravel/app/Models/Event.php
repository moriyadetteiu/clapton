<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $casts = [
        'is_progress' => 'boolean',
    ];

    public function eventDates(): HasMany
    {
        return $this->hasMany(EventDate::class);
    }

    public function eventAffiliationTeams(): HasMany
    {
        return $this->hasMany(EventAffiliationTeam::class);
    }

    public function scopeFilterUnderwayEvents(Builder $builder): Builder
    {
        return $builder->where('is_progress', true);
    }

    public function scopeFilterFinishedEvents(Builder $builder): Builder
    {
        return $builder->where('is_progress', false);
    }

    public function updateIsProgress(): void
    {
        $this->is_progress = $this->judgeIsProgress();
        $this->save();
    }

    private function judgeIsProgress(): bool
    {
        $latestDate = optional($this->eventDates()->latest('date')->first())->date;
        if (is_null($latestDate)) {
            return false;
        }

        return now()->setTime(0, 0)->lessThanOrEqualTo($latestDate);
    }
}
