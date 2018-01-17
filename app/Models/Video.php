<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $primaryKey='video_id';
    protected $guarded=['video_id'];

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
}
