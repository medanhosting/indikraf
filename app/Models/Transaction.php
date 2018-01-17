<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $primaryKey='transaction_id';
    protected $guarded=['transaction_id'];


    public function cart(){
      return $this->hasMany('App\Models\Cart','transaction_id','transaction_id');
    }

    public function buyer(){
      return $this->belongsTo('App\User','buyer_id','user_id');
    }

    public function shipping_address(){
      return $this->hasOne('App\Models\Shipping_address','order_id','order_id');
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
