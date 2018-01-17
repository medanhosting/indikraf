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
          <h1>{!! trans('front/articles.title') !!}</h1>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="section">
				<div class="col-1 hide-sm"></div>
				<div class="col-10" id="article-wrapper">
        @foreach ($articles as $a)
          <div class="news-wrap news--page">
            <div class="news--page__padding rwd">
              <div class="news-wrap__cover">
                @php
                  $path="uploads/gambar_artikel/".$a->writer->user_id."_".$a->writer->profile->first_name."/artikel".$a->post_id."/".$a->default_image;
                @endphp
                <a href="{{url('/article_details/'.$a->slug)}}"><img src="{{asset($path)}}"></a>
              </div>
              <div class="news-wrap__body">
                <a href="{{url('/article_details/'.$a->slug)}}"><h3 class="title">{{$a->title}}</h3></a>
                <p class="date">{{$a->date_format()}}</p>
                <p class="main">
                  {{str_limit(strip_tags($a->post), $limit = 100, $end = '...')}}
                </p>
                <a href="{{url('/article_details/'.$a->slug)}}" class="btn btn-less">Read More</a>
              </div>
            </div>
          </div>
        @endforeach
        </div>
      </div>
      <div class="section">
        <p class="load-more">
          @if ($articles->lastPage()!=$articles->currentPage())
            <button class="btn btn-opaque load-start" id="load-more">{!! trans('front/articles.load_more') !!}</button>
          @endif
        </p>
        <p class="to-top">
          <a href="#" class="btn btn-primary" id="top"><i class="fa fa-arrow-up"></i></a>
        </p>
      </div>
    </div>
@endsection

@section('js')
  <script type="text/javascript">
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
          url:'/articles/load_more',
          data:{page:page},
          success:function(s){
            if(s=="0"){
              $('#load-more').css('display','none');
            }else {
              $('#article-wrapper').append(s);
              var $new = $('.load-item');
              $new.slideDown();
            }
            if (page=={{$articles->lastPage()}}) {
              $('#load-more').css('display','none');
            }
          },
          error:function(a){
            $(this).css('display','none');
          }
        });
    });
  </script>
@endsection
