<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $primaryKey='store_id';
    protected $guarded=['store_id'];

    public function products()
    {
      return $this->hasMany('App\Models\Product','store_id','store_id');
    }

    public function city()
    {
      return $this->hasOne('App\Models\City','city_id','store_city');
    }
}
