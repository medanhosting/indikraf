@extends('layouts.layout_indikraf')

@section('title')
	{{$meta->title}}
@endsection

@section('keywords')
	{{$meta->keyword}}
@endsection

@section('description')
	{{$meta->description}}
@endsection

@section('content')
		<div class="container container--slider">
			<div class="section section--slider" id="slider1">
				<div class="section--slider__item">
					<div class="section--slider__item__ratio">
						<div class="centerize">
							<img src="{{asset('assets/images/banner.jpg')}}" alt="Banner 1">
						</div>
					</div>
				</div>
				<div class="section--slider__item">
					<div class="section--slider__item__ratio">
						<div class="centerize">
							<img src="{{asset('assets/images/BannerBlog.jpg')}}" alt="Banner 2">
						</div>
					</div>
				</div>
				<div class="section--slider__item">
					<div class="section--slider__item__ratio">
						<div class="centerize">
							<img src="{{asset('assets/images/artikel_big.jpg')}}" alt="Banner 3">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="section section--panel">
				<div class="section--panel__title">
					<h2>{{trans('front/index.best_selling')}}</h2>
				</div>
				<div class="col-1 hide-sm"></div>
				<div class="col-10">
					<div class="slider" id="slider2">
						@foreach ($best_selling_products as $p)
						<div class="slider__item">
							<div class="product-wrap">
								<a href="products/detail_product/{{$p->slug}}">
									<div class="product-wrap__ratio">
										@if (count($p->product_images)!=0)
			                @php($path="uploads/gambar_produk/".$p->seller->user_id."_".$p->seller->profile->first_name."/produk".$p->product_id)
												<img src="{{asset($path."/".$p->product_images[0]->product_image_name)}}">
			              @else
			                <img src="http://placehold.it/320x150" alt="">
			              @endif
									</div>
									<div class="product-wrap__attributes">
										<h3 class="title">{{$p->product_name}}</h3>
										<p>
											<span class="author">{{$p->store->store_name}}</span>
											<span class="price">Rp {{number_format($p->price)}}</span>
										</p>
									</div>
								</a>
							</div>
						</div>
						@endforeach
					</div>
					<hr class="section-separator">
				</div>
			</div>
			<div class="section section--panel">
				<div class="section--panel__title">
					<h2>{{trans('front/index.newest_products')}}</h2>
				</div>
				<div class="col-1 hide-sm"></div>
				<div class="col-10 rwd">
					@foreach ($newest_products as $p)
					<div class="product-wrap-rating">
						<a href="products/detail_product/{{$p->slug}}">
							<div class="product-wrap-rating--padding">
								<div class="product-wrap-rating__ratio">
									@if (count($p->product_images)!=0)
										@php($path="uploads/gambar_produk/".$p->seller->user_id."_".$p->seller->profile->first_name."/produk".$p->product_id)
											<img src="{{asset($path."/".$p->product_images[0]->product_image_name)}}">
									@else
										<img src="http://placehold.it/320x150" alt="">
									@endif
								</div>
								<div class="product-wrap-rating__attributes">
									<h3 class="title">{{$p->product_name}}</h3>
									<p class="author">{{$p->store->store_name}}</p>
									<p class="rwd">
										<span class="price">Rp. {{number_format($p->price)}}</span>
										<span class="rating">
											@php($salim=0)
											@for ($i = 0; $i < $p->rating(); $i++)
												@php
													$salim++
												@endphp
												<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											@endfor
											@php
												$arizi=5-$salim;
											@endphp
											@for ($i = 0; $i < $arizi; $i++)
												<img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
											@endfor
										</span>
									</p>
								</div>
							</div>
						</a>
					</div>
					@endforeach
				</div>
				<br>&nbsp;<br>
				<p class="centerize"><a href="{{url('/products')}}" class="btn btn-opaque">{{trans('front/index.all_products')}}</a></p>
				<br>&nbsp;<br>
			</div>
		</div>
		<div class="container container--news">
			<div class="section section--portal">
				<div class="section--portal__title">
					<h2>{{trans('front/index.articles')}}</h2>
				</div>
				<div class="section--portal__slider" id="slider3">
					@foreach ($articles as $a)
					<div class="section--portal__slider__item">
						<div class="news-wrap">
							<div class="news-wrap__cover">
								@php
									$path="uploads/gambar_artikel/".$a->writer->user_id."_".$a->writer->profile->first_name."/artikel".$a->post_id."/".$a->thumbnail;
								@endphp
								<a href="{{url('/article_details/'.$a->slug)}}"><img src="{{asset($path)}}"></a>
							</div>
							<div class="news-wrap__body">
								<a href="{{url('/article_details/'.$a->slug)}}"><h3 class="title">{{$a->title}}</h3></a>
								<p class="date">{{$a->date_format()}}</p>
								<p class="main">
									{{str_limit(strip_tags($a->post), $limit = 50, $end = '...')}}
								</p>
								<a href="{{url('/article_details/'.$a->slug)}}" class="btn btn-less">Read More</a>
							</div>
						</div>
					</div>
					@endforeach
				</div>
				<p class="section--portal__show">
					<a href="{{('/articles')}}" class="btn btn-opaque">{{trans('front/index.all_articles')}}</a>
				</p>
			</div>
		</div>
		<div class="container">
			<div class="section section--portal">
				<div class="section--portal__title">
					<h2>{{trans('front/index.videos')}}</h2>
				</div>
				<div class="col-1 hide-sm"></div>
				<div class="col-10 rwd">
					@foreach ($videos as $v)
							@if (array_search($v->video_id,$skip)!=false)

							@else
								<div class="video">
									<a href="{{url('/watch/'.$v->video_id)}}" data-featherlight="ajax" data-featherlight-variant="featherlight-video" class="no-smoothstate video-trigger">
										<div class="video-embed">
											<div class="video-ratio">
												<img src="{{$v->thumbnail}}" alt="Gambar Thumbnail" class="video-ratio-thumbs">
												<div class="video-ratio-helper">
													{{-- <img src="assets/images/play-button.png" alt="Play Button"> --}}
												</div>
											</div>
										</div>
										<div class="video-desc">
											<h2 class="video-desc-title">{{$v->title==NULL?'Video Indikraf':$v->title}}</h2>
											<p class="video-desc-date">Diunggah {{$v->date_format()}}</p>
											<p class="video-desc-detail">
												@if ($v->description!=NULL)
													{{$v->description}}
												@else
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
												@endif
											</p>
										</div>
									</a>
								</div>
						@endif
					@endforeach
				</div>
				<p class="section--portal__show">
					<a href="{{url('/video')}}" class="btn btn-opaque">{{trans('front/index.all_videos')}}</a>
				</p>
			</div>
		</div>
		<div class="container container--subscribe">
			<div class="section section--subscribe">
				<div class="subscribe">
					<div class="subscribe-header">
						<h3>{{trans('front/index.direct_email')}}</h3>
						<h2>{{trans('front/index.get_email')}}</h2>
					</div>
					<div class="subscribe-body">
						<form method="post" action="{{url('/subscribe')}}">
							{{ csrf_field() }}
							<div class="form-group">
								<input type="text" name="name" placeholder="{{trans('front/index.name_hint')}} ..." required>
							</div>
							<div class="input-group">
								<input type="email" name="email" placeholder="{{trans('front/index.email_hint')}} ..." required>
								<div class="input-group__addon">
									<button class="btn btn-default">
										{{trans('front/index.subscribe_btn')}}
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
@endsection
@section('js')
	<script type="text/javascript">
		$(document).ready(function (){
			// Slider
			$('#slider1').slick({
				infinite: true,
				autoplay: true,
				autoplaySpeed: 5000,
				prevArrow: "<button type='button' class='slider-prev'><img src='assets/images/left-chevron.png'></button>",
				nextArrow: "<button type='button' class='slider-next'><img src='assets/images/right-chevron.png'></button>",
				responsive: [{
					breakpoint: 992,
					settings: {
						arrows: false
					}
				}]
			});
			$('#slider2').slick({
				infinite: true,
				slidesToShow: 3,
				slidesToScroll: 3,
				autoplay: true,
				autoplaySpeed: 2000,
				prevArrow: "<button type='button' class='slider-prev'><img src='assets/images/left-chevron.png'></button>",
				nextArrow: "<button type='button' class='slider-next'><img src='assets/images/right-chevron.png'></button>",
				responsive: [{
					breakpoint: 768,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}]
			});
			$('#slider3').slick({
				arrows: true,
				infinite: true,
				autoplay: true,
				autoplaySpeed: 2000,
				prevArrow: "<button type='button' class='slider-prev'><img src='assets/images/left-chevron.png'></button>",
				nextArrow: "<button type='button' class='slider-next'><img src='assets/images/right-chevron.png'></button>",
				responsive: [{
					breakpoint: 992,
					settings: {
						arrows: false
					}
				}]
			});
		});
	</script>
@endsection
