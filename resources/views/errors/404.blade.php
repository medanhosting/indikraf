<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Indikraft</title>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/stylesheets/css/normalize.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/font-awesome/css/font-awesome.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/stylesheets/css/app.css')}}">
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
</head>
<body>
	<div class="loading"></div>
	<div class="site-page">
		<div class="error-box">
         <section class="error-box-main">
            <h1 class="error-box-main-title">404</h1>
            <h2 class="error-box-main-sub_title">Laman tidak ditemukan !</h2>
            <p class="error-box-main-paragraft">Silakan kembali ke halaman beranda</p>
            <a href="{{url('/')}}" class="btn btn-primary error-box-main-back_button">Beranda</a>
         </section>
         <footer class="error-box-footer">
            2017 &copy; Indikraf
         </footer>
		</div>
	</div>
	<script src="{{asset('assets/javascripts/jquery-3.2.1.min.js')}}"></script>
	<script src="{{asset('assets/plugins/slick/slick.min.js')}}"></script>
	<script src="{{asset('assets/javascripts/modal.js')}}"></script>
	<script src="{{asset('assets/javascripts/app.js')}}"></script>
</body>
</html>
