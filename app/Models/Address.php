<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $primaryKey="address_id";

    protected $guarded=['address_id'];

    public function user(){
      return $this->belongsTo('App\User','user_id','user_id');
    }

    public function city()
    {
      return $this->hasOne('App\Models\City','city_id','city_id');
    }
}
