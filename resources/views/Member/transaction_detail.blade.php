@extends('layouts.layout_indikraf')

@section('content')
  <main>
    @include('Member.header_member')
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10">
          <div class="panel panel-default panel-fullscreen">
            <div class="col-8 border-right no-pad">
              <div class="rwd">
                <div class="col-12 border-bottom pad">
                  @php
                    $quantity=0;
                    $total_weight=0;
                    $total_price=0;
                  @endphp
                  @foreach ($transaction as $t)
                    <div class="product-highlight">
                      <div class="product-image">
                        <div class="product-ratio">
                          @php($path="uploads/gambar_produk/".$t->product->seller->user_id."_".$t->product->seller->profile->first_name."/produk".$t->product->product_id)
                          <img src="{{asset($path."/".$t->product->product_images[0]->product_image_name)}}">
                        </div>
                      </div>
                      <div class="product-attributes">
                        <h4>{{$t->product->product_name}}</h4>
                        <p>
                          Rp {{number_format($t->price)}}<br>
                          {{$t->amount." buah"}}<br>
                          {{$t->product->description}}.
                        </p>
                      </div>
                    </div><hr>
                    @php
                      $status=$t->transaction->status;

                      $date=$t->transaction->date_format();
                      $time=$t->transaction->time_format();
                      $quantity+=$t->amount;
                      $total_weight+=$t->product->weight;
                      $total_price=$t->transaction->cart->sum('total_price');
                      $shipping_price=$t->transaction->shipping_price;
                      $payment_method=$t->transaction->payment_method;
                      $courier=$t->transaction->courier;
                      $courier_type=$t->transaction->courier_type;
                      $resi=$t->transaction->tracking_number;

                      $name=$t->transaction->shipping_address->address->first_name." ".$t->transaction->shipping_address->address->last_name;
                      $address=$t->transaction->shipping_address->address;
                      $city=$t->transaction->shipping_address->address->city;
                    @endphp
                  @endforeach
                </div>
                <div class="col-12 border-bottom pad">
                  <table class="table">
                    <tr>
                      <td class="text-left">{!! trans('member/transaction.date') !!}</td>
                      <td class="text-right">{{$date." ".$time}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">{!! trans('member/transaction.quantity') !!}</td>
                      <td class="text-right">{{$quantity}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">{!! trans('member/transaction.total_weight') !!}</td>
                      <td class="text-right">{{number_format($total_weight)}} gr</td>
                    </tr>
                    <tr>
                      <td class="text-left">{!! trans('member/transaction.total_price') !!}</td>
                      <td class="text-right">{{number_format($total_price)}}</td>
                    </tr>
                  </table>
                </div>
                <div class="col-12 pad">
                  <table class="table">
                    <tr>
                      <td class="text-left">{!! trans('member/transaction.shipping_cost') !!}</td>
                      <td class="text-right">{{number_format($shipping_price)}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">{!! trans('member/transaction.total_payment') !!}</td>
                      <td class="text-right text-bold">{{number_format($total_price+$shipping_price)}}</td>
                    </tr>
                    <tr class="separator"></tr>
                    <tr>
                      <td class="text-left">{!! trans('member/transaction.total_price') !!}</td>
                      <td class="text-right">{{number_format($total_price+$shipping_price)}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">{!! trans('member/transaction.payment_method') !!}</td>
                      <td class="text-right">{{$payment_method}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">{!! trans('member/transaction.courier') !!}</td>
                      <td class="text-right">{{$courier}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">{!! trans('member/transaction.courier_type') !!}</td>
                      <td class="text-right">{{$courier_type}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">{!! trans('member/transaction.tracking_number') !!}</td>
                      <td class="text-right">
                        <button type="button" class="text-link" style="border:none; background:none;" data-clipboard-action="copy" data-clipboard-target="#resi">{!! trans('member/transaction.copy') !!}</button>
                        <input type="text" name="resi" id="resi" readonly="" value="{{$resi==NULL?'Belum ada resi':$resi}}" class="resi-field-custom">
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-4 no-pad">
              <div class="col-12 border-bottom pad">
                <h3 class="text-info">{!! trans('member/transaction.destination') !!}</h3>
                <p class="wider">{!! trans('member/transaction.d_name') !!} <br class="hide-sm">{{$name}}</p>
                <p class="wider">{!! trans('member/transaction.d_address') !!} <br class="hide-sm">{{$address->address}}.{{$city->city.", ".$city->province->province.". ".$address->postal_code}}</p>
                <p class="wider">{!! trans('member/transaction.phone') !!} <br clear="hide-sm">{{$address->phone}}</p>
              </div>
              <div class="col-12 pad">
                @php
                  $array_status = array(
                                1=>"Menunggu Pembayaran",
                                2=>"Pembayaran Diterima",
                                3=>"Barang Diproses",
                                4=>"Barang Dikirim",
                                5=>"Selesai"
                            );

                  $index=array_search($status,$array_status);
                @endphp
                <h3 class="text-info">{!! trans('member/transaction.transaction_status') !!}</h3>
                <table class="table">
                  @if ($index!=false)
                    @for ($i = 1; $i <= $index; $i++)
                      <tr>
                        <td class="no-pad">{{$array_status[$i]}}</td>
                        <td class="text-success text-right"><i class="fa fa-check-circle-o"></i></td>
                      </tr>
                    @endfor
                    @for ($i = $index+1; $i <= 6-$index; $i++)
                      <tr>
                        <td class="no-pad">{{$array_status[$i]}}</td>
                        <td class="text-default text-right"><i class="fa fa-check-circle-o"></i></td>
                      </tr>
                    @endfor
                  @else
                    <tr>
                      <td class="no-pad"><font color="red">{{$status}}</font></td>
                      <td class="text-right"><font color="red"><i class="fa fa-close"></i></font></td>
                    </tr>
                  @endif
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>&nbsp;<br>&nbsp;
    </div>
  </main>
@endsection
@section('js')
  <script src="{{asset('clipboard/clipboard.min.js')}}"></script>

  <!-- 3. Instantiate clipboard -->
  <script>
  var clipboard = new Clipboard('.text-link');

  clipboard.on('success', function(e) {
      console.log(e);
  });

  clipboard.on('error', function(e) {
      console.log(e);
  });
  </script>
@endsection
