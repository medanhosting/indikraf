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
			<div class="page-head">
				<div class="page-head__title">
					<h1>{!! trans('front/gallery.title') !!}</h1>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="section">
				<div class="col-1 hide-sm"></div>
				<div class="col-10">
					<div class="gallery-wrapper">
						@foreach ($images as $i)
						@php
							$path="/uploads/gallery/".$i->category->image_category_id."_".$i->category->image_category_name;
						@endphp
						<div class="gallery">
							<div class="gallery__ratio">
								<a href="{{$path."/".$i->image_path}}" title="{{$i->tooltip}}" data-lightbox="gallery" class="no-smoothstate" data-title="{{$i->description}}">
									<img src="{{$path."/".$i->image_path}}" alt="{{$i->tooltip}}" title="{{$i->tooltip}}">
									<div class="hover">
										<i class="fa fa-search"></i>
									</div>
								</a>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
			<div class="section">
				<p class="load-more">
					@if ($images->lastPage()!=1)
						<button class="btn btn-opaque load-start" id="load-more">{!! trans('front/gallery.load_more') !!}</button>
					@endif
				</p>
				<p class="to-top">
          <a href="#" class="btn btn-primary" id="top"><i class="fa fa-arrow-up"></i></a>
        </p>
			</div>
		</div>
@endsection
@section('js')
	<script src="{{asset('assets/plugins/lightbox2/js/lightbox.min.js')}}"></script>
	<script type="text/javascript">

		lightbox.option({
			'alwaysShowNavOnTouchDevices' : true
		});

		var page=1;
		$('#top').click(function () {
				$('body,html').animate({
						scrollTop: 0
				}, 600);
				return false;
		});

		$(window).scroll(function () {
				if ($(this).scrollTop() > 100) {
						$('.to-top a').fadeIn();
				} else {
						$('.to-top a').fadeOut();
				}
		});

		$('#load-more').on('click',function(e){
        page++;
        $.ajax({
          type:'get',
          url:'/gallery/load_more',
          data:{page:page},
          success:function(s){
            if(s.substr(s.length - 1)=="0"){
              $('#load-more').css('display','none');
            }else {
              $('.gallery-wrapper').append(s);
              var $new = $('.gallery');
              $new.slideDown();
            }
						if (page=={{$images->lastPage()}}) {
              $('#load-more').css('display','none');
            }
          },
          error:function(a){
            $('#load-more').css('display','none');
          }
        });
    });
	</script>
@endsection
