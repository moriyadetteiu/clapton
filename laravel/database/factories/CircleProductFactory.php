<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{
    CirclePlacement,
    CircleProduct,
    CircleProductClassification,
};

class CircleProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CircleProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->randomNumber,
            'circle_product_classification_id' => CircleProductClassification::factory(),
            'circle_placement_id' => CirclePlacement::factory(),
        ];
    }
}
