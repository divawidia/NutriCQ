<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Validation\ValidationException;

class AuthService
{
    private UserRepository $userRepository;
    private GoalService $goalService;
    public function __construct(UserRepository $userRepository, GoalService $goalService) {
        $this->userRepository = $userRepository;
        $this->goalService = $goalService;
    }

    /**
     * Register a new user logic.
     *
     * @param array $data
     * @return User
     */
    public function registerUser(array $data): User
    {
        $data['status'] = 'active';
        $user = $this->userRepository->create($data, 'user');

        // create nutrition goal data when user created
        $goalData = $this->goalService->calculateGoal($data['gender'], $data['tingkat_aktivitas'], $data['berat_badan'], $data['tinggi_badan'], $user->getAgeAttribute());
        $user->goal()->updateOrCreate($goalData);
        $user->goalHistories()->create($goalData);

        return $user;
    }

    /**
     * Register a new user with a specific role and optional file uploads.
     *
     * @param array $data
     * @param string $role
     * @return User
     */
    public function register(array $data, string $role): User
    {
        if (array_key_exists('cv', $data)) {
            $data['cv'] = $data['cv']->store('public/cv');
        }

        if (array_key_exists('license', $data)) {
            $data['license'] = $data['license']->store('public/license');
        }

        $user = $this->userRepository->create($data, 'doctor');


        // create nutrition goal data when user created
        if ($role == 'user') {
            $goalData = $this->goalService->calculateGoal($data['gender'], $data['tingkat_aktivitas'], $data['berat_badan'], $data['tinggi_badan'], $user->getAgeAttribute());
            $user->goal()->updateOrCreate($goalData);
            $user->goalHistories()->create($goalData);
        }

        return $user;
    }

    /**
     * Attempt to login a user and return their data and token.
     *
     * @param string $email
     * @param string $password
     * @return array{user: User, token: string}
     *
     * @throws ValidationException
     */
    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findActiveByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials or account inactive.'],
            ]);
        }

        $token = $user->createToken('nutricqtoken')->plainTextToken;

        return compact('user', 'token');
    }

    /**
     * Logout the currently authenticated user.
     *
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
