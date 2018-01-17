@extends('layouts.layout_indikraf')
@section('content')
<div class="container">
   <div class="section">
      <div class="col-1 hide-sm"></div>
      <div class="col-10">
        @if (count($products)==0 && count($articles)==0 && count($images)==0 && count($videos)==0)
          <div class="nothing" style="min-height:300px">
            <br><br><br>
            <center>
              <h1>Kami tidak menemukan hasil pencarian untuk <br>"{{app('request')->input('s')}}"</h1>
              <br>Tolong periksa pengejaan kata, gunakan kata-kata yang lebih umum dan coba lagi!
            </center>
          </div>
        @else
            @if (count($products))
             <div class="search">
                <div class="search-header">
                   <h2 class="search-header-title"><i class="fa fa-bookmark-o"></i> Produk</h2>
                   <a href="#" class="search-header-detail" onclick="event.preventDefault();
                            document.getElementById('search_product_form').submit();">
                            Lainnya >>
                   </a>
                   <form id="search_product_form" action="{{url('/search_product')}}" method="get">
                     <input type="hidden" name="s" value="{{app('request')->input('s')}}">
                   </form>
                </div>
                <div class="search-body">
                  @foreach ($products as $p)
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
                                <img src="assets/images/star-on.png" class="rating__star">
                              @endfor
                              @php
                                $arizi=5-$salim;
                              @endphp
                              @for ($i = 0; $i < $arizi; $i++)
                                <img src="assets/images/star-off.png" class="rating__star">
                              @endfor
                            </span>
                          </p>
                        </div>
                      </div>
                    </a>
                  </div>
                  @endforeach
              </div>
            </div>
          @endif
        @if (count($articles))
         <div class="search">
           <div class="search-header">
             <h2 class="search-header-title"><i class="fa fa-pencil-square-o"></i> Artikel</h2>
             <a href="#" class="search-header-detail" onclick="event.preventDefault();
                      document.getElementById('search_article_form').submit();">
                      Lainnya >>
             </a>
             <form id="search_article_form" action="{{url('/search_article')}}" method="get">
               <input type="hidden" name="s" value="{{app('request')->input('s')}}">
             </form>
           </div>
           <div class="search-body">
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
         @endif
         @if (count($images))
           <div class="search">
             <div class="search-header">
               <h2 class="search-header-title"><i class="fa fa-picture-o"></i> Galeri</h2>
               <a href="#" class="search-header-detail" onclick="event.preventDefault();
                        document.getElementById('search_gallery_form').submit();">
                        Lainnya >>
               </a>
               <form id="search_gallery_form" action="{{url('/search_gallery')}}" method="get">
                  <input type="hidden" name="s" value="{{app('request')->input('s')}}">
               </form>
             </div>
             <div class="search-body">
               @foreach ($images as $i)
               @php
                 $path="/uploads/gallery/".$i->category->image_category_id."_".$i->category->image_category_name;
               @endphp
               <div class="gallery">
                 <div class="gallery__ratio">
                   <a href="{{$path."/".$i->image_path}}" data-lightbox="gallery" class="no-smoothstate" data-title="{{$i->description}}">
                     <img src="{{$path."/".$i->image_path}}" alt="{{$i->tooltip}}">
                     <div class="hover">
                       <i class="fa fa-search"></i>
                     </div>
                   </a>
                 </div>
               </div>
               @endforeach
             </div>
           </div>
         @endif
         @if (count($videos))
           <div class="search">
             <div class="search-header">
               <h2 class="search-header-title"><i class="fa fa-video-camera"></i> Video</h2>
               <a href="#" class="search-header-detail" onclick="event.preventDefault();
                        document.getElementById('search_videos_form').submit();">
                        Lainnya >>
               </a>
               <form id="search_videos_form" action="{{url('/search_videos')}}" method="get">
                  <input type="hidden" name="s" value="{{app('request')->input('s')}}">
               </form>
             </div>
             <div class="search-body">
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
         @endif
        @endif
      </div>
   </div>
  <div class="section">
   <p class="to-top">
     <a href="#" class="btn btn-primary" id="top"><i class="fa fa-arrow-up"></i></a>
   </p>
  </div>
</div>
@endsection
@section('js')
	<script src="{{asset('assets/plugins/lightbox2/js/lightbox.min.js')}}"></script>
@endsection
