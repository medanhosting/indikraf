<div class="page-head">
  <div class="page-head__user">
    <div class="profile">
      <div class="profile__image">
        @if ($user->profile->profile_image)
          <img src="{{asset('uploads/foto_profil/'.$user->profile->profile_image)}}" class="profile__image">
        @else
          <img src="{{asset('assets/images/Group 3.png')}}">
        @endif
      </div>
      <div class="profile__attributes">
        <p class="text-left text-bold text-bigger">{{$user->profile->first_name." ".$user->profile->last_name}}</p>
        <p class="text-left">{{$user->email}}</p>
      </div>
    </div>
  </div>
</div>
<div class="container container--gray">
  <div class="section">
    <div class="col-12">
      <ul class="nav-tabs">
        <li class='{{url()->current()==url('/member')?"active":''}}'><a href="{{url('/member')}}">{!! trans('layout_indikraf.profile') !!}</a></li>
        <li class='{{url()->current()==url('/member/transaction') || Request::segment(1)."/".Request::segment(2)=='member/transaction_detail'?"active":''}}'><a href="{{url('/member/transaction')}}">{!! trans('layout_indikraf.t_history') !!}</a></li>
        <li class='{{url()->current()==url('/member/review')?"active":''}}'><a href="{{url('/member/review')}}">{!! trans('layout_indikraf.review') !!}</a></li>
      </ul>
    </div>
  </div>
