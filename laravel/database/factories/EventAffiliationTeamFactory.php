<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\EventAffiliationTeam;
use App\Models\Team;
use App\Models\Event;

class EventAffiliationTeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventAffiliationTeam::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $team = Team::inRandomOrder()->first();
        $event = Event::inRandomOrder()->first();

        return [
            'team_id' => $team->id,
            'event_id' => $event->id,
        ];
    }
}
