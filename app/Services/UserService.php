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

    public function updateAuthUserProfile(array $data, User $user): User
    {
        $goalData = $this->goalService->calculateGoal($data['gender'], $data['tingkat_aktivitas'], $data['berat_badan'], $data['tinggi_badan'], $user->getAgeAttribute());
        $user->update($data);
        $user->goal()->update($goalData);
        $user->goalHistories()->create($goalData);
        return $user;
    }
}
