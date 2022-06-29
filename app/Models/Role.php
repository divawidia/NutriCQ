<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    use HasFactory;

    public $guarded = [];

    //ini define fungsi relasi many to many lagi antara users sama roles biar di unit test food diarynya bisa buat factory user sama rolenya untuk authnya juga, tapi masih aman kok fungsinya buat register sama login user seperti biasa
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
}
