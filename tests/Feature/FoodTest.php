<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\FoodCategory;
use Database\Factories\FoodFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FoodTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_seach_food()
    {
        $foodCategory = FoodCategory::factory()->create();
        $food = Food::factory()->create(['category_id' => $foodCategory->id]);

        $response = $this->getJson(route('foods.search', ['search' => $food->name]));

        $this->assertEquals(1, $this->count($response->json()));
    }
}
