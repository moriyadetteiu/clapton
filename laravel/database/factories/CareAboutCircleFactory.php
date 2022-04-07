<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\CareAboutCircle;
use App\Models\CirclePlacement;
use App\Models\JoinEvent;

class CareAboutCircleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CareAboutCircle::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'circle_placement_id' => CirclePlacement::factory(),
            'join_event_id' => JoinEvent::factory(),
        ];
    }
}
