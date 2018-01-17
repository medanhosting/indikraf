@extends('layouts.layout_indikraf')

@section('content')
    <div class="page-head page-head--nobg">
      <div class="page-head__title">
        <h1>{!! trans('front/shopping_cart.title') !!}</h1>
      </div>
    </div>
    @if (count($cart))
    <div class="container container--gray">
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10 no-pad">
          <div class="cart-wrapper clearfix">
            <div class="cart-section--8">
              <div class="panel panel-default panel-fullscreen panel--cart">
                <form action="{{url('/update_cart')}}" method="post">
                  {{csrf_field()}}
                  <div class="panel-body no-pad">
                    <table class="table table--cart table--large">
                      <thead class="thead-hide-sm">
                        <th>{!! trans('front/shopping_cart.col_product') !!}</th>
                        <th></th>
                        <th width="100px">{!! trans('front/shopping_cart.col_price') !!} @</th>
                        <th>{!! trans('front/shopping_cart.col_amount') !!}</th>
                      </thead>
                      <tbody>
                        @php
                          $total_quantity=0;
                          $total_price=0;
                          $total_weight=0;
                        @endphp
                        @if (Auth::guest())
                          @foreach ($cart as $c)
                            @php
                              $p=App\Models\Product::find($c->id)
                            @endphp
                            <tr class="text-center">
                              <td class="table-cart-produk">
                                @if (count($p->product_images)!=0)
                                  @php($path="uploads/gambar_produk/".$p->seller->user_id."_".$p->seller->profile->first_name."/produk".$p->product_id)
                                    <img class="img-responsive" src="{{asset($path."/".$p->product_images[0]->product_image_name)}}" style="max-height:118px">
                                @else
                                  <img src="http://placehold.it/320x150" alt="">
                                @endif
                              </td>
                              <td class="table-cart-name">
                                <a href="{{url('/products/detail_product/'.$p->slug)}}"><h3>{{$p->product_name}}</h3></a>
                                <span class="hide-sm">{{str_limit($p->description, $limit = 50, $end = '...')}}</span>
                                <h3 class="price">Rp {{number_format($p->price)}}</h3>
                              </td>
                              <td class="table-cart-price">Rp {{number_format($p->price)}}</td>
                              <td class="table-cart-quantity">
                                <p class="cancel"><button class="btn btn-close"><i class="fa fa-close"></i></button></p>
                                <br>
                                <p class="label">{!! trans('front/shopping_cart.col_amount') !!}</p>
                                <div class="quantity">
                                  <input type="number" name="quantity[{{$p->product_id}}]" min="0" max="{{$p->stock}}" step="1" class="border-radius-lg" value="{{$c->quantity}}">
                                </div>
                              </td>
                            </tr>
                            @php
                              $total_quantity+=$c->quantity;
                              $total_weight+=($p->weight*$p->quantity);
                              $total_price+=$p->price*$c->quantity;
                            @endphp
                          @endforeach

                        @else

                          @foreach ($cart as $k=>$v)
                            @php($store_id=$v->store_id)
                            <tr class="text-center">
                              <td class="table-cart-produk">
                                @if (count($v->product->product_images)!=0)
                                  @php($path="uploads/gambar_produk/".$v->product->seller->user_id."_".$v->product->seller->profile->first_name."/produk".$v->product->product_id)
                                    <img class="img-responsive" src="{{$path."/".$v->product->product_images[0]->product_image_name}}" style="max-height:118px">
                                @else
                                  <img src="http://placehold.it/320x150" alt="">
                                @endif
                              </td>
                              <td class="table-cart-name">
                                <a href="{{url('/products/detail_product/'.$v->product->slug)}}"><h3>{{$v->product->product_name}}</h3></a>
                                <span class="hide-sm">{{str_limit($v->product->description, $limit = 50, $end = '...')}}</span>
                                <h3 class="price">Rp {{number_format($v->product->price)}}</h3>
                              </td>
                              <td class="table-cart-price">Rp {{number_format($v->product->price)}}</td>
                              <td class="table-cart-quantity">
                                <p class="cancel"><button class="btn btn-close"><i class="fa fa-close"></i></button></p>
                                <br>
                                <p class="label">{!! trans('front/shopping_cart.col_amount') !!}</p>
                                <div class="quantity">
                                  <input type="number" name="quantity[{{$v->product->product_id}}]" min="0" max="{{$v->product->stock}}" step="1" class="border-radius-lg" value="{{$v->amount}}">
                                </div>
                              </td>
                            </tr>
                            @php
                              $total_quantity+=$v->amount;
                              $total_weight+=($v->product->weight*$v->amount);
                              $total_price+=$v->product->price*$v->amount;
                            @endphp

                            @if ($loop->remaining!=0)
                              @if ($v->product->store_id!=$cart[$k+1]->product->store_id)
                                            </tbody>
                                          </table>
                                        </div>
                                        <div class="panel-footer panel-hide-sm">
                                          <div class="panel-submit-wrapper">
                                            <button class="btn btn-primary btn-panel-submit">Update {!! trans('front/shopping_cart.cart') !!}</button>
                                          </div>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                  <div class="cart-section--4">
                                    <div class="panel panel-default panel-fullscreen panel--cart">
                                      <div class="panel-body">
                                        <div class="summary">
                                          <div class="summary-item">
                                            <span class="summary-item-left">{!! trans('front/shopping_cart.product_total') !!}</span>
                                            <span class="summary-item-right">{{$total_quantity}}</span>
                                          </div>
                                          <div class="summary-item">
                                            <span class="summary-item-left">{!! trans('front/shopping_cart.weight_total') !!}</span>
                                            <span class="summary-item-right">{{number_format($total_weight)}} gr</span>
                                          </div>
                                          <div class="summary-item">
                                            <span class="summary-item-left">{!! trans('front/shopping_cart.price_total') !!}</span>
                                            <span class="summary-item-right">Rp {{number_format($total_price)}}</span>
                                          </div>
                                          <br>
                                        </div>
                                      </div>
                                      <div class="panel-footer">
                                        <div class="panel-submit-wrapper">
                                          <a href="{{url('/products')}}" class="btn btn-opaque btn-panel-submit btn-hide-md">{!! trans('front/shopping_cart.shopping') !!}</a>
                                          <a href="{{url('/checkout/'.$store_id)}}" class="btn btn-primary btn-panel-submit">Check Out</a>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="cart-wrapper clearfix">
                                  <div class="cart-section--8">
                                    <div class="panel panel-default panel-fullscreen panel--cart">
                                      <form action="{{url('/update_cart')}}" method="post">
                                        {{csrf_field()}}
                                        <div class="panel-body no-pad">
                                          <table class="table table--cart table--large">
                                            <thead class="thead-hide-sm">
                                              <th>{!! trans('front/shopping_cart.col_product') !!}</th>
                                              <th></th>
                                              <th width="100px">{!! trans('front/shopping_cart.col_price') !!} @</th>
                                              <th>{!! trans('front/shopping_cart.col_amount') !!}</th>
                                            </thead>
                                            <tbody>
                                              @php
                                                $total_quantity=0;
                                                $total_price=0;
                                                $total_weight=0;
                                              @endphp
                              @endif
                            @endif

                          @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                  <div class="panel-footer panel-hide-sm">
                    <div class="panel-submit-wrapper">
                      <button class="btn btn-primary btn-panel-submit">Update {!! trans('front/shopping_cart.cart') !!}</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="cart-section--4">
              <div class="panel panel-default panel-fullscreen panel--cart">
                <div class="panel-body">
                  <div class="summary">
                    <div class="summary-item">
                      <span class="summary-item-left">{!! trans('front/shopping_cart.product_total') !!}</span>
                      <span class="summary-item-right">{{$total_quantity}}</span>
                    </div>
                    <div class="summary-item">
                      <span class="summary-item-left">{!! trans('front/shopping_cart.weight_total') !!}</span>
                      <span class="summary-item-right">{{number_format($total_weight)}} gr</span>
                    </div>
                    <div class="summary-item">
                      <span class="summary-item-left">{!! trans('front/shopping_cart.price_total') !!}</span>
                      <span class="summary-item-right">Rp {{number_format($total_price)}}</span>
                    </div>
                    <br>
                  </div>
                </div>
                <div class="panel-footer">
                  <div class="panel-submit-wrapper">
                    <a href="{{url('/products')}}" class="btn btn-opaque btn-panel-submit btn-hide-md">{!! trans('front/shopping_cart.shopping') !!}</a>
                    @if (Auth::guest())
                      <a href="{{url('/checkout/0')}}" class="btn btn-primary btn-panel-submit">Check Out</a>
                    @else
                      <a href="{{url('/checkout/'.$store_id)}}" class="btn btn-primary btn-panel-submit">Check Out</a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>&nbsp;<br>&nbsp;
    </div>
    @else
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10">
          <div class="panel panel-defaul panel-fullscreen panel--cart">
            <div class="panel-body">
              <h2 class="text-primary text-center">{!! trans('front/shopping_cart.empty_cart') !!}</h2>
            </div>
          </div>
        </div>
      </div>
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10">
          <h2>&nbsp; {!! trans('front/shopping_cart.recommendation') !!}</h2>
          <div class="rwd">
            @foreach ($products as $p)
              <div class="product-wrap-rating">
                <a href="{{url('/products/detail_product/'.$p->slug)}}">
                  <div class="product-wrap-rating--padding">
                    <div class="product-wrap-rating__ratio">
                      @if (count($p->product_images)!=0)
    										@php($path="uploads/gambar_produk/".$p->seller->user_id."_".$p->seller->profile->first_name."/produk".$p->product_id)
    											<img src="{{$path."/".$p->product_images[0]->product_image_name}}">
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
      </div>
    @endif
@endsection
@section('js')
  <script src="{{asset('assets/javascripts/input-number.js')}}"></script>
@endsection
