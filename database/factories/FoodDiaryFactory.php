<?php

namespace Database\Factories;

use App\Models\FoodDiary;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodDiaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = FoodDiary::class;

    public function definition()
    {
        return [
            'tgl_food_diary' => $this->faker->date,
            'total_air' => $this->faker->randomFloat(1),
            'total_energi'=> $this->faker->randomFloat(1),
            'total_protein'=> $this->faker->randomFloat(1),
            'total_lemak'=> $this->faker->randomFloat(1),
             'total_karbohidrat'=> $this->faker->randomFloat(1),
             'total_serat'=> $this->faker->randomFloat(1),
             'total_abu'=> $this->faker->randomFloat(1),
             'total_kalsium'=> $this->faker->randomFloat(1),
             'total_fosfor'=> $this->faker->randomFloat(1),
             'total_besi'=> $this->faker->randomFloat(1),
             'total_natrium'=> $this->faker->randomFloat(1),
             'total_kalium'=> $this->faker->randomFloat(1),
             'total_tembaga'=> $this->faker->randomFloat(1),
             'total_seng'=> $this->faker->randomFloat(1),
             'total_retinol'=> $this->faker->randomFloat(1),
             'total_b_karoten'=> $this->faker->randomFloat(1),
             'total_karoten_total'=> $this->faker->randomFloat(1),
             'total_thiamin'=> $this->faker->randomFloat(1),
             'total_riboflamin'=> $this->faker->randomFloat(1),
             'total_niasin'=> $this->faker->randomFloat(1),
             'total_vitamin_c'=> $this->faker->randomFloat(1),
             'jumlah_makanan'=> $this->faker->randomDigit(),
             'user_id'=> function(){
                 return User::factory()->create()->id;
             }
        ];
    }
}
