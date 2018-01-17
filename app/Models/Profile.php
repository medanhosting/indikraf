<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $primaryKey='profile_id';

    protected $guarded=['profile_id'];
}
