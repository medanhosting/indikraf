<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $primaryKey='cart_id';
    protected $guarded=['cart_id'];

    public function user(){
      return $this->belongsTo('App\User','buyer_id','user_id');
    }

    public function product(){
      if ($this->status==0) {
        return $this->hasOne('App\Models\Product','product_id','product_id');
      }else {
        return $this->hasOne('App\Models\Product','product_id','product_id')->withTrashed();
      }
    }

    public function transaction(){
      return $this->hasOne('App\Models\Transaction','transaction_id','transaction_id');
    }

    public function date_format(){
      $date=$this->created_at;
      $month_list=array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
      $dates=explode(' ',$date);

      $date=explode('-',$dates[0]);

      $day=$date[2];
      $month=$month_list[intval($date[1]-1)];
      $year=$date[0];

      return $day." ".$month." ".$year;
    }

    public function time_format(){
      $date=$this->created_at;
      $dates=explode(' ',$date);
      return $time=$dates[1];
    }

    public function short_date_format(){
      $date=$this->created_at;
      $month_list=array('Jan','Feb','Mar','Apr','Mei','Juni','Juli','Agus','Sep','Okt','Nov','Des');
      $dates=explode(' ',$date);

      $date=explode('-',$dates[0]);

      $day=$date[2];
      $month=$month_list[intval($date[1]-1)];
      $year=$date[0];

      return $day." ".$month." ".$year;
    }
}
