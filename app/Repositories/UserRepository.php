<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(
        array $withRelations = [],
        string $search = null,
        string $gender = null,
        int $minHeight = null,
        int $maxHeight = null,
        int $minWeight = null,
        int $maxWeight = null,
        string $activityLevel = null,
        string $status = null,
        int $page = 1,
        int $perPage = 10
    )
    {
        $skip = ($page - 1) * $perPage;
        return $this->user->with($withRelations)
            ->when($search, fn($query) => $query->search($search))
            ->when($gender, fn($query) => $query->where('gender', $gender))
            ->when($minHeight, fn($query) => $query->where('tinggi_badan', '>=',$minHeight))
            ->when($maxHeight, fn($query) => $query->where('tinggi_badan', '<=', $maxHeight))
            ->when($minWeight, fn($query) => $query->where('berat_badan', '>=',$minWeight))
            ->when($maxWeight, fn($query) => $query->where('berat_badan', '<=', $maxWeight))
            ->when($activityLevel, fn($query) => $query->where('tingkat_aktivitas', $activityLevel))
            ->when($status, fn($query) => $query->where('status', $status))
            ->role('user')
            ->paginate($perPage, ['*'], 'page', $page)
            ->get();
    }

    /**
     * Create a new user with the provided data.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data, string $role): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->user->create($data);
        $user->assignRole($role);
        return $user;
    }

    /**
     * Find an active user by their email address.
     *
     * @param string $email
     * @return User|null
     */
    public function findActiveByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->where('status', 'active')->first();
    }
}
