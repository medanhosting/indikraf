<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $primaryKey='rating_id';
    protected $guarded=['rating_id'];

    public function user()
    {
      return $this->belongsTo('App\User','user_id','user_id');
    }
}
