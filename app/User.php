<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey="user_id";

    protected $guarded = ['user_id'];

    protected $hidden = [
        'verified','verification_code','password', 'remember_token',
    ];

    public function products(){
      return $this->hasMany('App\Models\Product','seller_id','user_id');
    }

    public function articles(){
      return $this->hasMany('App\Models\Post','writer_id','user_id');
    }

    public function role(){
      return $this->hasOne('App\Models\Role','role_id','role_id');
    }

    public function profile(){
      return $this->hasOne('App\Models\Profile','user_id','user_id');
    }

    public function address(){
      return $this->hasMany('App\Models\Address','user_id','user_id');
    }

    public function cart(){
      return $this->hasMany('App\Models\Cart','buyer_id','user_id');
    }
}
