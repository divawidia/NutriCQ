<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    protected GoalService $goalService;
    public function __construct(GoalService $goalService)
    {
        $this->goalService = $goalService;
    }

    public function index(string $search, string $gender, int $minHeight, int $maxHeight, int $minWeight, int $maxWeight, string $activityLevel, string $status)
    {

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
