<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
    return [
        'user_id' => function() {
            User::factory()->create()->id;
        },
        'doctor_id' => function() {
            User::factory()->create()->id;
        },
        'comment' => $this->faker->sentence(),
        'total_rating' => $this->faker->numberBetween(0, 5)
        ];
    }
}
