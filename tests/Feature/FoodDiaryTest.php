<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\FoodDiary;
use App\Models\FoodDiaryDetail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    public function test_store_food_diary_with_foods()
    {
        //preparation
        $role = Role::factory()->create(['name' => 'user', 'display_name' => 'User', 'description' => 'User']);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        Sanctum::actingAs(
            $user,
            ['*']
        );
        $foodDiary = FoodDiary::factory()->make(['user_id' => $user->id]);

        //action
        $response = $this->postJson(route('food-diary.store'), [
            'tgl_food_diary' => $foodDiary->tgl_food_diary])
            ->assertCreated()
            ->json();

        //assertion
        $this->assertEquals($foodDiary->tgl_food_diary, $response[0]['tgl_food_diary']);
        $this->assertDatabaseHas('food_diaries', ['tgl_food_diary' => $foodDiary->tgl_food_diary]);
    }
}
