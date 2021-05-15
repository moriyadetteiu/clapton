<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{
    Circle,
    CirclePlacement,
    CirclePlacementClassification,
    EventDate,
};

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
        return [
            'event_date_id' => EventDate::factory(),
            'hole' => $this->faker->randomElement(['東', '西', '南']),
            'line' => $this->faker->randomElement(range('A', 'Z')),
            'number' => $this->faker->numberBetween(0, 80),
            'a_or_b' => $this->faker->randomElement(['a', 'b']),
            'circle_id' => Circle::factory(),
            'circle_placement_classification_id' => function ($attributes) {
                $eventDate = EventDate::findOrFail($attributes['event_date_id']);
                return $this->firstRandomRelatedCirclePlacementClassification($eventDate)->id;
            },
        ];
    }

    private function firstRandomRelatedCirclePlacementClassification(EventDate $eventDate): CirclePlacementClassification
    {
        $eventAffiliationTeam = $eventDate
            ->event
            ->eventAffiliationTeams()
            ->inRandomOrder()
            ->first();

        if ($eventAffiliationTeam) {
            $circlePlacementClassification = $eventAffiliationTeam
                ->team
                ->circlePlacementClassifications()
                ->inRandomOrder()
                ->first();
        } else {
            $circlePlacementClassification = CirclePlacementClassification::factory()->create();
        }
        return $circlePlacementClassification;
    }
}
