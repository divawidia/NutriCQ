<?php

namespace Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\GoalService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    public function test_register_creates_user_and_assigns_role()
    {
        $userRepositoryMock = Mockery::mock(UserRepository::class);
        $goalServiceMock = Mockery::mock(GoalService::class);

        $userMock = Mockery::mock(User::class)->makePartial();
        $goalRelationMock = Mockery::mock(HasOne::class);
        $goalHistoriesRelationMock = Mockery::mock(HasMany::class);

        $data = [
            'gender' => 'male',
            'tingkat_aktivitas' => 'active',
            'berat_badan' => 70,
            'tinggi_badan' => 175,
            'cv' => UploadedFile::fake()->create('cv.pdf', 100),
            'license' => UploadedFile::fake()->create('license.jpg', 100),
        ];

        $storedData = $data;
        $storedData['cv'] = 'public/cv/cv.pdf';
        $storedData['license'] = 'public/license/license.jpg';

        $userRepositoryMock->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return isset($arg['cv']) && isset($arg['license']);
            }))
            ->andReturn($userMock);

        $userMock->shouldReceive('assignRole')->once()->with('user');

        $userMock->shouldReceive('getAgeAttribute')->once()->andReturn(25);

        $goalServiceMock->shouldReceive('calculateGoal')
            ->once()
            ->with(
                $storedData['gender'],
                $storedData['tingkat_aktivitas'],
                $storedData['berat_badan'],
                $storedData['tinggi_badan'],
                25
            )
            ->andReturn([
                'calories' => 2500,
                'protein' => 150,
                'fat' => 70,
                'carbs' => 300,
            ]);

        $userMock->shouldReceive('goal')->once()->andReturn($goalRelationMock);
        $goalRelationMock->shouldReceive('updateOrCreate')->once();

        $userMock->shouldReceive('goalHistories')->once()->andReturn($goalHistoriesRelationMock);
        $goalHistoriesRelationMock->shouldReceive('create')->once();

        $service = new AuthService($userRepositoryMock, $goalServiceMock);

        $user = $service->register($data, 'user');

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_login_success()
    {
        $userRepositoryMock = Mockery::mock(UserRepository::class);
        $goalServiceMock = Mockery::mock(GoalService::class);

        $userMock = Mockery::mock(User::class)->makePartial();
        $userMock->password = Hash::make('password123');

        $userMock->shouldReceive('createToken')
            ->once()
            ->with('nutricqtoken')
            ->andReturn((object)['plainTextToken' => 'generated_token']);

        $userRepositoryMock->shouldReceive('findActiveByEmail')
            ->once()
            ->with('test@example.com')
            ->andReturn($userMock);

        $service = new AuthService($userRepositoryMock, $goalServiceMock);

        $response = $service->login('test@example.com', 'password123');

        $this->assertEquals($userMock, $response['user']);
        $this->assertEquals('generated_token', $response['token']);
    }

    public function test_login_failure_invalid_credentials()
    {
        $this->expectException(ValidationException::class);

        $userRepositoryMock = Mockery::mock(UserRepository::class);
        $goalServiceMock = Mockery::mock(GoalService::class);

        $userMock = Mockery::mock(User::class)->makePartial();
        $userMock->password = Hash::make('correctpassword');

        $userRepositoryMock->shouldReceive('findActiveByEmail')
            ->once()
            ->with('wrong@example.com')
            ->andReturn($userMock);

        $service = new AuthService($userRepositoryMock, $goalServiceMock);

        $service->login('wrong@example.com', 'wrongpassword');
    }

    public function test_logout_deletes_current_access_token()
    {
        $userRepositoryMock = Mockery::mock(UserRepository::class);
        $goalServiceMock = Mockery::mock(GoalService::class);

        $userMock = Mockery::mock(User::class);
        $tokenMock = Mockery::mock();

        $userMock->shouldReceive('currentAccessToken')->once()->andReturn($tokenMock);
        $tokenMock->shouldReceive('delete')->once();

        $service = new AuthService($userRepositoryMock, $goalServiceMock);

        $service->logout($userMock);

        $this->assertTrue(true); // If no exception thrown, logout success
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
