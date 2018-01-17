<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey="role_id";

    protected $guarded=['profile_id'];
}
