@extends('layouts.layout_indikraf')
@section('keywords')
	{{trim($article->meta_keyword)}}
@endsection

@section('description')
	{{trim($article->meta_description)}}
@endsection
@section('content')
    <div class="container">
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10">
          <div class="news-detail">
            <div class="news-detail__head">
              <h2 class="title">{{$article->title}}</h2>
              <p class="date">{{$article->date_format()}}</p>
            </div>
            <div class="news-detail__cover">
              <div class="news-detail__cover__ratio">
                @php
                  $path="uploads/gambar_artikel/".$article->writer->user_id."_".$article->writer->profile->first_name."/artikel".$article->post_id."/".$article->default_image;
                @endphp
                <img src="{{asset($path)}}">
              </div>
            </div>
            <div class="news-detail__body">
              {!!$article->post!!}
            </div>
            <div class="news-detail__share">
              {{-- <!-- Go to www.addthis.com/dashboard to customize your tools -->
              <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-598c2a2d9f4c6240"></script>
              <!-- Go to www.addthis.com/dashboard to customize your tools -->
              <div class="addthis_inline_share_toolbox_d2xc"></div> --}}
              <!-- AddToAny BEGIN -->
              <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                <a class="a2a_button_facebook"></a>
                <a class="a2a_button_twitter"></a>
                <a class="a2a_button_pinterest"></a>
                <a class="a2a_button_linkedin"></a>
                <a class="a2a_button_email"></a>
              </div>
                <script async src="https://static.addtoany.com/menu/page.js"></script>
              <!-- AddToAny END -->
              <br>
            </div>
            <div class="news-detail__foot">
              <div class="news-detail__foot__item rwd">
                <h3 class="uppercase">{!! trans('front/articles.recommendation') !!}</h3>
                @foreach ($related_article as $ra)
                <div class="news-bait">
                  <a href="{{url('/article_details/'.$ra->slug)}}">
                    <div class="news-bait__cover">
                      @php
                        $path="uploads/gambar_artikel/".$ra->writer->user_id."_".$ra->writer->profile->first_name."/artikel".$ra->post_id."/".$ra->default_image;
                      @endphp
                      <img src="{{asset($path)}}">
                    </div>
                    <div class="news-bait__title">
                      <h4>{{$ra->title}}</h4>
                      <p class="date">{{$ra->date_format()}}</p>
                    </div>
                    <div class="news-bait__body">
                      {{str_limit(strip_tags($ra->post), $limit = 50, $end = '...')}}
                    </div>
                  </a>
                </div>
                @endforeach
                </div>
              </div>
              <div class="news-detail__foot__item">
                <h3 class="uppercase">{!! trans('front/articles.write_comment') !!}</h3>
                <form class="comment" method="post" action="{{url('/comment')}}">
                  @if (Auth::guest())
                    <div class="comment__inline">
                      <label>{!! trans('front/articles.name') !!}</label>
                      <input type="text" name="name" required>
                    </div>
                    <div class="comment__inline">
                      <label>Email</label>
                      <input type="email" name="email" required>
                    </div>
                  @else
                    <input type="hidden" name="name" value="{{$user->profile->first_name." ".$user->profile->last_name}}">
                    <input type="hidden" name="email" value="{{$user->email}}">
                  @endif
                  <div class="comment__block">
                    <label>{!! trans('front/articles.comment') !!}</label>
                    <textarea name="comment" required></textarea><br>
                    <div id="captcha-2-indikraf"></div>
                  </div>
                  {{csrf_field()}}
                  <input type="hidden" name="post_id" value="{{$article->post_id}}">
                  <div class="comment__inline">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary" id="btn_submit" disabled>{!! trans('front/articles.btn_comment') !!}</button>
                  </div>
                </form>
              </div>
              <div class="news-detail__foot__item">
                <h3 class="uppercase">{!! trans('front/articles.comment_list') !!}</h3>
                @if (count($comments)==0)
                  	{!! trans('front/articles.empty_comment') !!}
                @endif
                @foreach ($comments as $c)
                  <div class="comment-list">
                    <div class="comment-list__item">
                      <div class="comment-list__item__head rwd">
                        <span class="commentator pull-left">{{$c->name}}</span>
                        <span class="date pull-right">{{$c->date_format()}}</span>
                      </div>
                      <div class="comment-list__item__body">
                        <p>{{$c->comment}}</p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="pagination__foot">
                <ul class="pagination-list">
                  @php($link_limit = 10)
                  @if ($comments->onFirstPage())
                  @else
                    <li><a href="{{$comments->previousPageUrl()}}">&laquo; Prev</a></li>
                  @endif

                  @if ($comments->hasMorePages())
                    @for ($i = 1; $i <= $comments->lastPage(); $i++)
                        @php
                        $half_total_links = floor($link_limit / 2);
                        $from = $comments->currentPage() - $half_total_links;
                        $to = $comments->currentPage() + $half_total_links;
                        if ($comments->currentPage() < $half_total_links) {
                           $to += $half_total_links - $comments->currentPage();
                        }
                        if ($comments->lastPage() - $comments->currentPage() < $half_total_links) {
                            $from -= $half_total_links - ($comments->lastPage() - $comments->currentPage()) - 1;
                        }
                        @endphp
                        @if ($from < $i && $i < $to)
                            <li class="{{ ($comments->currentPage() == $i) ? ' active' : '' }}">
                                <a href="{{ $comments->url($i) }}">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor
                  @endif

                  @if ($comments->hasMorePages())
                    <li><a href="{{$comments->nextPageUrl()}}">Next &raquo;</a></li>
                  @else
                  @endif
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

@endsection

@section('js')
  <script type="text/javascript">
    var verifyCallback = function(response) {
      document.getElementById('btn_submit').disabled=false;
    };
  </script>
@endsection
