<?php

namespace Tests\Feature;

use App\Models\Food;
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
        Food::factory()->create();

        $response = $this->getJson(route('food.search'));

        $this->assertEquals(1, $this->count($response->json()));
    }
}
