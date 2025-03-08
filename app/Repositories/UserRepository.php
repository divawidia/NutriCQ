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

    /**
     * Create a new user with the provided data.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->user->create($data);
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
