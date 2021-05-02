<?php

namespace Database\Factories;

use App\Models\CirclePlacementClassification;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class CirclePlacementClassificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CirclePlacementClassification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $team = Team::inRandomOrder()->first();

        if (!$team) {
            $team = Team::factory()->create();
        }

        return [
            'team_id' => $team->id,
            'name' => $this->faker->name,
            'cost' => $this->faker->numberBetween(0, 10)
        ];
    }
}
