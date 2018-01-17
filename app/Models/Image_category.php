<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image_category extends Model
{
    protected $primaryKey='image_category_id';
    protected $guarded=['image_category_id'];

    public function images()
    {
      return $this->hasMany('App\Models\Image','image_category_id','image_category_id');
    }
}
