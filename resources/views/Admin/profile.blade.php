@extends('layouts.layout_admin_lte')

@section('content_header','Profil')

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

@section('content')
<div class="col-md-12">
  <div class="row">
    <div class="col-md-3">

      <!-- Profile Image -->
      <div class="box box-success">
        <div class="box-body box-profile">
          <img class="profile-user-img img-responsive img-circle" src="{{asset('uploads/foto_profil/'.$user->profile->profile_image)}}" style="height:95px" alt="User profile picture">

          <h3 class="profile-username text-center">{{$user->profile->first_name}} {{$user->profile->last_name}}</h3>

          <p class="text-muted text-center">Admin</p>

          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Email</b> <a class="pull-right">{{$user->email}}</a>
            </li>
            <li class="list-group-item">
              <b>Telepon</b> <a class="pull-right">{{$user->address[0]->phone}}</a>
            </li>
          </ul>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

      <!-- About Me Box -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">About Me</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <strong><i class="fa fa-book margin-r-5"></i> Produk</strong>

          <p class="text-muted">
            {{count($user->products)}}
          </p>

          <hr>

          <strong><i class="fa fa-map-marker margin-r-5"></i> Alamat</strong>

          <p class="text-muted">
            @php
              $ris=$user->address[0];
              $ma=$ris->city;
            @endphp
            {{$ris->address}}.<br>
            {{$ma->city}},<br>
            {{$ma->province->province}}. {{$ris->postal_code}}
          </p>

          <hr>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="settings">
            @if (Session::has('status_profile'))
              <div class="alert alert-info alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_profile')}}
              </div>
            @endif
            <form class="form-horizontal" method="post" action="{{url('/admin/profile')}}" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                 <label for="first_name" class="col-sm-2 control-label">Nama Depan</label>

                 <div class="col-sm-10">
                   <input id="first_name" type="text" class="form-control" name="first_name" placeholder="Nama Depan" value="{{ $user->profile->first_name }}" required autofocus>
                   @if ($errors->has('first_name'))
                       <span class="help-block">
                           <strong>{{ $errors->first('first_name') }}</strong>
                       </span>
                   @endif
                 </div>
              </div>
              <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                 <label for="last_name" class="col-sm-2 control-label">Nama Belakang</label>

                 <div class="col-sm-10">
                   <input id="last_name" type="text" class="form-control" name="last_name" placeholder="Nama Belakang" value="{{ $user->profile->last_name }}" required autofocus>
                   @if ($errors->has('last_name'))
                       <span class="help-block">
                           <strong>{{ $errors->first('last_name') }}</strong>
                       </span>
                   @endif
                 </div>
              </div>
              <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                 <label for="gender" class="col-sm-2 control-label">Jenis Kelamin</label>

                 <div class="col-sm-10">
                   <select class="form-control" id="gender" name="gender">
                     <option>Laki-laki</option>
                     <option>Perempuan</option>
                   </select>
                   @if ($errors->has('gender'))
                       <span class="help-block">
                           <strong>{{ $errors->first('gender') }}</strong>
                       </span>
                   @endif
                 </div>
              </div>
              <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                 <label for="phone" class="col-sm-2 control-label">Nomor Telepon</label>

                 <div class="col-sm-10">
                   <input id="phone" type="text" class="form-control" name="phone" placeholder="Nomor Telepon" value="{{ $phone }}" required>
                   @if ($errors->has('phone'))
                       <span class="help-block">
                           <strong>{{ $errors->first('phone') }}</strong>
                       </span>
                   @endif
                 </div>
              </div>
              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                 <label for="email" class="col-sm-2 control-label">Email</label>

                 <div class="col-sm-10">
                   <input id="email" type="email" class="form-control" name="email" placeholder="Email" value="{{ $user->email }}" readonly>
                   @if ($errors->has('email'))
                       <span class="help-block">
                           <strong>{{ $errors->first('email') }}</strong>
                       </span>
                   @endif
                 </div>
              </div>
              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                  <label for="password" class="col-sm-2 control-label">Password</label>
                  <div class="col-sm-10">
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password">
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                  </div>
              </div>
              <div class="form-group">
                  <label for="password-confirm" class="col-sm-2 control-label">Ulangi Password</label>
                  <div class="col-sm-10">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Ketik ulang password">
                  </div>
              </div>
              <hr>
              <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                 <label for="address" class="col-sm-2 control-label">Alamat Lengkap</label>

                 <div class="col-sm-10">
                   <textarea style="height:113px; resize:none" id="address" class="form-control" name="address" placeholder="Alamat Lengkap" required>{{ $address }}</textarea>
                   @if ($errors->has('address'))
                       <span class="help-block">
                           <strong>{{ $errors->first('address') }}</strong>
                       </span>
                   @endif
                 </div>
              </div>

              <div class="form-group{{ $errors->has('province') ? ' has-error' : '' }}">
                <label for="inputName" class="col-sm-2 control-label">Provinsi</label>

                <div class="col-sm-10">
                  <select class="form-control" id="province" name="province">
                    <option disabled selected>Pilih Provinsi</option>
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
              </div>

              <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                 <label for="province" class="col-sm-2 control-label">Kota</label>

                 <div class="col-sm-10">
                   <select class="form-control" id="city" name="city">
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
              </div>

              <div class="form-group{{ $errors->has('postal_code') ? ' has-error' : '' }}">
                 <label for="postal_code" class="col-sm-2 control-label">Kode Pos</label>

                 <div class="col-sm-10">
                   <input type="text" class="form-control" name="postal_code" placeholder="Kode Pos" value="{{$postal_code}}">
                   @if ($errors->has('postal_code'))
                       <span class="help-block">
                           <strong>{{ $errors->first('postal_code') }}</strong>
                       </span>
                   @endif
                 </div>
              </div>

              <div class="form-group{{ $errors->has('profile_image') ? ' has-error' : '' }}">
                 <label for="profile_image" class="col-sm-2 control-label">Foto Profil</label>

                 <div class="col-sm-10">
                   <input type="file" class="form-control" name="profile_image">
                   @if ($errors->has('profile_image'))
                       <span class="help-block">
                           <strong>{{ $errors->first('profile_image') }}</strong>
                       </span>
                   @endif
                 </div>
              </div>

              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-danger pull-right">Submit</button>
                </div>
              </div>
            </form>
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
</div>
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
