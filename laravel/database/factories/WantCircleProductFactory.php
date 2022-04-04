<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{
    CircleProduct,
    User,
    WantCircleProduct,
    WantPriority
};

class WantCircleProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WantCircleProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 10),
            'want_priority_id' => WantPriority::factory(),
            'user_id' => User::factory(),
            'circle_product_id' => CircleProduct::factory(),
        ];
    }
}
