<?php

namespace App\Http\Controllers\Auth;

use Session;
use Cart;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\NewUserRegistration;

// Email Confirmation
use Mail;
use App\Mail\Confirmation_email;

// Models
use App\User;
use App\Models\Role;
use App\Models\Profile;
use App\Models\Address;
use App\Models\Product;
use App\Models\Cart as WebCart;
use App\Models\Email;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    public function showRegistrationForm()
    {
        $cart=Cart::getContent();
        return view('auth.register',['cart'=>$cart]);
    }

    protected function registered(Request $request, $user)
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
            $k->update();
          }
        }
      }

      Email::create([
        'name'=>$user->profile->first_name." ".$user->profile->last_name,
        'email'=>$user->email,
      ]);

      $e_m=User::where('role_id','1')->get();
      foreach ($e_m as $u) {
        $u->notify(new NewUserRegistration($user));
      }
    }
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/member';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware(function($request,$next){
          app()->setLocale(Session::get('locale'));
          return $next($request);
        });
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
          'first_name' => 'required|string|max:255',
          'last_name' => 'required|string|max:255',
          'gender' => 'nullable|string',
          'email' => 'required|string|email|max:255|unique:users',
          'phone' => 'nullable|numeric|digits_between:10,20',
          'password' => 'required|string|min:6|confirmed',
          'address' => 'nullable|min:6',
          'province' => 'nullable|numeric',
          'city' => 'nullable|numeric',
          'postal_code' => 'nullable|numeric|digits_between:5,6',
          'g-recaptcha-response' => 'required'
        ]);
    }

    public function sendEmail($thisUser){
      Mail::to($thisUser->email)->queue(new Confirmation_email($thisUser));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $role=Role::where('role_name','Member')->first();
        $role=$role->role_id;
        $user=User::create([
            'role_id'           => $role,
            'email'             => $data['email'],
            'password'          => bcrypt($data['password']),
            'verification_code' => Str::random(40)
        ]);
        $user_id=$user->user_id;

        Profile::create([
            'user_id'   =>  $user_id,
            'first_name'=>  $data['first_name'],
            'last_name' =>  $data['last_name'],
        ]);


        $thisUser=User::find($user_id);
        $this->sendEmail($thisUser);
        return $thisUser;
    }
}
