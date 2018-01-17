<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table='city';
    protected $primaryKey='city_id';

    protected $guarded=['city_id'];

    public function province()
    {
        return $this->belongsTo('App\Models\Province','province_id','province_id');
    }
}
