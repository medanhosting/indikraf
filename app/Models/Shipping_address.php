<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping_address extends Model
{
    protected $primaryKey='shipping_id';
    protected $guarded=['shipping_id'];

    public function address(){
      return $this->hasOne('App\Models\Address','address_id','address_id');
    }
}
