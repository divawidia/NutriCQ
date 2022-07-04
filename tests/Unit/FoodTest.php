<?php

namespace Tests\Unit;

use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\FoodDiaryDetail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_food_can_has_many_food_diary_detail()
    {
        $category = FoodCategory::factory()->create();
        $food = Food::factory()->create(['category_id' => $category->id]);
        $foodDiaryDetails = FoodDiaryDetail::factory()->create([
            'food_id' => $food->id
        ]);

        $this->assertInstanceOf(Collection::class, $food->foodDiaryDetails);
        $this->assertInstanceOf(FoodDiaryDetail::class, $food->foodDiaryDetails->first());
    }

    public function test_food_belongs_to_food_category()
    {
        $category = FoodCategory::factory()->create();
        $food = Food::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(FoodCategory::class, $food->foodCategory);
    }

    public function test_if_food_deleted_then_all_food_diary_detail_will_be_deleted()
    {
        $category = FoodCategory::factory()->create();
        $food = Food::factory()->create(['category_id' => $category->id]);
        $foodDiaryDetails1 = FoodDiaryDetail::factory()->create([
            'food_id' => $food->id
        ]);
        $foodDiaryDetails2 = FoodDiaryDetail::factory()->create([
            'food_id' => $food->id
        ]);

        $food->delete();

        $this->assertDatabaseMissing('foods', ['id' => $food->id]);
        $this->assertDatabaseMissing('food_diary_details', ['id' => $foodDiaryDetails1->id]);
        $this->assertDatabaseMissing('food_diary_details', ['id' => $foodDiaryDetails2->id]);
    }
}
