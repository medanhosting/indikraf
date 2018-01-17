<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exception\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use App\Notifications\resetPassword;

// Models
use App\User;
use App\Models\Role;
use App\Models\Profile;
use App\Models\Address;
use App\Models\Password_reset;

//Email
use Mail;
use App\Mail\Confirmation_email;

class AuthController extends Controller
{
    function index(){
      $user=User::all();
      return response()->json($user);
    }
    //Login
    public function authenticate(Request $r){
        $credentials=array(
                      "email"=>$r->email,
                      "password"=>$r->password,
                      "role_id"=>2
                  );
        try {
          if(!$token=JWTAuth::attempt($credentials)){
            return response()->json(['error'=>'User credentials are not correct'],401);
          }
        } catch (JWTException $ex) {
          return response()->json(['error'=>'Something went wrong'],500);
        }

        return response()->json(compact('token'));
    }
    // End Login

    // Register
    function register(Request $r){
        $data=$r->all();
        $validate=$this->validation($data);
        if($validate!="Validation Success"){
           $errors=$this->validation($data);
           return response()->json(['errors'=>$errors],400);
        }else {
          $this->create($data);
        }
        return $this->authenticate($r);
    }

    function validation(array $data){
        $validator = Validator::make($data, [
          'first_name' => 'required|string|max:255',
          'last_name' => 'required|string|max:255',
          'gender' => 'nullable|string',
          'email' => 'required|string|email|max:255|unique:users',
          'phone' => 'nullable|numeric|digits_between:10,20',
          'password' => 'required|string|min:6',
          'address' => 'nullable|min:6',
          'province' => 'nullable|numeric',
          'city' => 'nullable|numeric',
          'postal_code' => 'nullable|numeric|digits_between:5,6'
        ]);

        if ($validator->fails()){
          return $validator->errors();
        }else{
          return "Validation Success";
        }
    }

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

        // Address::create([
        //     'user_id'       =>  $user_id,
        //     'address'       =>  $data['address'],
        //     'city'          =>  $data['city'],
        //     'postal_code'   =>  $data['postal_code'],
        //     'phone'         =>  $data['phone'],
        // ]);

        $thisUser=User::find($user_id);
        $this->sendEmail($thisUser);
        return $thisUser;
    }

    public function sendEmail($thisUser){
      Mail::to($thisUser->email)->queue(new Confirmation_email($thisUser));
    }
    // End Register

    // Refresh Token
    function token(){
      $token=JWTAuth::getToken();

      if(!$token){
        return response()->json(['error'=>"Token is invalid"]);
      }

      try {
        $refreshedToken=JWTAuth::refresh($token);
      } catch (JWTException $e) {
        return response()->json(['error'=>"Something went wrong"]);
      }
      return response()->json(compact('refreshedToken'));
    }
    //End Refresh Token

    // Reset Password
    public function requestPassword(Request $r)
    {
        $user=User::where('email',$r->email)->first();
        if (!$user) {
          return response()->json(['error'=>'user with this credetials not found']);
        }else {
          $token=str_random(4);
          Password_reset::create([
            'email'=>$r->email,
            'token'=>$token
          ]);
          $user->notify(new resetPassword($token));
          return response()->json(['status'=>'email sent','token'=>$token]);
        }
    }

    public function setPassword(Request $r)
    {
        $check=Password_reset::where([['email',$r->email],['token',$r->token]])->first();
        if (!$check) {
            return response()->json(['error'=>'token for reset password is invalid']);
        }else {
            $user=User::where('email',$r->email)->first();
            $user->password=bcrypt($r->password);
            if (!$user->update()) {
              return response()->json(['error'=>'update password failed']);
            }else {
              Password_reset::where([['email',$r->email],['token',$r->token]])->delete();
              return response()->json(compact('user'));
            }
        }
    }
    // End Reset Password
}
