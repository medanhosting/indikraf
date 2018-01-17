<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $primaryKey='image_id';
    protected $guarded=['image_id'];

    public function category(){
      return $this->hasOne('App\Models\Image_category','image_category_id','image_category_id');
    }
}
