<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    use HasFactory;

    public $guarded = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'user_id', 'role_id');
    }
}
