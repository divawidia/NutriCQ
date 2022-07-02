<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;


use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [];
}
