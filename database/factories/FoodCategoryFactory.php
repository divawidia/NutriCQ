<?php

namespace Database\Factories;

use App\Models\FoodCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = FoodCategory::class;

    public function definition()
    {
        return [
            'category_name' => $this->faker->name
        ];
    }
}
