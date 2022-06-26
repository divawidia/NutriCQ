<?php

namespace Database\Factories;

use App\Models\Food;
use App\Models\FoodDiary;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodDiaryDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'food_id' => function(){
                return Food::factory()->create()->id;
            },
            'food_diary_id' => function(){
                return FoodDiary::factory()->create()->id;
            },
            'air' => $this->faker->randomFloat(1),
            'energi' => $this->faker->randomFloat(1),
            'protein' => $this->faker->randomFloat(1),
            'lemak' => $this->faker->randomFloat(1),
            'karbohidrat' => $this->faker->randomFloat(1),
            'serat' => $this->faker->randomFloat(1),
            'abu' => $this->faker->randomFloat(1),
            'kalsium' => $this->faker->randomFloat(1),
            'fosfor' => $this->faker->randomFloat(1),
            'besi' => $this->faker->randomFloat(1),
            'natrium' => $this->faker->randomFloat(1),
            'kalium' => $this->faker->randomFloat(1),
            'tembaga' => $this->faker->randomFloat(1),
            'seng' => $this->faker->randomFloat(1),
            'retinol' => $this->faker->randomFloat(1),
            'b_karoten' => $this->faker->randomFloat(1),
            'karoten_total' => $this->faker->randomFloat(1),
            'thiamin' => $this->faker->randomFloat(1),
            'riboflamin' => $this->faker->randomFloat(1),
            'niasin' => $this->faker->randomFloat(1),
            'vitamin_c' => $this->faker->randomFloat(1),
            'takaran_saji' => $this->faker->randomDigit()
        ];
    }
}
