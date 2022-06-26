<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\FoodDiary;
use App\Models\FoodDiaryDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FoodDiaryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_store_food_diary_with_foods()
    {
        //preparation
        $user = $this->authUser();
        $foodCategory = FoodCategory::factory()->create();
        $food = Food::factory()->create(['category_id' => $foodCategory->id]);
        $foodDiary = FoodDiary::factory()->make();
        $foodDiaryDetails = FoodDiaryDetail::factory()->make(['food_id' => $food->id,'food_diary_id' => $foodDiary->id]);
        $servingSize = 200;

        //action
        $response = $this->postJson(route('food-diary.store'), [
            'tgl_food_diary' => $foodDiary->tgl_food_diary,
            'food_list' => [
                'food_id' => $food->id,
                'serving_size' => $servingSize
            ]])
            ->assertCreated()
            ->json();

        //assertion
        $this->assertEquals($foodDiary->tgl_food_diary, $response['tgl_food_diary']);
        $this->assertDatabaseHas('food_diaries', ['tgl_food_diary' => $foodDiary->tgl_food_diary]);
    }
}
