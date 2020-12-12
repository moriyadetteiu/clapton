<?php

namespace Database\Factories;

use App\Models\EventDate;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'date' => $this->faker->date($format='Y-m-d', $max='now'),
            'is_production_day' => $this->faker->boolean,
        ];
    }
}
