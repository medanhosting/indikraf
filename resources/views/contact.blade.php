@extends('layouts.layout_indikraf')
@section('content')
	<div class="container container--slider">
	<div class="page-head">
		<div class="page-head__title">
			<h1>{!! trans('front/contact.title') !!}</h1>
		</div>
	</div>
</div>
	 <div class="container">
			<div class="section">
				 <div class="col-1 hide-sm"></div>
				 <div class="col-5">
						<div class="gutter">
							 <h2 class="text-primary">{!! trans('front/contact.send_message') !!}</h2>
							 <form class="form-blue" method="post" action="{{url('/send_message')}}">
								 	{{ csrf_field() }}
									<div class="form-group">
										 <input type="text" name="name" placeholder="{!! trans('front/contact.name') !!}" required>
									</div>
									<div class="form-group">
										 <input type="email" name="email" placeholder="Email" required>
									</div>
									<div class="form-group">
										 <input type="text" name="title" placeholder="{!! trans('front/contact.title_hint') !!}" required>
									</div>
									<div class="form-group">
										 <textarea name="message" placeholder="message" rows="5" style="resize:none" required></textarea>
									</div>
									<div class="form-group">
										 <div id="captcha-2-indikraf"></div>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary btn-block btn-lg" id="btn_submit" disabled>{!! trans('front/contact.btn_send') !!}</button>
									</div>
							 </form>
						</div>
				 </div>
				 <div class="col-5">
						<div class="gutter">
							 <h2 class="text-primary">{!! trans('front/contact.contact_us') !!}</h2>
							 <ul class="contact">
									<li class="contact-list">
										 <div class="contact-list-icon">
												<i class="fa fa-clock-o"></i>
										 </div>
										 <div class="contact-list-detail">
												<p>08:00 - 17:00 WIB ({!! trans('front/contact.working_day') !!})</p>
												<p>09:00 - 15:00 WIB (Weekend)</p>
										 </div>
									</li>
									<li class="contact-list">
										 <div class="contact-list-icon">
												<i class="fa fa-phone"></i>
										 </div>
										 <div class="contact-list-detail">
												<p>+(62)22-1234-5678</p>
										 </div>
									</li>
									<li class="contact-list">
										 <div class="contact-list-icon">
												<i class="fa fa-envelope"></i>
										 </div>
										 <div class="contact-list-detail">
												<p>admin@indikraf.com</p>
										 </div>
									</li>
									<li class="contact-list">
										 <div class="contact-list-icon">
												<i class="fa fa-map-marker"></i>
										 </div>
										 <div class="contact-list-detail">
												<p>Jl. Guntur Sari II No. 37 Bandung 40264</p>
										 </div>
									</li>
							 </ul>
						</div>
				 </div>
			</div>
			<div class="section">
				<div class="col-1 hide-sm"></div>
			 	<div class="col-10">
						<div class="maps" id="map"></div>
				 </div>
			</div>
			<br>
	 </div>
@endsection
@section('js')
	<script>
		 function initMap() {
				var map = new google.maps.Map(document.getElementById('map'), {
					 center: {lat: -6.945758, lng: 107.632644},
					 zoom: 18
				});

				var infowindow = new google.maps.InfoWindow();
				var service = new google.maps.places.PlacesService(map);

				service.getDetails({
					 placeId: 'ChIJI8PjgcPnaC4RS255yfZgUV8'
				}, function(place, status) {
					 if (status === google.maps.places.PlacesServiceStatus.OK) {
							var marker = new google.maps.Marker({
								 map: map,
								 position: place.geometry.location
							});
					 }
				});
		 }
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDS7DHEicI_tJXE9foPnR3uHqI2T7C8_f0&callback=initMap&libraries=places"></script>

  <script type="text/javascript">
    var verifyCallback = function(response) {
      document.getElementById('btn_submit').disabled=false;
    };
  </script>
@endsection
