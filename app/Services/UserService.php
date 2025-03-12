<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    protected GoalService $goalService;
    protected UserRepository $userRepository;
    public function __construct(GoalService $goalService, UserRepository $userRepository)
    {
        $this->goalService = $goalService;
        $this->userRepository = $userRepository;
    }

    public function index(string $search, string $gender, int $minHeight, int $maxHeight, int $minWeight, int $maxWeight, string $activityLevel, string $status, int $page, int $perPage)
    {
        return $this->userRepository->index([], $search, $gender, $minHeight, $maxHeight, $minWeight, $maxWeight, $activityLevel, $status, $page, $perPage);
    }

    public function updateAuthUserProfile(array $data, User $user): User
    {
        $goalData = $this->goalService->calculateGoal($data['gender'], $data['tingkat_aktivitas'], $data['berat_badan'], $data['tinggi_badan'], $user->getAgeAttribute());
        $user->update($data);
        $user->goal()->updateOrCreate($goalData);
        $user->goalHistories()->create($goalData);
        return $user;
    }
}
