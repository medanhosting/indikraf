@extends('layouts.layout_indikraf')

@section('content')
  <main>
    @include('Member.header_member')
      <div class="section">
        <div class="col-1 hide-sm"></div>
        <div class="col-10">
          <div class="panel panel-default panel-hide-sm">
            <div class="panel-head">{!! trans('member/transaction.title') !!}</div>
            <div class="panel-body">
              <form class="form-inline history--inline" method="get" action="{{url('/member/search_transaction/')}}">
                <div class="form-group">
                  <input type="text" name="order_id" name="id" placeholder="{!! trans('member/transaction.transaction_code') !!}">
                </div>
                <div class="form-group">
                  <select name="status">
                    <option value="0">{!! trans('member/transaction.all_status') !!}</option>
                    <option>{!! trans('member/transaction.pending') !!}</option>
                    <option>{!! trans('member/transaction.payment_received') !!}</option>
                    <option>{!! trans('member/transaction.processed') !!}</option>
                    <option>{!! trans('member/transaction.shipped') !!}</option>
                    <option>{!! trans('member/transaction.success') !!}</option>
                    <option>{!! trans('member/transaction.canceled') !!}</option>
                  </select>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary form-submit border-radius-lg">Filter</button>
                </div>
              </form>
              <br>
              <table class="table table-striped table-default">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>{!! trans('member/transaction.transaction_code') !!}</th>
                    <th>{!! trans('member/transaction.date') !!}</th>
                    <th>{!! trans('member/transaction.payment_method') !!}</th>
                    <th>Status</th>
                    <th>{!! trans('member/transaction.total_price') !!}</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @php($alim_arizi=($transactions->currentPage()-1)*10+1)
                  @foreach ($transactions as $t)
                    <tr>
                      <th>{{$alim_arizi++}}</th>
                      <td><a href="{{url('/member/transaction_detail/'.$t->transaction->order_id)}}" class="text-link">{{$t->transaction->order_id}}</a></td>
                      <td>{{$t->transaction->date_format()}}</td>
                      <td>{{$t->transaction->payment_method}}</td>
                      <td>{{$t->transaction->status}}</td>
                      <td>{{number_format(($t->transaction->cart->sum('total_price'))+$t->transaction->shipping_price)}}</td>
                      <td><a href="#" class="detail" onclick="detail({{$t->transaction->order_id}})"><img src="{{asset('assets/images/see_detail.png')}}"></a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="table-footer">
                {{$transactions->links()}}
                <span class="record-track pull-right">{!! trans('member/transaction.show') !!} {{count($transactions)}} {!! trans('member/transaction.from') !!} {{$transactions->total()}}</span>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <div class="panel panel-default panel-fullscreen panel-hide-md">
            <div class="panel-heading">
              <form class="form-inline history--inline" method="get" action="{{url('/member/search_transaction/')}}">
                <div class="form-group">
                  <input type="text" name="order_id" placeholder="Kode transaksi">
                </div>
                <div class="form-group">
                  <select name="status">
                    <option value="0">{!! trans('member/transaction.all_status') !!}</option>
                    <option>{!! trans('member/transaction.pending') !!}</option>
                    <option>{!! trans('member/transaction.payment_received') !!}</option>
                    <option>{!! trans('member/transaction.processed') !!}</option>
                    <option>{!! trans('member/transaction.shipped') !!}</option>
                    <option>{!! trans('member/transaction.success') !!}</option>
                    <option>{!! trans('member/transaction.canceled') !!}</option>
                  </select>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary border-radius-lg">Filter</button>
                </div>
              </form>
            </div>
            <div class="panel-body">
              <div class="div-table">
                @foreach ($transactions as $t)
                <div class="table-row-group">
                  <div class="table-row clearfix">
                    <p class="pull-left">{!! trans('member/transaction.transaction_code') !!}</p>
                    <p class="pull-right">{{$t->transaction->date_format()}}</p>
                  </div>
                  <div class="table-row clearfix">
                    <p class="pull-left"><a href="{{url('/member/transaction_detail/'.$t->transaction->order_id)}}" class="text-link">{{$t->transaction->order_id}}</a></p>
                    <p class="pull-right">{{$t->transaction->status}}</p>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <div class="panel-footer text-center">
              @if ($transactions->currentPage()!=$transactions->lastPage())
                <a href="{{$transactions->nextPageUrl()}}" class="btn btn-opaque">Load More</a>
              @endif
            </div>
          </div>
        </div>
      </div>
      <br>&nbsp;<br>&nbsp;
    </div>
  </main>
@endsection
@section('js')
  <script type="text/javascript">
    function detail(id){
      $.ajax({
        type:"get",
        url:"/member/ajax_detail_transaction",
        data:{id:id},
        success:function(salim){
          $('#history_detail #order_id').html(id);
          $('#history_detail .modal-box__body').html(salim);
          // var arizi=JSON.parse(salim);
          // var cart_id=arizi['cart_id'];
          // var order_id=arizi['transaction']['order_id'];
          //
          // var product_id=arizi['product']['product_id'];
          // var product_name=arizi['product']['product_name'];
          // var product_desc=arizi['product']['description'];
          // var product_weight=arizi['product']['weight'];
          // var product_image=arizi['product']['product_images']['0']['product_image_name'];
          //
          //
          // var product_seller_id=arizi['product']['seller']['user_id'];
          // var product_seller_name=arizi['product']['seller']['profile']['first_name'];
          //
          // var transaction_date=arizi['transaction']['created_at'];
          // var quantity=parseInt(arizi['amount']);
          // var total_price=parseInt(arizi['total_price']);
          // var shipping_price=parseInt(arizi['transaction']['shipping_price']);
          // var payment_method=arizi['transaction']['payment_method'];
          // var courier=arizi['transaction']['courier'];
          // var courier_type=arizi['transaction']['courier_type'];
          //
          // var name=arizi['user']['profile']['first_name']+" "+arizi['user']['profile']['last_name'];
          // var address=arizi['transaction']['shipping_address']['address']['address'];
          // var city=arizi['city'];
          // var postal_code=arizi['transaction']['shipping_address']['address']['postal_code'];
          // var phone=arizi['transaction']['shipping_address']['address']['phone'];
          //
          // $('#history_detail #order_id').html(order_id);
          // $('#history_detail #product_name').html(product_name);
          // $('#history_detail #product_desc').html(product_desc);
          // $('#history_detail #product_image').attr('src',"/uploads/gambar_produk/"+product_seller_id+"_"+product_seller_name+"/produk"+product_id+"/"+product_image);
          // $('#history_detail #transaction_date').html(transaction_date);
          // $('#history_detail #quantity').html(quantity);
          // $('#history_detail #weight').html($.number(product_weight)+"gr");
          // $('#history_detail #total_price').html($.number(total_price));
          // $('#history_detail #shipping_price').html($.number(shipping_price));
          // $('#history_detail #total_price_2').html($.number(total_price+shipping_price));
          // $('#history_detail #total_price_3').html($.number(total_price+shipping_price));
          // $('#history_detail #payment_method').html(payment_method);
          // $('#history_detail #courier').html(courier);
          // $('#history_detail #courier_type').html(courier_type);
          //
          // $('#history_detail #name').html(name);
          // $('#history_detail #address').html(address+".<br> "+city['type']+" "+city['city_name']+", "+city['province']+" "+postal_code);
          // $('#history_detail #phone').html(phone);

        },
        complete: function(){
          $('.modal-box__loading').fadeOut();
        },
        error:function(request,status,error){
          $('.modal-box__error').fadeIn();
          $('.modal-box__error__text').html(error);
        }
      })
    }
  </script>

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
