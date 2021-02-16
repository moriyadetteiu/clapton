<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\JoinEvent;
use App\Models\Team;
use App\Models\Event;
use App\Models\User;


class JoinEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JoinEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $team = Team::inRandomOrder()->first();
        $event = Event::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        return [
            'team_id' => $team->id,
            'event_id' => $event->id,
            'user_id' => $user->id,
        ];
    }
}
