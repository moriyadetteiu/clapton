<?php

namespace Database\Seeders\MigrationFromComicMarketTool\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

use App\Models\UserAffiliationTeam;
use App\Models\EventDate;
use App\Models\JoinEvent;
use Database\Seeders\MigrationFromComicMarketTool\IdMapper;

class Participation extends Model
{
    protected function appendAttributes(IdMapper $idMapper): array
    {
        $affiliationId = $idMapper->getModelId(UserAffiliationTeam::class, $this->affiliation_id);
        $eventDateId = $idMapper->getModelId(EventDate::class, $this->event_date_id);
        $userAffiliationTeam = UserAffiliationTeam::findOrFail($affiliationId);
        $eventDate = EventDate::findOrFail($eventDateId);
        return [
            'user_id' => $userAffiliationTeam->user_id,
            'event_id' => $eventDate->event_id,
            'team_id' => $userAffiliationTeam->team_id,
        ];
    }

    // note: 登録しようとしているレコードがすでにあるか調べ、あったら該当Modelを返す
    public function findAlreadyCreatedRecord(IdMapper $idMapper): ?BaseModel
    {
        $attributes = $this->appendAttributes($idMapper);

        return JoinEvent::where('user_id', $attributes['user_id'])
            ->where('event_id', $attributes['event_id'])
            ->where('team_id', $attributes['team_id'])
            ->first();
    }
}
