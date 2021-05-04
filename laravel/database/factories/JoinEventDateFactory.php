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
        return [
            'join_event_id' => JoinEvent::factory(),
            'event_date_id' => EventDate::factory(),
            'is_join' => $this->faker->boolean(),
            'number_of_tickets' => $this->faker->numberBetween(0, 10),
        ];
    }
}
