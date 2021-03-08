<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\CareAboutCircle;
use App\Models\Circle;
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
        $circle = Circle::inRandomOrder()->first();
        $joinEvent = JoinEvent::inRandomOrder()->first();

        return [
            'circle_id' => $circle->id,
            'join_event_id' => $joinEvent->id
        ];
    }
}
