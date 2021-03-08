<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\CirclePlacement;
use App\Models\EventDate;

class CirclePlacementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CirclePlacement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $eventDate = EventDate::inRandomOrder()->first();
        $circlePlacementClassification = $eventDate
            ->event
            ->eventAffiliationTeams()
            ->inRandomOrder
            ->first()
            ->team
            ->circlePlacementClassifications()
            ->inRandomOrder()
            ->first();

        return [
            'event_date_id' => $eventDate->id,
            'hole' => $this->faker->randomElement(['東', '西', '南']),
            'line' => $this->faker->randomElement(range('A', 'Z')),
            'number' => $this->faker->numberBetween(0, 80),
            'a_or_b' => $this->faker->randomElement(['a', 'b']),
            'circle_placement_classification_id' => $circlePlacementClassification->id,
        ];
    }
}
