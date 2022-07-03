<?php

namespace Tests\Unit;

use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\FoodDiary;
use App\Models\FoodDiaryDetail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FoodDiaryDetailTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_food_diary_belongs_to_relations()
    {
        $role = Role::factory()->create(['name' => 'user', 'display_name' => 'User', 'description' => 'User']);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $category = FoodCategory::factory()->create();
        $food = Food::factory()->create(['category_id' => $category->id]);

        $foodDiary = FoodDiary::factory()->create(['user_id' => $user->id]);
        $foodDiaryDetail = FoodDiaryDetail::factory()->create([
            'food_id' => $food->id,
            'food_diary_id' => $foodDiary->id
        ]);

        $this->assertInstanceOf(Food::class, $foodDiaryDetail->foods);
        $this->assertInstanceOf(FoodDiary::class, $foodDiaryDetail->foodDiary);
    }
}
