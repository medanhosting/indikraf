<?php

namespace App\Http\Middleware;


use Illuminate\Http\Request;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class checkAdmin
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        dd($this->auth->user()->role->role_name);
        if(Auth::user()->role->role_name!="Admin"){
          return redirect('/');
        }else{
          return $next($request);
        }
    }
}
