<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\FoodDiary;
use App\Models\FoodDiaryDetail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FoodDiaryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $role = Role::factory()->create(['name' => 'user', 'display_name' => 'User', 'description' => 'User']);
        $this->user = User::factory()->create();
        $this->user->roles()->attach($role);

        Sanctum::actingAs(
            $this->user,
            ['*']
        );
        $this->foodDiary = FoodDiary::factory()->create(['user_id' => $this->user->id]);

    }

    public function test_store_food_diary_without_foods()
    {
        //preparation
        $foodDiary = FoodDiary::factory()->make(['user_id' => $this->user->id]);

        //action
        $response = $this->postJson(route('food-diary.store'), [
            'tgl_food_diary' => $foodDiary->tgl_food_diary])
            ->assertCreated()
            ->json();

        //assertion
        $this->assertEquals($foodDiary->tgl_food_diary, $response[0]['tgl_food_diary']);
        $this->assertDatabaseHas('food_diaries', ['tgl_food_diary' => $foodDiary->tgl_food_diary]);
    }

//    public function test_store_food_diary_without_foods_validation()
//    {
//        //preparation
//        $foodDiary = FoodDiary::factory()->make(['user_id' => $this->user->id]);
//
//        //action
//        $response = $this->postJson(route('food-diary.store'), [
//            'tgl_food_diary' => null]);
//
//        $response->assertJsonMissingValidationErrors(['tgl_food_diary']);
//        //assertion
////        $this->assertEquals($foodDiary->tgl_food_diary, $response[0]['tgl_food_diary']);
////        $this->assertDatabaseHas('food_diaries', ['tgl_food_diary' => $foodDiary->tgl_food_diary]);
//    }

    public function test_fetch_all_food_diary()
    {
        //action
        $response = $this->getJson(route('food-diary.index'));

        //assertion
        $this->assertEquals(1, $this->count($response->json()));
    }

    public function test_fetch_single_food_diary()
    {
        //action
        $response = $this->getJson(route('food-diary.show', $this->foodDiary->id))
            ->assertOk()
            ->json();

        //assertion
        $this->assertEquals($response[0]['tgl_food_diary'], $this->foodDiary->tgl_food_diary);
    }

    public function test_store_food_diary_with_foods()
    {
        //preparation
        $foodCategory = FoodCategory::factory()->create();
        $food = Food::factory()->create(['category_id' => $foodCategory->id]);
        $servingSize = 200;
        $foodDiary = FoodDiary::factory()->make(['user_id' => $this->user->id]);
        $foodDiaryDetail = FoodDiaryDetail::factory()->make(['food_diary_id' => $foodDiary->id]);

        //action
        $response = $this->postJson(route('food-diary.store'), [
            'tgl_food_diary' => $foodDiary->tgl_food_diary,
            'food_id' => $food->id,
            'serving_size' => $servingSize])
            ->assertCreated()
            ->json();

        //dd($response, $food, $foodCategory);

        //assertion
        $this->assertEquals($foodDiary->tgl_food_diary, $response[0]['tgl_food_diary']);
        $this->assertDatabaseHas('food_diaries', ['tgl_food_diary' => $foodDiary->tgl_food_diary, 'total_air' => $response[0]['total_air']]);
        $this->assertDatabaseHas('food_diary_details', ['food_id' => $food->id, 'food_diary_id' => $response[0]['id'], 'air' => $food->air * $servingSize/100]);
    }

    public function test_add_food_to_existing_food_diary()
    {
        //preparation
        $foodCategory = FoodCategory::factory()->create();
        $food = Food::factory()->create(['category_id' => $foodCategory->id]);
        $servingSize = 200;
        $foodDiaryDetail = FoodDiaryDetail::factory()->make(['food_diary_id' => $this->foodDiary->id]);

        //action
        $response = $this->patchJson(route('food-diary.addFoodToExistingFoodDiary', $this->foodDiary->id), [
            'food_id' => $food->id,
            'serving_size' => $servingSize])
            ->assertCreated()
            ->json();

        //assertion
        $this->assertEquals($response[0]['total_air'], $this->foodDiary->total_air + $food->air * $servingSize/100);
        $this->assertDatabaseHas('food_diary_details', ['food_id' => $food->id, 'food_diary_id' => $response[0]['id'], 'air' => $food->air * $servingSize/100]);
    }

    public function test_store_calculated_food_to_food_diary()
    {
        //preparation
        $foodCategory = FoodCategory::factory()->create();
        $food = Food::factory()->create(['category_id' => $foodCategory->id]);
        $servingSize = 200;

        //action
        $response = $this->patchJson(route('foods.storeCalculatedFoodToFoodDiary', $food->id), [
            'serving_size' => $servingSize,
            'food_diary_id' => $this->foodDiary->id])
            ->assertCreated()
            ->json();
        //dd($response['data'][0]['total_air']);

        //assertion
        $this->assertEquals($response['data'][0]['total_air'], $this->foodDiary->total_air + $food->air * $servingSize/100);
        $this->assertDatabaseHas('food_diary_details', ['food_id' => $food->id, 'food_diary_id' => $response['data'][0]['id'], 'air' => $food->air * $servingSize/100]);
    }
}
