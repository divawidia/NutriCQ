<?php

namespace Repositories;

use App\Models\Booking;
use App\Models\FoodDiary;
use App\Models\Goal;
use App\Models\GoalHistory;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    public function test_index_returns_paginated_users()
    {
        $userMock = Mockery::mock(User::class);
        $queryMock = Mockery::mock();
        $paginatorMock = Mockery::mock(LengthAwarePaginator::class);

        $userMock->shouldReceive('with')->once()->with([])->andReturn($queryMock);
        $queryMock->shouldReceive('when')->times(8)->andReturnSelf();
        $queryMock->shouldReceive('role')->once()->with('user')->andReturnSelf();
        $queryMock->shouldReceive('paginate')->once()->with(10, ['*'], 'page', 1)->andReturn($paginatorMock);
        $paginatorMock->shouldReceive('get')->once()->andReturn('paginated_users');

        $repository = new UserRepository($userMock);

        $result = $repository->index();

        $this->assertEquals('paginated_users', $result);
    }

    public function test_create_creates_a_user_with_role()
    {
        $userMock = Mockery::mock(User::class);
        $repository = new UserRepository($userMock);

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'plain-password',
        ];

        $expectedData = $data;
        $expectedData['password'] = Hash::make($data['password']);

        $userMock->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function($arg) use ($data) {
                // Only check that the password is hashed
                return isset($arg['password']) && !($arg['password'] === $data['password']);
            }))
            ->andReturn(new User($expectedData));

        $user = $repository->create($data, 'admin');

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_find_active_by_email_returns_user()
    {
        $userMock = Mockery::mock(User::class);
        $queryMock = Mockery::mock();

        $userMock->shouldReceive('where')->once()->with('email', 'john@example.com')->andReturn($queryMock);
        $queryMock->shouldReceive('where')->once()->with('status', 'active')->andReturnSelf();
        $queryMock->shouldReceive('first')->once()->andReturn(new User());

        $repository = new UserRepository($userMock);

        $user = $repository->findActiveByEmail('john@example.com');

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_find_active_by_email_returns_null_when_not_found()
    {
        $userMock = Mockery::mock(User::class);
        $queryMock = Mockery::mock();

        $userMock->shouldReceive('where')->once()->with('email', 'john@example.com')->andReturn($queryMock);
        $queryMock->shouldReceive('where')->once()->with('status', 'active')->andReturnSelf();
        $queryMock->shouldReceive('first')->once()->andReturn(null);

        $repository = new UserRepository($userMock);

        $user = $repository->findActiveByEmail('john@example.com');

        $this->assertNull($user);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
