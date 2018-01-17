<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $primaryKey="post_id";
    protected $guarded=['post_id'];

    protected $appends=['slug'];

    public function getSlugAttribute()
    {
      $year=$this->created_at->year;
      $month=$this->created_at->month>9?$this->created_at->month:"0".$this->created_at->month;

      return $year."/".$month."/".str_slug($this->title)."-ARMS-999".$this->post_id;
    }

    public function writer(){
      return $this->belongsTo('App\User','writer_id','user_id');
    }

    public function comments()
    {
      return $this->hasMany('App\Models\Comment','post_id','post_id');
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
}
