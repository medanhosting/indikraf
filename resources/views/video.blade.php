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
					<h1>{!! trans('front/videos.title') !!}</h1>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="section">
				<div class="col-1 hide-sm"></div>
				<div class="col-10" id="video-wrapper">
						@foreach ($videos as $v)
							@if (array_search($v->video_id,$skip)!=false)

							@else
								<div class="video">
									<a href="{{url('/watch/'.$v->video_id)}}" data-featherlight="ajax" data-featherlight-variant="featherlight-video" class="no-smoothstate video-trigger">
										<div class="video-embed">
											<div class="video-ratio">
												<img src="{{$v->thumbnail}}" alt="Gambar Thumbnail" class="video-ratio-thumbs">
												<div class="video-ratio-helper">
													<img src="assets/images/play-button.png" alt="Play Button">
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
			</div>
			<div class="section">
				<p class="load-more">
					<button class="btn btn-opaque load-start" id="load-more">{!! trans('front/videos.load_more') !!}</button>
				</p>
				<p class="to-top">
					<a href="#" class="btn btn-primary" id="top"><i class="fa fa-arrow-up"></i></a>
				</p>
			</div>
		</div>
@endsection
@section('js')
	<script type="text/javascript">
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
		var page=1;
		$('#load-more').on('click',function(e){
        page++;
        $.ajax({
          type:'get',
          url:'/video/load_more',
          data:{page:page},
          success:function(s){
            if(s.substr(s.length - 1)=="0"){
              $('#load-more').css('display','none');
            }else {
              $('#video-wrapper').append(s);
              var $new = $('.video');
              $new.slideDown();
            }
						if (page=={{$videos->lastPage()}}) {
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
