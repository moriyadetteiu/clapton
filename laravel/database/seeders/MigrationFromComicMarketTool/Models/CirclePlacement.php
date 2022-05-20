<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\CirclePlacementClassification;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Team;
use Database\Seeders\MigrationFromComicMarketTool\IdMapper;
use Database\Seeders\MigrationFromComicMarketTool\Models\Event as FromEvent;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CirclePlacement extends Model
{
    protected array $columnMapping = [
        'place_hole' => 'hole',
        'place_alpha' => 'line',
        'place_a_b' => 'a_or_b',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    protected array $relationMapping = [
        'circle_id' => 'circle_id',
    ];

    protected function appendAttributes(IdMapper $idMapper): array
    {
        $eventDate = $this->findAppEventDate($idMapper);
        $circlePlacementClassification = $this->findAppCirclePlacementClassification($idMapper);

        return [
            'event_date_id' => $eventDate->id,
            'circle_placement_classification_id' => $circlePlacementClassification->id,
            'number' => (int)$this->place_num,
        ];
    }

    private function findAppEventDate(IdMapper $idMapper): ?EventDate
    {
        $eventId = $idMapper->getModelId(Event::class, $this->event_id);
        return EventDate::where('event_id', $eventId)
            ->where('name', $this->place_date)
            ->firstOrFail();
    }

    private function findAppCirclePlacementClassification(IdMapper $idMapper): ?CirclePlacementClassification
    {
        $teamId = $idMapper->getModelId(Team::class, $this->event->group_id);
        $placeClassificationMaster = $this->placeClassificationMaster;
        if (is_null($placeClassificationMaster)) {
            // note: 不正データは消したいので、ログで分かるようにしてデバッグしやすくしている
            throw new ModelNotFoundException("invalid data! place_classification_master_id is null");
        }
        $placeClassificationName = $placeClassificationMaster->name;
        return CirclePlacementClassification::where('name', $placeClassificationName)
            ->where('team_id', $teamId)
            ->firstOrFail();
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(FromEvent::class, 'event_id');
    }

    public function placeClassificationMaster(): BelongsTo
    {
        return $this->belongsTo(PlaceClassificationMaster::class);
    }
}
