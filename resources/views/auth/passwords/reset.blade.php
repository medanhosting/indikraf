@extends('layouts.layout_indikraf')

@section('content')
  <main class="main-section">
    <div class="page-head page-head--nobg">
      <div class="page-head__title">
        <h1>Reset Password</h1>
      </div>
    </div>
    <div class="container">
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10">
          <div class="panel panel-default panel--login">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.request') }}">
              {{ csrf_field() }}
              <input type="hidden" name="token" value="{{ $token }}">
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
                <input id="password" type="password" name="password" placeholder="Password" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
              </div>
              <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                  <label class="form-label">Confirm Password</label>
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Ulangi Password" required>
                  @if ($errors->has('password_confirmation'))
                      <span class="help-block">
                          <strong>{{ $errors->first('password_confirmation') }}</strong>
                      </span>
                  @endif
              </div>
              <div class="form-group">
                <button class="btn btn-default btn-lg btn-flat">Reset Password</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <br>&nbsp;<br>&nbsp;
  </main>

@endsection
