<?php

namespace Database\Factories;

use App\Models\WantPriority;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class WantPriorityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WantPriority::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'name' => $this->faker->name,
        ];
    }
}
