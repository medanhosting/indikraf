<?php

namespace App\Http\Controllers\Auth;

use Cart;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

// Models
use App\Models\Product;
use App\Models\Cart as WebCart;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
     public function showLoginForm()
     {
        $cart=Cart::getContent();
         return view('auth.login',['cart'=>$cart]);
     }

    protected function authenticated($request, $user)
    {
        if(count(Cart::getContent())){
          foreach (Cart::getContent() as $c) {
            $p=Product::find($c->id);
            $check_item=WebCart::where([['product_id',$c->id],['buyer_id',$user->user_id],['status',0]])->first();
            if(count($check_item)==0){
              WebCart::create([
                'buyer_id'=>$user->user_id,
                'product_id'=>$c->id,
                'price'=>$p->price,
                'amount'=>$c->quantity,
                'total_price'=>$c->quantity*$p->price,
              ]);
            }else {
              $k=WebCart::find($check_item->cart_id);
              $k->amount+=$c->quantity;
              $k->total_price=($k->price*$k->amount);
              $k->update();
            }
          }
        }
        return redirect()->intended('/member');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
