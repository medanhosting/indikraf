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

@section('css')
	<style media="screen">
		.loading_filter{
			position:absolute;
			width:100%;
			height:200px;
			background:url("{{asset('assets/images/loading.gif')}}") center no-repeat rgba(255, 255, 255, 0.8);
			background-size:30px;
		}
	</style>
@endsection

@section('content')
		@php
			if (app('request')->input('city_list')!=null) {
				$city_list=explode(',',app('request')->input('city_list'));
				$city_list['first'] = $city_list[0];
				unset($city_list[0]);
			}else {
				$city_list=[];
			}

			if (app('request')->input('category_list')!=null) {
				$category_list=explode(',',app('request')->input('category_list'));
				$category_list['first'] = $category_list[0];
				unset($category_list[0]);
			}else {
				$category_list=[];
			}

			if (app('request')->input('rating_list')!=null) {
				$rating_list=explode(',',app('request')->input('rating_list'));
				$rating_list['first'] = $rating_list[0];
				unset($rating_list[0]);
			}else {
				$rating_list=[];
			}
		@endphp
    <div class="page-head page-head--nobg">
      <div class="page-head__title">
        <h1>{!! trans('front/products.title') !!}</h1>
      </div>
    </div>
    <div class="container">
      <div class="section">
				<div class="col-3">
					<input type="hidden" name="s" value="{{app('request')->input('s')}}">
					<div class="filter-wrapper">
						<div class="filter">
							<div class="filter-head">
								<h2>{!! trans('front/products.category') !!}</h2>
							</div>
							<div class="filter-body">
								<ul class="filter-list" id="categoryContainer">
									@foreach ($categories as $c)
										<li>
											<input type="checkbox" name="category" class="category" value="{{$c->category_id}}" {{array_search($c->category_id,$category_list)!=false?'checked':''}}>{{$c->category_name}}
										</li>
									@endforeach
								</ul>
							</div>
						</div>
						<div class="filter">
							<div class="filter-head">
								<h2>{!! trans('front/products.sent_from') !!}</h2>
							</div>
							<div class="filter-body">
								<ul class="filter-list" id="cityContainer">
									@foreach ($stores as $s)
										<li>
											<input type="checkbox" name="kota" class="kota" value="{{$s->store_city}}" {{array_search($s->store_city,$city_list)!=false?'checked':''}}>{{App\Models\City::find($s->store_city)->city}}
										</li>
									@endforeach
								</ul>
							</div>
						</div>
						<div class="filter">
							<div class="filter-head">
								<h2>{!! trans('front/products.price') !!}</h2>
							</div>
							<div class="filter-body">
								<div class="filter-price">
									<div class="filter-price-row">
										<span class="filter-price-start" id="lower" range-data="0">Rp 0</span>
										<span class="filter-price-end" id="upper" range-data="1000000">Rp 1.000.000</span>
									</div>
									<div class="filter-price-row">
										<div id="rangeSlider"></div>
									</div>
									<div class="filter-price-row">
										<button type="button" id="btn-filter-harga" class="btn btn-default btn-block btn-lg">{!! trans('front/products.price_filter') !!}</button>
									</div>
								</div>
							</div>
						</div>
						<div class="filter">
							<div class="filter-head">
								<h2>Rating</h2>
							</div>
							<div class="filter-body" id="ratingContainer">
								<ul class="filter-list">
									<li>
										<input type="checkbox" name="rating" value="5" {{array_search(5,$rating_list)!=false?'checked':''}}>
										<span class="rating">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
										</span>
									</li>
									<li>
										<input type="checkbox" name="rating" value="4" {{array_search(4,$rating_list)!=false?'checked':''}}>
										<span class="rating">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
										</span>
									</li>
									<li>
										<input type="checkbox" name="rating" value="3" {{array_search(3,$rating_list)!=false?'checked':''}}>
										<span class="rating">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
										</span>
									</li>
									<li>
										<input type="checkbox" name="rating" value="2" {{array_search(2,$rating_list)!=false?'checked':''}}>
										<span class="rating">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
										</span>
									</li>
									<li>
										<input type="checkbox" name="rating" value="1" {{array_search(1,$rating_list)!=false?'checked':''}}>
										<span class="rating">
											<img src="{{asset('assets/images/star-on.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
											<img src="{{asset('assets/images/star-off.png')}}" class="rating__star">
										</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
        <div class="col-9 rwd" style="position:relative; min-height:200px">
          <div class="pagination">
            <div class="pagination__body rwd">
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
    									<h3 class="title">{{str_limit($p->product_name,27,'...')}}</h3>
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
            <div class="pagination__foot">
              <ul class="pagination-list">
                @php($link_limit = 10)
                @if ($products->onFirstPage())
                @else
                  <li><a href="{{$products->previousPageUrl()}}">&laquo; Prev</a></li>
                @endif

                @if ($products->hasMorePages())
                  @for ($i = 1; $i <= $products->lastPage(); $i++)
                      @php
                      $half_total_links = floor($link_limit / 2);
                      $from = $products->currentPage() - $half_total_links;
                      $to = $products->currentPage() + $half_total_links;
                      if ($products->currentPage() < $half_total_links) {
                         $to += $half_total_links - $products->currentPage();
                      }
                      if ($products->lastPage() - $products->currentPage() < $half_total_links) {
                          $from -= $half_total_links - ($products->lastPage() - $products->currentPage()) - 1;
                      }
                      @endphp
                      @if ($from < $i && $i < $to)
                          <li class="{{ ($products->currentPage() == $i) ? ' active' : '' }}">
                              <a href="{{ $products->url($i) }}">{{ $i }}</a>
                          </li>
                      @endif
                  @endfor
                @endif

                @if ($products->hasMorePages())
                  <li><a href="{{$products->nextPageUrl()}}">Next &raquo;</a></li>
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
<script>
	// Filter Slider
	var sliderUI = document.getElementById('rangeSlider');

	noUiSlider.create(sliderUI, {
		start: [ 0, 1000000 ],
		step: 1000,
		connect: true,
		range: {
			'min': 0,
			'max': 1000000
		}
	});

	var lower = $('#lower');
	var upper = $('#upper');

	sliderUI.noUiSlider.on('update', function( values, handle ) {
		if (handle == 0) {
			lower.html('Rp '+$.number(values[handle]));
			lower.attr('range-data',values[handle]);
		} else if (handle == 1) {
			upper.html('Rp '+$.number(values[handle]));
			upper.attr('range-data',values[handle]);
		}
	});
