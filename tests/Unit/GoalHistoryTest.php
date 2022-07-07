<?php

namespace Tests\Unit;

use App\Models\GoalHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoalHistoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_goal_history_belongs_to_user()
    {
        $user = User::factory()->create();
        $goalHistory = GoalHistory::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $goalHistory->user);
    }
}
