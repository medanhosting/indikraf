<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRequest extends Model
{
    use SoftDeletes;
    protected $table='requests';
    protected $primaryKey='request_id';

    protected $guarded=['request_id'];
    protected $dates = ['deleted_at'];

    public function category(){
      return $this->hasOne('App\Models\Category','category_id','product_category');
    }

    public function product_images(){
      return $this->hasMany('App\Models\Product_image_request','request_id','request_id');
    }

    public function seller(){
      return $this->belongsTo('App\User','user_id','user_id');
    }
}
