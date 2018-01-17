<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Styles -->
    <link href="http://indikraf.com/css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="http://indikraf.com/css/font-awesome.css">
</head>
  <body>
    @if ($status=="Confirmed")
      <div class="container">
          <div class="row">
            <div class="alert alert-info">
              Dear {{$data->profile->first_name}}, verifikasi email telah berhasil. Klik <a href="{{url($redirect_url)}}">disini</a> untuk kembali.
            </div>
      	   </div>
      </div>
    @else
      <div class="container">
          <div class="row">
            <div class="alert alert-danger">
              Email Verifikasi gagal
            </div>
      	   </div>
      </div>
    @endif
  </body>
</html>
