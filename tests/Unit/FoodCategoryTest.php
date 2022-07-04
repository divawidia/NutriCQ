<?php

namespace Tests\Unit;

use App\Models\Food;
use App\Models\FoodCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodCategoryTest extends TestCase
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

        $this->assertInstanceOf(Collection::class, $category->foods);
        $this->assertInstanceOf(Food::class, $category->foods->first());
    }
}
