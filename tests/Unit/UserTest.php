<?php

namespace Tests\Unit;

use App\Models\FoodDiary;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_user_can_has_many_food_diary()
    {
        $user = User::factory()->create();
        $foodDiary = FoodDiary::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Collection::class, $user->foodDiaries);
        $this->assertInstanceOf(FoodDiary::class, $user->foodDiaries->first());
    }

    public function test_user_belongs_to_many_role()
    {
        $role = Role::factory()->create(['name' => 'user', 'display_name' => 'User', 'description' => 'User']);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        $this->assertInstanceOf(Collection::class, $user->roles);
        $this->assertInstanceOf(Role::class, $user->roles->first());
    }
}
