<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title','Indikraf')</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/slick/slick.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/slick/slick-theme.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/lightbox2/css/lightbox.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/noUiSlider/nouislider.min.css')}}">
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700|Montserrat:400,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/featherlight/release/featherlight.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/stylesheets/css/app.css') }}">
	<script src="{{asset('assets/javascripts/jquery-3.2.1.min.js')}}"></script>
	<script src="{{asset('assets/plugins/slick/slick.min.js')}}"></script>
	<script src="{{asset('assets/plugins/smoothState.js/src/jquery.smoothState.js')}}"></script>
	<script src="{{asset('assets/plugins/noUiSlider/nouislider.min.js')}}"></script>
	<script src="{{asset('assets/plugins/featherlight/release/featherlight.min.js')}}"></script>
	<meta name="keywords" content="@yield('keywords','Toko online indikraf')" />
	<meta name="description" content="@yield('description')" />
	@yield('css')
</head>
<body>
	<div class="loading"></div>
	<div class="progress progress-load">
		<div class="progress-bar"  id="loadState"></div>
	</div>
	<aside class="sidenav" id="nav">
		<button class="btn-lang" onclick="language_control('id')"><img src="{{asset('assets/images/flag-id.png')}}"></button>
		<button class="btn-lang" onclick="language_control('en')"><img src="{{asset('assets/images/flag-en.png')}}"></button>
		<div class="nav-close-wrap">
			<button class="btn btn-nav-close" id="nav-close">
				<i class="fa fa-close"></i>
			</button>
		</div>
		<!-- Show if logged in -->
		@if (Auth::user())
		<div class="sidenav-profile">
			<h4 class="sidenav-profile-name">{{$user->profile->first_name." ".$user->profile->last_name}}</h4>
			<p class="sidenav-profile-email">{{$user->email}}</p>
		</div>
		@endif
		<ul>
			<li class="active"><a href="{{url('/')}}">{{trans('layout_indikraf.home')}}</a></li>
			<li><a href="{{url('/products')}}">{{trans('layout_indikraf.products')}}</a></li>
			<li><a href="{{url('/articles')}}">{{trans('layout_indikraf.articles')}}</a></li>
			<li><a href="{{url('/gallery')}}">{{trans('layout_indikraf.gallery')}}</a></li>
			<li><a href="{{url('/video')}}">{{trans('layout_indikraf.videos')}}</a></li>
			<li><a href="{{url('/faq')}}">{{trans('layout_indikraf.faq')}}</a></li>
			<li><a href="{{url('/about')}}">{{trans('layout_indikraf.about')}}</a></li>
			<li><a href="{{url('/contact')}}">{{trans('layout_indikraf.contact')}}</a></li>
			@if (Auth::guest())
				<li><a href="{{url('/login')}}">Login/Register</a></li>
			@else
				<li>
					<a href="{{ route('logout') }}" class="no-smoothstate"
							onclick="event.preventDefault();
											 document.getElementById('logout-form').submit();">
							<i class="fa fa-power-off"></i> Logout
					</a>
				</li>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
				</form>
			@endif
		</ul>
	</aside>
	<div class="site-page" id="main">
		<div class="container container--contact">
	    <div class="section section--contact">
	      <p class="section--contact__email"><i class="fa fa-envelope"></i> customer@indikraf.com</p>
				<p class="section--contact__phone"><i class="fa fa-phone"></i> 022 858585</p>
				<button class="btn-lang" onclick="language_control('id')"><img src="{{asset('assets/images/flag-id.png')}}"></button>
				<button class="btn-lang" onclick="language_control('en')"><img src="{{asset('assets/images/flag-en.png')}}"></button>
				<form id="language-form" action="{{url('language-chooser')}}" method="post">
					{{ csrf_field() }}
					<input type="hidden" name="lang">
				</form>
	    </div>
	  </div>
		<header class="header">
			<div class="container container--menu">
				<div class="section section--menu">
					<div class="section--menu__logo">
						<a href="{{url('/')}}"><img src="{{asset('assets/images/logo_small.png')}}"></a>
					</div>
					<div class="section--menu__toggle">
						<a href="#" class="section--menu__toggle__btn" id="toggler">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
					</div>
					<div class="section--menu__cart">
						<a href="{{url('/shopping_cart')}}" class="section--menu__cart__btn">
							<span class="icon">
								<img src="{{asset('assets/images/Forma 1.png')}}">
							</span>
							<span class="cart-quantity">{{count($cart)}}</span>
						</a>
						<a href="#" class="section--menu__cart__btn search-toggle" id="search">
							<span class="icon icon--costume">
								<i class="fa fa-search"></i>
							</span>
						</a>
						<div class="clearfix"></div>
					</div>
					<div class="section--menu__tools">
						@if (Auth::guest())
	            <button class="btn btn-less" id="register">Register</button>
	            <button class="btn btn-opaque" id="login">Login</button>
	          @else
	            <div class="section--menu__user">
	              <div class="dropdown">
	                <a href="#" class="btn btn-opaque dropdown-button"><i class="fa fa-user"></i> {{$user->profile->first_name." ".$user->profile->last_name}}</a>
	                <ul class="dropdown-list">
	                  <li><a href="{{url('/member')}}">{!! trans('layout_indikraf.profile') !!}</a></li>
	                  <li><a href="{{url('/member/transaction')}}">{!! trans('layout_indikraf.t_history') !!}</a></li>
	                  <li><a href="{{url('/member/review')}}">{!! trans('layout_indikraf.review') !!}</a></li>
	                  <li class="separator"></li>
	                  <li><a href="{{ route('logout') }}" class="no-smoothstate" onclick="event.preventDefault();
	                           document.getElementById('logout-form').submit();">Logout</a>
	                  </li>
	                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
	                      {{ csrf_field() }}
	                  </form>
	                </ul>
	              </div>
	            </div>
	          @endif
					</div>

					<form class="section--menu__search" method="get" action="{{url('/search')}}">
						<div class="section--menu__search__input input-group">
							<input type="search" name="s" placeholder="{{trans('layout_indikraf.search_hint')}} .." value="{{app('request')->input('s')}}">
							<div class="input-group__addon">
								<button class="btn btn-default">
									<img src="{{asset('assets/images/search.png')}}">
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="container container--nav">
				<div class="section section--nav">
					<nav class="section--nav__bar" role="navigation">
						<ul>
							<li><a href="{{url('/')}}">{{trans('layout_indikraf.home')}}</a></li>
		          <li><a href="{{url('/products')}}">{{trans('layout_indikraf.products')}}</a></li>
		          <li><a href="{{url('/articles')}}">{{trans('layout_indikraf.articles')}}</a></li>
		          <li><a href="{{url('/gallery')}}">{{trans('layout_indikraf.gallery')}}</a></li>
		          <li><a href="{{url('/video')}}">{{trans('layout_indikraf.videos')}}</a></li>
		          <li><a href="{{url('/faq')}}">{{trans('layout_indikraf.faq')}}</a></li>
		          <li><a href="{{url('/about')}}">{{trans('layout_indikraf.about')}}</a></li>
		          <li><a href="{{url('/contact')}}">{{trans('layout_indikraf.contact')}}</a></li>
						</ul>
					</nav>
				</div>
			</div>
			<div class="container container--search">
				<form class="section--nav__search" method="get" action="{{url('/search')}}">
					<div class="section--nav__search__input input-group">
						<input type="search" name="s" placeholder="{{trans('layout_indikraf.search_hint')}} .." value="{{app('request')->input('s')}}">
						<div class="input-group__addon">
							<button class="btn btn-default">
								<img src="{{asset('assets/images/search.png')}}">
							</button>
						</div>
					</div>
				</form>
			</div>
		</header>
		<div class="modal-wrapper">
			<div class="modal animate {{Session::has('error_modal_login')?'modal-active':''}}" id="modal_login">
				<div class="modal-content">
					<div class="modal-box modal--auth">
						<div class="modal-box__head">
							<h3 class="modal-box__head__title">Sign In</h3>
							<button class="btn btn-close modal-box__head__close"><i class="fa fa-close"></i></button>
						</div>
						<div class="modal-box__body">
							<div class="col-1 hide-sm"></div>
							<div class="col-10">
								<form class="form-blue" method="post" action="{{ route('login') }}" accept-charset="UTF-8" id="login-nav">
			            {{csrf_field()}}
			            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
			              <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
			              @if ($errors->has('email'))
			                  <span class="help-block">
			                      <strong>{{ $errors->first('email') }}</strong>
			                  </span>
			              @endif
			            </div>
			            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
			              <input type="password" name="password" placeholder="Password" required>
			              @if ($errors->has('password'))
			                  <span class="help-block">
			                      <strong>{{ $errors->first('password') }}</strong>
			                  </span>
			              @endif
			            </div>
			            <div class="form-group">
			              <a class="btn btn-link" href="{{ route('password.request') }}">Forgot your password?</a>
			            </div>
			            <div class="form-group">
			              <button class="btn btn-default btn-lg btn-flat">Sign In</button>
			            </div>
			            <br>&nbsp;
			            <p class="text-center text-muted">
			              Not a member yet? <a href="{{route('register')}}" class="btn btn-link">Sign up.</a>
			            </p>
			          </form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal animate {{Session::has('error_modal_register')?'modal-active':''}}" id="modal_register">
				<div class="modal-content">
					<div class="modal-box modal--auth">
						<div class="modal-box__head">
							<h3 class="modal-box__head__title">Register</h3>
							<button class="btn btn-close modal-box__head__close"><i class="fa fa-close"></i></button>
						</div>
						<div class="modal-box__body">
							<div class="col-1 hide-sm"></div>
							<div class="col-10">
								<form class="form-blue" action="{{route('register')}}" method="post">
			              {{csrf_field()}}
			            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
			              <input id="first_name" type="text" name="first_name" placeholder="{{trans('layout_indikraf.first_name')}}" value="{{ old('first_name') }}" required autofocus>
			              @if ($errors->has('first_name'))
			                  <span class="help-block">
			                      <strong>{{ $errors->first('first_name') }}</strong>
			                  </span>
			              @endif
			            </div>
			            <div class="form-group">
			              <input id="last_name" type="text" class="form-control" name="last_name" placeholder="{{trans('layout_indikraf.last_name')}}" value="{{ old('last_name') }}" required>
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
			                <input id="password-confirm" type="password" name="password_confirmation" placeholder="{{trans('layout_indikraf.c_password')}}" required>
			            </div>
									<div class="form-group">
									    <div class="g-recaptcha" id="register-captcha"></div>
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
			              <button class="btn btn-default btn-lg btn-flat" id="btnRegister" disabled>{!! trans('layout_indikraf.btn_register') !!}!</button>
			            </div>
			            <br>&nbsp;
			            <p class="text-center text-muted">
			              Already have an account? <a href="{{url('login')}}" class="btn btn-link">Sign in</a>
			            </p>
			          </form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal animate" id="history_detail">
        <div class="modal-content modal--large">
          <div class="modal-box">
						<div class="modal-box__loading"></div>
						<div class="modal-box__error">
							<button class="btn btn-close modal-box__head__close"><i class="fa fa-close"></i></button>
							<h1 class="modal-box__error__text">Request Timeout</h1>
						</div>
            <div class="modal-box__head">
              <h3 class="modal-box__head__title">Detail Transaksi <span id="order_id">MMTR002555</span></h3>
              <button class="btn btn-close modal-box__head__close"><i class="fa fa-close"></i></button>
            </div>
						<div class="modal-box__body">
              <div class="col-8 border-right no-pad">
                <div class="rwd">
                  <div class="col-12 border-bottom pad">
                    <div class="product-highlight-wrapper" style="max-height:160px; overflow-y:auto;">

                      <div class="product-highlight">
                        <div class="product-image">
                          <div class="product-ratio">
                            <img id="product_image" src="{{asset('assets/images/lamp.png')}}">
                          </div>
                        </div>
                        <div class="product-attributes">
													<h4 id="product_name">Lampu Dinding - COCO Wall Lamp</h4>
				                  <p id="product_desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="col-12 border-bottom pad">
                    <table class="table">
											<tr>
			                  <td class="text-left">Waktu Pembelian</td>
			                  <td class="text-right" id="transaction_date">2017-08-03 11:18:20</td>
			                </tr>
			                <tr>
			                  <td class="text-left">Jumlah Produk</td>
			                  <td class="text-right" id="quantity">1</td>
			                </tr>
			                <tr>
			                  <td class="text-left">Total Berat Barang</td>
			                  <td class="text-right" id="weight">300 gr</td>
			                </tr>
			                <tr>
			                  <td class="text-left">Total Harga</td>
			                  <td class="text-right" id="total_price">300.000</td>
			                </tr>
                    </table>
                  </div>
                  <div class="col-12 pad">
                    <table class="table">
											<tr>
			                  <td class="text-left">Biaya Kirim</td>
			                  <td class="text-right" id="shipping_price">7000</td>
			                </tr>
			                <tr>
			                  <td class="text-left">Total Pembayaran</td>
			                  <td class="text-right text-bold" id="total_price_2">307.000</td>
			                </tr>
			                <tr class="separator"></tr>
			                <tr>
			                  <td class="text-left">Total Harga</td>
			                  <td class="text-right" id="total_price_3">300.000</td>
			                </tr>
			                <tr>
			                  <td class="text-left">Pilihan Pembayaran</td>
			                  <td class="text-right" id="payment_method">BCA KlikPay</td>
			                </tr>
			                <tr>
			                  <td class="text-left">Layanan Pengiriman</td>
			                  <td class="text-right" id="courier">JNE</td>
			                </tr>
			                <tr>
			                  <td class="text-left">Jenis Pengiriman</td>
			                  <td class="text-right" id="courier_type">CTC</td>
			                </tr>
			                <tr>
			                  <td class="text-left">Resi Pengiriman</td>
			                  <td class="text-right">
			                    <a href="#" class="text-link">Salin</a>
			                    <input type="text" name="resi" readonly="" value="test" class="form-field" style="max-width: 140px">
			                  </td>
			                </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-4 no-pad">
                <div class="col-12 border-bottom pad">
                  <h3 class="text-info">Tujuan Barang</h3>
									<p class="wider">Nama Penerima <br class="hide-sm"><span id="name">John Doe</span></p>
			            <p class="wider">Alamat Pengiriman <br class="hide-sm"><span id="address">Jl. Jati Indah No.2</span></p>
			            <p class="wider">No. Telepon <br clear="hide-sm"><span id="phone">16730</span></p>
                </div>
                <div class="col-12 pad">
                  <h3 class="text-info">Status Barang</h3>
                  <table class="table">
                    <tr>
                      <td class="no-pad">Menunggu Pembayaran</td>
                      <td class="text-success text-right"><i class="fa fa-check-circle-o"></i></td>
                    </tr>
                    <tr>
                      <td class="no-pad">Pembayaran Diterima</td>
                      <td class="text-default text-right"><i class="fa fa-check-circle-o"></i></td>
                    </tr>
                    <tr>
                      <td class="no-pad">Barang Diproses</td>
                      <td class="text-default text-right"><i class="fa fa-check-circle-o"></i></td>
                    </tr>
                    <tr>
                      <td class="no-pad">Barang Dikirim</td>
                      <td class="text-default text-right"><i class="fa fa-check-circle-o"></i></td>
                    </tr>
                    <tr>
                      <td class="no-pad">Selesai</td>
                      <td class="text-default text-right"><i class="fa fa-check-circle-o"></i></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
			@if (Auth::user())
			<div class="modal animate" id="modal_review">
				<div class="modal-content">
					<div class="modal-box modal--review">
						<div class="modal-box__head">
							<h3 class="modal-box__head__title">{!! trans('member/review.m_title') !!}</h3>
							<button class="btn btn-close modal-box__head__close"><i class="fa fa-close"></i></button>
						</div>
						<div class="modal-box__body">
							<h3 id="product_name">Lampu Hias Dinding COOL</h3>
							<form action="{{url('/member/rating')}}" method="post">
			          {{ csrf_field() }}
								<input type="hidden" name="product_id" value="">
								<input type="hidden" name="user_id" value="{{$user->user_id}}">
								<div class="form-group">
									<label>{!! trans('member/review.give_rate') !!}</label>
									<select class="form-field" name="rating">
										<option value="5">{!! trans('member/review.five') !!}</option>
										<option value="4">{!! trans('member/review.four') !!}</option>
										<option value="3">{!! trans('member/review.three') !!}</option>
										<option value="2">{!! trans('member/review.two') !!}</option>
										<option value="1">{!! trans('member/review.one') !!}</option>
									</select>
								</div>
								<br>
								<div class="form-group">
									<label>{!! trans('member/review.g_review') !!}</label>
									<textarea name="comments" class="form-field"></textarea>
								</div>
								<div class="form-group">
									<button type="button" id="send-review" class="btn btn-primary btn-lg border-radius-lg">{!! trans('member/review.send_r') !!}</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			@endif
			<div class="modal animate {{Session::has('subscribed')?'modal-active':''}}" id="subscribe">
				<div class="modal-content">
					<div class="modal-box">
						<div class="modal-box__head modal-box__head--subs">
							<h3 class="modal-box__head__title">{!! trans('layout_indikraf.thanks') !!}</h3>
							<button class="btn btn-close modal-box__head__close"><i class="fa fa-close"></i></button>
						</div>
						<div class="modal-box__body">
							<div class="col-12">
								<p>
									{!! trans('layout_indikraf.subscribed') !!}
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<main class="main-section transition-fadeIn">
    	@yield('content')
		</main>
    <footer>
			<div class="container container--foot">
				<div class="section section--link">
					<div class="section--link__logo">
						<a href="{{url('/')}}"><img src="{{asset('assets/images/logo_big.png')}}"></a>
					</div>
					<div class="section--link__list">
						<ul>
							<li><a href="{{url('/open_store')}}">Open a Store</a></li>
							<li><a href="{{url('/register')}}">Join Indikraf</a></li>
							<li><a href="{{url('/articles')}}">Blog</a></li>
						</ul>
						<ul>
							<li><a href="{{url('/about')}}">About Us</a></li>
							<li><a href="#">Press Kit</a></li>
							<li><a href="{{url('/faq')}}">Support & FAQ</a></li>
						</ul>
						<ul>
							<li><a href="{{url('/about')}}">Terms</a></li>
							<li><a href="{{url('/about')}}">Privacy</a></li>
							<li><a href="{{url('/contact')}}">Contact Us</a></li>
						</ul>
					</div>
				</div>
				<div class="section section--copyright">
					<p class="section--copyright__text">&copy; Indikraf 2017</p>
				</div>
				<br>&nbsp;
			</div>
		</footer>
		<script type="text/javascript">
		    var widgetId1;
		    var widgetId2;
		    var onloadCallback = function() {
		      // Renders the HTML element with id 'example1' as a reCAPTCHA widget.
		      // The id of the reCAPTCHA widget is assigned to 'widgetId1'.
		      widgetId1 = grecaptcha.render('register-captcha', {
		        'sitekey' : '6LdCozAUAAAAAMPhyEjpBrIj805GJDXi-dsWNjJG',
		        'callback' 	: enableRegister
		      });
		      widgetId2 = grecaptcha.render(document.getElementById('captcha-2-indikraf'), {
		        'sitekey' : '6LdCozAUAAAAAMPhyEjpBrIj805GJDXi-dsWNjJG',
						'callback': verifyCallback
		      });
		    };
	  </script>

		<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
		<script src="{{asset('assets/javascripts/app.js')}}"></script>
		<script src="{{asset('js/jquery-number-master/jquery.number.js')}}"></script>
		<script type="text/javascript">
			var enableRegister = function(response) {
	      document.getElementById('btnRegister').disabled=false;
	    };
			function language_control(lang){
				$('#language-form input[name=lang]').val(lang);
				$('#language-form').submit();
			}
		</script>
		@yield('js')
	</div>
</body>
</html>
