<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\EventDate;
use App\Models\Event;

class EventDateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventDate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'date' => $this->faker->date('Y-m-d', 'now'),
            'is_production_day' => $this->faker->boolean,
            'event_id' => Event::factory(),
        ];
    }
}
