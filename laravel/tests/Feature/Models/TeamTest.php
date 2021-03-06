<?php

namespace Tests\Feature\Models;

use App\Models\Event;
use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Team;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use WithFaker;

    public function testTeamCreatedObserver()
    {
        $team = Team::create([
            'name' => $this->faker->name(),
            'code' => Team::generateCode()
        ]);

        $this->assertEquals($team->circlePlacementClassifications->count(), 7);
        $circlePlacementClassificationNames = $team->circlePlacementClassifications->pluck('name');
        $this->assertContains('シャッター', $circlePlacementClassificationNames);

        $this->assertEquals($team->circleProductClassifications->count(), 5);
        $circleProductClassificationNames = $team->circleProductClassifications->pluck('name');
        $this->assertContains('新刊', $circleProductClassificationNames);

        $this->assertEquals($team->wantPriorities->count(), 4);
        $wantPriorityNames = $team->wantPriorities->pluck('name');
        $this->assertContains('最高', $wantPriorityNames);
    }

    public function testUnderwayEventsRelation()
    {
        $team = Team::factory()->create();
        $team->eventAffiliationTeams->each(fn ($eventAffiliationTeam) => $eventAffiliationTeam->delete());
        $events = Event::factory(3)->create();
        $events->each(function ($event) use ($team) {
            $team->eventAffiliationTeams()->create(['event_id' => $event->id]);
        });

        $team->refresh();
        $underwayEventIds = $team->underwayEvents->pluck('id');
        $expectEventIds = $events->pluck('id');
        $this->assertEquals($underwayEventIds, $expectEventIds);
    }
}
