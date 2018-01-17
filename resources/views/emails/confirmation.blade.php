<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
   	<title>Indikraft</title>
      <link rel="stylesheet" type="text/css" href="http://indikraf.com/assets/stylesheets/css/app.css">
   	<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
   	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
   </head>
   <body>
      <div class="confirmation">
         <img src="{{$message->embed('http://indikraf.com/assets/images/logo_small.png')}}" alt="logo">
         <br />
         &nbsp;
         <div class="confirmation-panel">
            <div class="confirmation-panel-head">
               <h3>Selamat Datang di Indikraf!</h3>
            </div>
            <div class="confirmation-panel-body">
               <p>
                  <br />
                  Hai {{$user->profile->first_name}}, terima kasih telah bergabung bersama kami. Tinggal satu langkah lagi untuk untuk bergabung bersama kami, kamu tinggal mengkonfirmasi email milikmu dengan menekan tombol dibawah ini.
                  <br />
                  &nbsp;
                  <center>
                     <a href="{{url('register/confirm/'.$user->email."/".$user->verification_code)}}" class="btn btn-primary btn-lg">Konfirmasi Email Milikmu!</a>
                  </center>
               </p>
            </div>
         </div>
      </div>
   </body>
</html>
