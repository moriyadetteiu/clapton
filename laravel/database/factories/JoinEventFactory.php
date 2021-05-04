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
        return [
            'team_id' => Team::factory(),
            'event_id' => Event::factory(),
            'user_id' => User::factory(),
        ];
    }
}
