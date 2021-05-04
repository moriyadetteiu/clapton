<?php

namespace Database\DatasetFactories;

use App\Models\{
    Event,
    EventAffiliationTeam,
    JoinEvent,
    JoinEventDate,
    EventDate,
};

class EventDatasetFactory
{
    public function create()
    {
        $teamDataset = (new TeamDatasetFactory())->create();
        $user = $teamDataset['user'];
        $team = $teamDataset['team'];
        $event = Event::factory()->has(
            EventDate::factory()
                ->count(3)
                ->sequence(
                    ['name' => '1日目'],
                    ['name' => '2日目'],
                    ['name' => '3日目']
                )
                ->state(['is_production_day' => true])
        )->create();
        EventAffiliationTeam::factory()
            ->create([
                'event_id' => $event->id,
                'team_id' => $team->id,
            ]);
        $joinEvent = JoinEvent::factory()
            ->create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'team_id' => $team->id,
            ]);
        $event->eventDates->each(function ($eventDate) use ($joinEvent) {
            JoinEventDate::factory()
                ->create([
                    'event_date_id' => $eventDate->id,
                    'join_event_id' => $joinEvent->id,
                ]);
        });
        return array_merge($teamDataset, compact('event', 'joinEvent'));
    }
}
