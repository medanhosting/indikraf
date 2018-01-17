<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Rating;

class Product extends Model
{
    use SoftDeletes;
    protected $primaryKey="product_id";
    protected $guarded=['product_id'];

    protected $appends=['slug'];

    public function getSlugAttribute()
    {
      return str_slug($this->product_name)."-999".$this->product_id;
    }

    public function category(){
      return $this->hasOne('App\Models\Category','category_id','category_id');
    }

    public function product_images(){
      return $this->hasMany('App\Models\Product_image','product_id','product_id');
    }

    public function store()
    {
      return $this->belongsTo('App\Models\Store','store_id','store_id');
    }

    public function seller(){
      return $this->belongsTo('App\User','seller_id','user_id');
    }

    public function cart(){
      return $this->hasMany('App\Models\Cart','product_id','product_id');
    }

    public function rating(){
      $rating=Rating::where('product_id',$this->product_id)->get();
      $salim=0;
      $arizi=0;
      if(count($rating)!=0){
        foreach ($rating as $r) {
          $salim++;
          $arizi+=$r->rating;
        }

        return ceil($arizi/$salim);
      }else {
        return 0;
      }
    }

    public function my_rating($user_id)
    {
      $my_rating=Rating::where([['product_id',$this->product_id],['user_id',$user_id]])->first();
      if (count($my_rating)!=0){
        return $my_rating;
      }else {
        return 0;
      }
    }

    public function review()
    {
      return $this->hasMany('App\Models\Rating','product_id','product_id');
    }
}