</script>
<script type="text/javascript">
	$('#btn-filter-harga').on('click',function(){
		add_filter();
	});

	$('.category').on('change',function(){
		add_filter();
	});

	$('.kota').on('change',function(){
		add_filter();
	});

	$('[name=rating]').on('change',function(){
		add_filter();
	});

	function add_filter(){
		$('.col-9').html("<div class='modal-box__loading'></div>");

		var keyword=$('[name="s"]').val();
		var category_list=[];
		$('#categoryContainer :input:checked').each(function(){
			var categories=$(this).val();
			category_list.push(categories);
		});

		var city_list=[];
		$('#cityContainer :input:checked').each(function(){
			var cities=$(this).val();
			city_list.push(cities);
		});

		var rating_list=[];
		$('#ratingContainer :input:checked').each(function(){
			var ratings=$(this).val();
			rating_list.push(ratings);
		});

		var minimum_price=$('#lower').attr('range-data');
		var maximum_price=$('#upper').attr('range-data');

		$('.col-9').html('<div class="loading_filter"></div>');

		$.ajax({
			type:"GET",
			url:"/search_products",
			data:{keyword,category_list,city_list,minimum_price,maximum_price,rating_list},
			success:function(data){
				$('.col-9').html(data);
			},
			complete: function(){
				$('.loading_filter').fadeOut();
			}
		});
	}
</script>
@endsection
