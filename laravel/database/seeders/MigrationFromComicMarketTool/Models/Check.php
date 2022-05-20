<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Event;
use App\Models\JoinEvent;
use App\Models\User;
use Database\Seeders\MigrationFromComicMarketTool\IdMapper;

class Check extends Model
{
    protected array $columnMapping = [
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    protected array $relationMapping = [
        'circle_placement_id' => 'circle_placement_id',
    ];

    public function shouldIgnore(IdMapper $idMapper): bool
    {
        return !$this->is_production;
    }

    protected function appendAttributes(IdMapper $idMapper): array
    {
        $circlePlacement = $this->circlePlacement()->firstOrFail();
        $eventId = $idMapper->getModelId(Event::class, $circlePlacement->event_id);
        $userId = $idMapper->getModelId(User::class, $this->user_id);
        $joinEvent = JoinEvent::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return ['join_event_id' => $joinEvent->id];
    }

    public function circlePlacement(): BelongsTo
    {
        return $this->belongsTo(CirclePlacement::class);
    }
}
