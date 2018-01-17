<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                      <li><a href="{{url('/')}}">Beranda</a></li>
                        <li><a href="{{url('/products')}}">Produk</a></li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (!(Request::url()==route('login') OR Request::url()==route('register') OR Request::url()==route('password.request')))
                          <li><a href="/shopping_cart"> <big><i class="fa fa-shopping-cart"></i></big> {{count($cart)}}</a></li>
                        @endif
                        @if (Auth::guest())
                            <li><a href="{{ route('register') }}">Register</a></li>
                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
                          			<ul id="login-dp" class="dropdown-menu" style="width:300px">
                                  <br>
                          				<li>
                          					 <div class="row">
                          							<div class="col-md-10 col-md-offset-1">
                          								 <form class="form" role="form" method="post" action="{{ route('login') }}" accept-charset="UTF-8" id="login-nav">
                                              {{csrf_field()}}
                          										<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                          											 <label for="email" class="control-label">E-Mail Address</label>
                          											 <input id="email" type="email" class="form-control" name="email" placeholder="E-Mail Address" value="{{ old('email') }}" required autofocus>
                                                 @if ($errors->has('email'))
                                                     <span class="help-block">
                                                         <strong>{{ $errors->first('email') }}</strong>
                                                     </span>
                                                 @endif
                          										</div>
                          										<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                          											 <label for="password" class="control-label">Password</label>
                          											 <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                                                 @if ($errors->has('password'))
                                                     <span class="help-block">
                                                         <strong>{{ $errors->first('password') }}</strong>
                                                     </span>
                                                 @endif
                          										</div>
                                              <div class="checkbox">
                          											 <label>
                          											 <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                          											 </label>
                          										</div>
                          										<div class="form-group">
                          											 <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                          										</div>
                                              <div class="help-block text-right"><a href="{{ route('password.request') }}">Forgot Your Password?</a></div>
                          								 </form>
                          							</div>
                          					 </div>
                                     <div class="row">
                                       <div class="col-md-10 col-md-offset-1">
                                         <div class="bottom text-center">
                                           New here ? <a href="{{url('/register')}}"><b>Join Us</b></a>
                                         </div>
                                       </div>
                                     </div>
                          				</li>
                          			</ul>
                            </li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ $user->profile->first_name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                      <a href="{{url('/member')}}">
                                        <i class="fa fa-home"></i> Home
                                      </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <i class="fa fa-power-off"></i> Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>
</body>
</html>
