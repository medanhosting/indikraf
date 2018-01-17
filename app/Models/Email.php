<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $primaryKey='email_id';
    protected $guarded=['email_id'];
}
