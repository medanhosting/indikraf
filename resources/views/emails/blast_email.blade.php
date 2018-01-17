@extends('emails.email_template')
@section('content')
  <p>
    Dear {{$user->name!=NULL?$user->name:'Admin '.$user->profile->first_name}},<br>
    {!!$text!!}
  </p>
@endsection
