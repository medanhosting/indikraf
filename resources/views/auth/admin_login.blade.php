<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link rel='stylesheet' href='{{asset('css/font-awesome.css')}}'>
  <link rel="stylesheet" href="{{asset('adminLogin/css/style.css')}}">
</head>

<body>
  <div class="login-form">
    <img src="{{asset('assets/images/logo_small.png')}}"><br><br>
    <form class="" method="POST" action="{{ route('admin_login') }}">
        {{ csrf_field() }}
       <div class="form-group{{ $errors->has('password') || $errors->has('email') ? ' wrong-entry' : '' }} log-status">
         <input type="text" class="form-control" name="email" placeholder="Email" id="email">
         <i class="fa fa-user"></i>
         @if ($errors->has('email'))
             <span class="alert" style="display:block">
                 {{ $errors->first('email') }}
             </span>
         @endif
       </div>
       <div class="form-group log-status">
         <input type="password" class="form-control" name="password" placeholder="Password" id="password">
         <i class="fa fa-lock"></i>
       </div>
        <span class="alert">Invalid Credentials</span>
        <a class="link" href="{{ route('password.request') }}">Lost your password?</a>
       <button type="submit" class="log-btn" >Log in</button>
    </form>

   </div>
    <script src="{{asset('adminLogin/js/index.js')}}"></script>

</body>
</html>
