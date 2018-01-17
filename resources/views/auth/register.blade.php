@extends('layouts.layout_indikraf')

@section('content')
    <div class="page-head page-head--nobg">
      <div class="page-head__title">
        <h1>{!! trans('auth/register.title') !!}</h1>
        <p> {!! trans('auth/register.text') !!} <a href="{{url('/login')}}" class="text-primary text-bold">Login.</a></p>
      </div>
    </div>
    <div class="container">
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10">
          <div class="panel panel-default panel--login">
              <form  action="{{route('register')}}" method="post">
                  {{csrf_field()}}
                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                  <input id="first_name" type="text" name="first_name" placeholder="{!! trans('layout_indikraf.first_name') !!}" value="{{ old('first_name') }}" required autofocus>
                  @if ($errors->has('first_name'))
                      <span class="help-block">
                          <strong>{{ $errors->first('first_name') }}</strong>
                      </span>
                  @endif
                </div>
                <div class="form-group">
                  <input id="last_name" type="text" class="form-control" name="last_name" placeholder="{!! trans('layout_indikraf.last_name') !!}" value="{{ old('last_name') }}" required>
                  @if ($errors->has('last_name'))
                      <span class="help-block">
                          <strong>{{ $errors->first('last_name') }}</strong>
                      </span>
                  @endif
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <input id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                  @if ($errors->has('email'))
                      <span class="help-block">
                          <strong>{{ $errors->first('email') }}</strong>
                      </span>
                  @endif
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input id="password" type="password" name="password" placeholder="Password" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <input id="password-confirm" type="password" name="password_confirmation" placeholder="{!! trans('layout_indikraf.c_password') !!}" required>
                </div>
                <div class="form-group">
                    <div id="captcha-2-indikraf"></div>
                </div>
                @if ($errors->has('g-recaptcha-response'))
                  <span class="help-block">
                      <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                  </span>
                @endif
                <div class="form-group">
                  <p>
                    By signing up, you have accepted our <a href="{{url('/faq')}}" class="btn btn-link">Terms of use</a> and <a href="{{url('/faq')}}" class="btn btn-link">Privacy Policy</a>
                  </p>
                </div>
                <br>
                <div class="form-group">
                  <button class="btn btn-default btn-lg btn-flat" id="btn_submit" disabled>{!! trans('layout_indikraf.btn_register') !!}</button>
                </div>
                <br>&nbsp;
                <p class="text-center text-muted">
                  Already have an account? <a href="{{url('login')}}" class="btn btn-link">Sign in</a>
                </p>
              </form>
            </div>
          </div>
        </div>
        <br>
        <br>
      </div>
@endsection
@section('js')
  <script type="text/javascript">
    var verifyCallback = function(response) {
      document.getElementById('btn_submit').disabled=false;
    };
  </script>
  <script type="text/javascript">
    $('#province').on('change',function(e){
      ambil_ajax('provinsi','#city',$(this).val());
    });

    function ambil_ajax(j,k,prov){
      $.ajax({
        type:"get",
        url:"/ambil_lokasi",
        data:{jenis:j,prov:prov},
        success:function(e){
          $(k).html(e);
        },
        error:function(e){}
      });
    }
  </script> --}}
@endsection
