<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
  protected $table='province';
  protected $primaryKey='province_id';

  protected $guarded=['province_id'];

  public function city()
  {
      return $this->hasMany('App\Models\Province','province_id','province_id');
  }
}
