<?php

namespace Tests\Unit;

use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\FoodDiary;
use App\Models\FoodDiaryDetail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FoodDiaryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_food_diary_has_many_food_diary_details()
    {
        $category = FoodCategory::factory()->create();
        $food = Food::factory()->create(['category_id' => $category->id]);
        $foodDiary = FoodDiary::factory()->create();
        $foodDiaryDetails = FoodDiaryDetail::factory()->create([
            'food_diary_id' => $foodDiary->id,
            'food_id' => $food->id
        ]);

        $this->assertInstanceOf(Collection::class, $foodDiary->foodDiaryDetails);
        $this->assertInstanceOf(FoodDiaryDetail::class, $foodDiary->foodDiaryDetails->first());
    }

    public function test_food_diary_belongs_to_user()
    {
        $role = Role::factory()->create(['name' => 'user', 'display_name' => 'User', 'description' => 'User']);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        Sanctum::actingAs(
            $user,
            ['*']
        );
        $foodDiary = FoodDiary::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $foodDiary->user);
    }

    public function test_if_food_diary_deleted_then_all_food_diary_detail_will_be_deleted()
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

        $foodDiaryDetails1 = FoodDiaryDetail::factory()->create([
            'food_id' => $food->id,
            'food_diary_id' => $foodDiary->id
        ]);
        $foodDiaryDetails2 = FoodDiaryDetail::factory()->create([
            'food_id' => $food->id,
            'food_diary_id' => $foodDiary->id
        ]);

        $foodDiary->delete();

        $this->assertDatabaseMissing('food_diaries', ['id' => $foodDiary->id]);
        $this->assertDatabaseMissing('food_diary_details', ['id' => $foodDiaryDetails1->id]);
        $this->assertDatabaseMissing('food_diary_details', ['id' => $foodDiaryDetails2->id]);
    }
}
