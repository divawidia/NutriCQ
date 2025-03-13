<?php

namespace Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\GoalService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Mockery;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    public function test_index_returns_paginated_users()
    {
        $goalServiceMock = Mockery::mock(GoalService::class);
        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $expectedUsers = ['user1', 'user2'];

        $userRepositoryMock->shouldReceive('index')
            ->once()
            ->with([], 'search', 'male', 160, 190, 50, 90, 'active', 'active', 1, 10)
            ->andReturn($expectedUsers);

        $service = new UserService($goalServiceMock, $userRepositoryMock);

        $result = $service->index('search', 'male', 160, 190, 50, 90, 'active', 'active', 1, 10);

        $this->assertEquals($expectedUsers, $result);
    }

    public function test_update_auth_user_profile_updates_user_and_goal()
    {
        $goalServiceMock = Mockery::mock(GoalService::class);
        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $service = new UserService($goalServiceMock, $userRepositoryMock);

        $userMock = Mockery::mock(User::class)->makePartial();
        $goalRelationMock = Mockery::mock(HasOne::class);
        $goalHistoriesRelationMock = Mockery::mock(HasMany::class);

        $data = [
            'gender' => 'male',
            'tingkat_aktivitas' => 'active',
            'berat_badan' => 70,
            'tinggi_badan' => 175,
        ];

        $goalData = [
            'calories' => 2500,
            'protein' => 150,
            'fat' => 70,
            'carbs' => 300,
        ];

        $userMock->shouldReceive('getAgeAttribute')->once()->andReturn(25);

        $goalServiceMock->shouldReceive('calculateGoal')
            ->once()
            ->with(
                $data['gender'],
                $data['tingkat_aktivitas'],
                $data['berat_badan'],
                $data['tinggi_badan'],
                25
            )
            ->andReturn($goalData);

        $userMock->shouldReceive('update')->once()->with($data)->andReturnTrue();

        $userMock->shouldReceive('goal')->once()->andReturn($goalRelationMock);
        $goalRelationMock->shouldReceive('updateOrCreate')->once()->with($goalData)->andReturnTrue();

        $userMock->shouldReceive('goalHistories')->once()->andReturn($goalHistoriesRelationMock);
        $goalHistoriesRelationMock->shouldReceive('create')->once()->with($goalData)->andReturnTrue();

        $result = $service->updateAuthUserProfile($data, $userMock);

        $this->assertInstanceOf(User::class, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
