<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use App\Models\EventAffiliationTeam;
use App\Models\Team;
use Database\Seeders\MigrationFromComicMarketTool\IdMapper;

class EventDate extends Model
{
    protected array $columnMapping = [
        'name' => 'name',
        'date' => 'date',
        'is_that_day' => 'is_production_day',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',

    ];

    protected array $relationMapping = [
        'event_id' => 'event_id',
    ];

    public function shouldIgnore(IdMapper $idMapper): bool
    {
        return $this->date === '0000-00-00';
    }
}
