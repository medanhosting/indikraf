<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $primaryKey='message_id';
    protected $guarded=['message_id'];


    public function time_format(){
      $date=$this->created_at;
      $dates=explode(' ',$date);
      $time=explode(':',$dates[1]);
      return $time[0].":".$time[1];
    }

    public function date_format(){
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
