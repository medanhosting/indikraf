@extends('layouts.layout_indikraf')

@section('content')
    <div class="page-head page-head--nobg page-head--show">
      <div class="page-head__title">
        <h1>Login Member</h1>
        <p>{!! trans('auth/login.text') !!} <a href="{{url('/register')}}" class="text-primary text-bold">{!! trans('auth/login.register_link') !!}</a></p>
      </div>
    </div>
    <div class="container">
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10">
          <div class="panel panel-default panel--login">
            <form method="POST" action="{{ route('login') }}">
              {{ csrf_field() }}
              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email" class="form-label">E-Mail Address</label>

                  <input id="email" type="email" class="form-field" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>

                  @if ($errors->has('email'))
                      <span class="help-block">
                          <strong>{{ $errors->first('email') }}</strong>
                      </span>
                  @endif
              </div>
              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="form-label">Password</label>
                <input type="password" name="password" placeholder="Password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
              </div>
              <div class="form-group">
                <a href="{{ route('password.request') }}">{!! trans('auth/login.forgot_password') !!}</a>
              </div>
              <div class="form-group">
                <button class="btn btn-default btn-lg btn-flat">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <br>
    <br>
@endsection
