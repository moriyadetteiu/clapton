<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Circle;
use App\Models\Favorite;
use App\Models\User;

class FavoriteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Favorite::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'circle_id' => Circle::factory(),
            'memo' => $this->faker->text(),
        ];
    }
}
