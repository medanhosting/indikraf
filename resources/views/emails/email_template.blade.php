<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Test Blast Email</title>
    <link rel="stylesheet" href='http://indikraf.com/css/app.css'>
  </head>
  <body>
    <div class="container"  style="background:white; margin-top:30px">
      <div class="row" style="margin-top:30px;">
        <div class="col-md-12">
          <img src="{{$message->embed("http://indikraf.com/assets/images/logo_small.png")}}" alt="">
          <i class="fa fa-home"></i>
          <br><br>
          @yield('content')
          <br>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <hr>
          <center>© Indikraf 2017</center>
        </div>
      </div>
    </div>
  </body>
</html>
