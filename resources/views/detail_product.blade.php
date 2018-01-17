@extends('layouts.layout_indikraf')

@section('keywords')
	{{$detail_product->meta_keyword}}
@endsection

@section('description')
	{{$detail_product->meta_description}}
@endsection

@section('content')
  @php($dp=$detail_product)
    <div class="container container--gray">
      <div class="section section--hide-sm">
        <div class="col-1"></div>
        <div class="col-10">
          <ul class="breadcrumb">
            <li><a href="{{url('/products')}}">{!! trans('front/products.title') !!}</a></li>
            <li><a href="{{url('/search_product?category_list='.$dp->category->category_id)}}">{{$dp->category->category_name}}</a></li>
            <li>{{$dp->product_name}}</li>
          </ul>
        </div>
      </div>
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10">
          <div class="rwd">
            <div class="col-7">
              <div class="product-panel">
                <div class="product-image">
                  <div class="product-image__big" id="big">
                    @php($path="/uploads/gambar_produk/".$dp->seller->user_id."_".$dp->seller->profile->first_name."/produk".$dp->product_id)
                    @foreach ($dp->product_images as $g)
                      <div class="product-image__big__items">
                          <div class="product-image__ratio">
                            <img src="{{$path."/".$g->product_image_name}}">
                          </div>
                      </div>
                    @endforeach
                  </div>
                  <div class="product-image__small" id="small">
                    @foreach ($dp->product_images as $g)
                      <div class="product-image__small__items">
                        <div class="product-image__ratio">
                          <img src="{{$path."/".$g->product_image_name}}">
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            <div class="col-5">
              <div class="product-panel">
                <div class="product-detail">
                  <h2 class="product-detail__name">
                    {{$dp->product_name}}
                  </h2>
                  <div class="rwd">
                    <div class="product-detail__price">
											@if ($dp->price!=$dp->first_price)
												<p class="disc-price">Rp {{number_format($dp->first_price)}}</p>
											@endif
											<p class="current-price">Rp {{number_format($dp->price)}}</p>
                    </div>
                    <div class="product-detail__author">
                      <p class="name text-muted">{{$dp->store->store_name}}</p>
                      <p class="location"><big><b><i class="fa fa-map-marker"></i></b></big>@php $location=App\Models\City::find($dp->store->store_city); @endphp {{$location->city}} </p>
                    </div>
                  </div>
                </div>
                <div class="product-buy">
                  <form action="{{url('/add_to_cart/')}}" method="post">
                    <div class="form-group">
                      <label>{!! trans('front/products.stock') !!}</label>
                      <p>
                        @php
                          $min=0;
                          if(count($product=App\Models\Cart::where([['product_id',$product_id],['status','0']])->get())!=0)
                          {
                            foreach ($product as $p) {
                              $min+=$p->amount;
                            }
                          }
                          if (!Auth::check()) {
                            if($product=count(Cart::get($product_id))>0){
                              $min+=Cart::get($product_id)->quantity;
                            }
                          }
                          $min=0;
                        @endphp
                         {{$dp->stock-$min>0?$dp->stock-$min:'0'}}
                    </p>
                    </div>
                        @if ($dp->stock-$min<1)
                          {!! trans('front/products.empty_amount') !!}
                        @else
                          <div class="form-group">
                            <label>{!! trans('front/products.amount') !!}</label>
                            <div class="quantity">
                              <input type="number" min="1" step="1" value="1" class="border-radius-lg" max="{{$dp->stock-$min}}" name="amount" id="amount" placeholder="0" required>
                            </div>
                          </div>
                          <input type="hidden" name="product_id" value="{{$dp->product_id}}">
                          {{csrf_field()}}
                          <div class="form-group">
                            <button type="submit" onclick="myFunction()" class="btn btn-primary btn-lg border-radius-lg">Beli</button>
                          </div>
                        @endif
                  </form>
                </div>
              </div>
              <br>&nbsp;
              <div class="product-panel">
                <ul class="tabs">
                  <li class="tab-link current" data-tab="tab-1">{!! trans('front/products.description') !!}</li>
                  <li class="tab-link" data-tab="tab-2">{!! trans('front/products.another_info') !!}</li>
                  <li class="tab-link" data-tab="tab-3">{!! trans('front/products.review') !!} (<span>{{count($dp->review)}}</span>)</li>
                </ul>
                <div class="tab-content current" id="tab-1">
                  {{$dp->description}}
                </div>
                <div class="tab-content" id="tab-2">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus eos soluta minus, dolores deserunt harum laudantium et consequuntur vitae aut quibusdam nam architecto quisquam ut, sequi est voluptatem obcaecati suscipit!
                </div>
                <div class="tab-content" id="tab-3">
                  @if ($dp->rating()!="0")
                    @foreach ($dp->review as $r)
                      <b>{{$r->user->profile->first_name." ".$r->user->profile->last_name}}</b><br>
                      {{$r->comments}}<hr>
                    @endforeach
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10">
          <h2>&nbsp; {!! trans('front/products.related') !!}</h2>
          <div class="rwd">
            @foreach ($related_product as $rp)
            <div class="product-wrap-rating">
              <a href="{{url('/products/detail_product/'.$rp->slug)}}">
                <div class="product-wrap-rating--padding">
                  <div class="product-wrap-rating__ratio">
                    @php($path="/uploads/gambar_produk/".$rp->seller->user_id."_".$rp->seller->profile->first_name."/produk".$rp->product_id)
                    <img src="{{$path."/".$rp->product_images[0]->product_image_name}}">
                  </div>
                  <div class="product-wrap-rating__attributes">
                    <h3 class="title">{{$rp->product_name}}</h3>
                    <p class="author">{{$rp->store->store_name}}</p>
                    <p class="rwd">
                      <span class="price">Rp. {{number_format($rp->price)}}</span>
                      <span class="rating">
  											@php($salim=0)
  											@for ($i = 0; $i < $rp->rating(); $i++)
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
        </div>
      </div>
      <br>&nbsp;<br>&nbsp;
    </div>
  @endsection

  @section('js')
    <script src="{{asset('assets/javascripts/input-number.js')}}"></script>
  	<script src="{{asset('assets/javascripts/tab.js')}}"></script>
    <script type="text/javascript">
        function myFunction() {
          var txt = "";
          if (document.getElementById("amount").validity.rangeOverflow) {
             txt = "Stock barang tidak cukup untuk permintaan anda";
             document.getElementById("amount").setCustomValidity(txt);
          }
        }
    		$(document).ready(function (){
    			// Slider
    			$('#big').slick({
    				slidesToShow: 1,
    				slidesToScroll: 1,
    				arrows: true,
    				fade: true,
    				prevArrow: "<button type='button' class='slider-prev'><img src='{{asset('assets/images/left-chevron-colored.png')}}'></button>",
    				nextArrow: "<button type='button' class='slider-next'><img src='{{asset('assets/images/right-chevron-colored.png')}}'></button>",
    				asNavFor: '#small',
    				responsive: [{
    					breakpoint: 992,
    					settings: {
    						arrows: false,
    						dots: true
    					}
    				}]
    			});

    			$('#small').slick({
    				slidesToShow: 3,
    				slidesToScroll: 1,
    				asNavFor: '#big',
    				centerMode: true,
    				focusOnSelect: true,
    				arrows: false
    			});
    		});
    	</script>
  @endsection
