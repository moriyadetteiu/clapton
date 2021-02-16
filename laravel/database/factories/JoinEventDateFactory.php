<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\JoinEvent;
use App\Models\EventDate;
use App\Models\JoinEventDate;

class JoinEventDateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JoinEventDate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $joinEvent = JoinEvent::inRandomOrder()->first();
        $eventDate = EventDate::inRandomOrder()->first();

        return [
            'join_event_id' => $joinEvent->id,
            'event_date_id' => $eventDate->id,
            'is_join' => $this->faker->boolean(),
            'number_if_tickets' => $this->faker->numberBetween(0, 10),
        ];
    }
}
