<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
class Email_verification extends Controller
{

  function __construct()
  {

  }

  public function send_email_done($email,$verification_code){
    $user=User::where([['email',$email],['verification_code',$verification_code]])->first();

    if($user){
      $redirect_url="/member";
      User::where([['email',$email],['verification_code',$verification_code]])->update(['verified'=>1,'verification_code'=>NULL]);
      $status="Confirmed";
    }else{
      $status="False";
    }

    return view('emails.confirm',['data'=>$user,'status'=>$status,'redirect_url'=>$redirect_url]);
  }
}
?>
