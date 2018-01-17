@extends('layouts.layout_indikraf')

@section('content')
  @if (count($user->address)>0)
    @php
      $address=$user->address[0]->address;
      $postal_code=$user->address[0]->postal_code;
      $mycity=$user->address[0]->city_id;
      $myprovince=App\Models\City::find($mycity)->province->province_id;
      $city=App\Models\City::where('province_id',$myprovince)->get();
      $phone=$user->address[0]->phone;
    @endphp
  @else
    @php
      $address="";
      $postal_code="";
      $myprovince="";
      $phone="";
      $city=[];
    @endphp
  @endif
  <main>
      @include('Member.header_member')
      <div class="section">
        <div class="panel panel-default panel--register">
          <form action="{{url('/member/profile')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="panel__item">
              <h2 class="panel__item__head">{!! trans('member/index.personal_info') !!}</h2>
              <div class="panel__item__body">
                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                   <label for="first_name" class="form-label">{!! trans('member/index.first_name') !!}</label>
                   <input id="first_name" type="text" class="form-field" name="first_name" placeholder="{!! trans('member/index.first_name') !!}" value="{{ $user->profile->first_name }}" required autofocus>
                   @if ($errors->has('first_name'))
                       <span class="help-block">
                           <strong>{{ $errors->first('first_name') }}</strong>
                       </span>
                   @endif
                </div>
                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                   <label for="last_name" class="form-label">{!! trans('member/index.last_name') !!}</label>
                   <input id="last_name" type="text" class="form-field" name="last_name" placeholder="{!! trans('member/index.last_name') !!}" value="{{ $user->profile->last_name }}" required autofocus>
                   @if ($errors->has('last_name'))
                       <span class="help-block">
                           <strong>{{ $errors->first('last_name') }}</strong>
                       </span>
                   @endif
                </div>
                <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                   <label for="gender" class="form-label">{!! trans('member/index.gender') !!}</label>
                   <select class="form-field" id="gender" name="gender">
                     <option>{!! trans('member/index.male') !!}</option>
                     <option>{!! trans('member/index.female') !!}</option>
                   </select>
                   @if ($errors->has('gender'))
                       <span class="help-block">
                           <strong>{{ $errors->first('gender') }}</strong>
                       </span>
                   @endif
                </div>
                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                   <label for="phone" class="form-label">{!! trans('member/index.phone') !!}</label>
                   <input id="phone" type="text" class="form-field" name="phone" placeholder="{!! trans('member/index.phone') !!}" value="{{$errors->has('phone') ? old('phone') : $phone }}" required>
                   @if ($errors->has('phone'))
                       <span class="help-block">
                           <strong>{{ $errors->first('phone') }}</strong>
                       </span>
                   @endif
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                   <label for="email" class="form-label">Email</label>
                   <input id="email" type="email" class="form-field" name="email" placeholder="Email" value="{{ $user->email }}" required readonly>
                   @if ($errors->has('email'))
                       <span class="help-block">
                           <strong>{{ $errors->first('email') }}</strong>
                       </span>
                   @endif
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-field" name="password" placeholder="Password">
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="password-confirm" class="form-label">{!! trans('member/index.c_password') !!}</label>
                    <input id="password-confirm" type="password" class="form-field" name="password_confirmation" placeholder="{!! trans('member/index.c_password') !!}">
                </div>
              </div>
            </div>
            <div class="panel__item">
              <h2 class="panel__item__head">{!! trans('member/index.destination') !!}</h2>
              <div class="panel__item__body">
                <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                   <label for="address" class="form-label">{!! trans('member/index.full_address') !!}</label>
                   <textarea style="height:113px; resize:none" id="address" class="form-field" name="address" placeholder="{!! trans('member/index.full_address') !!}" required>{{ $errors->has('address') ? old('address') : $address }}</textarea>
                   @if ($errors->has('address'))
                       <span class="help-block">
                           <strong>{{ $errors->first('address') }}</strong>
                       </span>
                   @endif
                </div>
                <div class="form-group{{ $errors->has('province') ? ' has-error' : '' }}">
                   <label for="province" class="form-label">{!! trans('member/index.province') !!}</label>
                   <select class="form-field" id="province" name="province">
                     <option disabled selected>{!! trans('member/index.c_province') !!}</option>
                     @foreach ($province as $p)
                       @if ($p['province_id']==$myprovince)
                          <option value="{{$p['province_id']}}" selected>{{$p['province']}}</option>
                       @else
                          <option value="{{$p['province_id']}}">{{$p['province']}}</option>
                       @endif
                     @endforeach
                   </select>
                   @if ($errors->has('province'))
                       <span class="help-block">
                           <strong>{{ $errors->first('province') }}</strong>
                       </span>
                   @endif
                </div>
                <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                   <label for="province" class="form-label">{!! trans('member/index.city') !!}</label>
                   <select class="form-field" id="city" name="city">
                     @foreach ($city as $c)
                       @if ($c->city_id==$mycity)
                          <option value="{{$c->city_id}}" selected>{{$c->city}}</option>
                       @else
                          <option value="{{$c->city_id}}">{{$c->city}}</option>
                       @endif
                     @endforeach
                   </select>
                   @if ($errors->has('city'))
                       <span class="help-block">
                           <strong>{{ $errors->first('city') }}</strong>
                       </span>
                   @endif
                </div>
                <div class="form-group{{ $errors->has('postal_code') ? ' has-error' : '' }}">
                   <label for="postal_code" class="form-label">{!! trans('member/index.postal') !!}</label>
                   <input type="text" class="form-field" name="postal_code" placeholder="{!! trans('member/index.postal') !!}" value="{{$errors->has('postal_code') ? old('postal_code') : $postal_code}}">
                   @if ($errors->has('postal_code'))
                       <span class="help-block">
                           <strong>{{ $errors->first('postal_code') }}</strong>
                       </span>
                   @endif
                </div>
                <div class="form-group{{ $errors->has('profile_image') ? ' has-error' : '' }}">
                   <label for="profile_image" class="form-label">{!! trans('member/index.p_image') !!}</label>
                   <input type="file" class="form-field" name="profile_image">
                   @if ($errors->has('profile_image'))
                       <span class="help-block">
                           <strong>{{ $errors->first('profile_image') }}</strong>
                       </span>
                   @endif
                </div>
              </div>
            </div>
            <div class="col-12">
              <p class="text-center">
                <button type="submit" class="btn btn-primary btn-lg border-radius-lg form-submit">{!! trans('member/index.save') !!}</button>
              </p>
            </div>
          </form>
        </div>
      </div>
      <br>&nbsp;<br>&nbsp;
    </div>
  </main>
@endsection
@section('js')
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
  </script>
@endsection
