<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use App\Models\EventAffiliationTeam;
use App\Models\Team;
use Database\Seeders\MigrationFromComicMarketTool\IdMapper;

class Event extends Model
{
    protected array $columnMapping = [
        'name' => 'name',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    public function migrateSpecifyPattern(IdMapper $idMapper): void
    {
        $attributes = [
            'team_id' => $idMapper->getModelId(Team::class, $this->group_id),
            'event_id' => $idMapper->getModelId(\App\Models\Event::class, $this->id),
        ];
        $eventAffiliationTeam = EventAffiliationTeam::create($attributes);
        $idMapper->addMap($eventAffiliationTeam, $this->id);
    }
}
