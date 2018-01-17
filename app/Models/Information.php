<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $table='informations';
    protected $primaryKey='information_id';
    protected $guarded=['information_id'];
}
