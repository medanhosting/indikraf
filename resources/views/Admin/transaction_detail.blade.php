@extends('layouts.layout_admin_lte')

@section('content_header')
  ORDER ID: <small><b>{{$transaction->order_id}}</b></small>
@stop

@section('content')
  <div id="tracking_number" class="modal fade" role="dialog">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Tracking Number untuk Order: {{$transaction->order_id}}</h4>
  			</div>
  			<div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <label>Tracking Number</label>
              <form action="{{url('/admin/change_status_transaction')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="order_id" value="{{$transaction->order_id}}">
              <input type="text" name="tracking_number" class="form-control" placeholder="Masukkan tracking number" required>
            </div>
          </div>
  			</div>
  			<div class="modal-footer">
              <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
  					<button class="btn btn-default" data-dismiss="modal">Batal</button>
  				</form>
  			</div>
  		</div>
  	</div>
  </div>
<div class="col-md-12">
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <i class="fa fa-globe"></i> Indikraf
          <small class="pull-right">Date: {{$transaction->date_format()}}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        From
        @php($from=$transaction->cart[0]->product->seller)

        <address>
          <strong>{{$from->profile->first_name." ".$from->profile->last_name}}</strong><br>
          {{$from->address[0]->address}}<br>
          @php($af=$from->address[0]->city)
          {{$af->city}}, {{$af->province->province}} {{$from->address[0]->postal_code}}<br>
          Phone: {{$from->address[0]->phone}}<br>
          Email: {{$from->email}}
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        To
        @php($to=$transaction->shipping_address->address)
        <address>
          <strong>{{$to->first_name." ".$to->last_name}}</strong><br>
          {{$to->address}}<br>
          @php($at=$to->city)
          {{$at->city}}, {{$at->province->province}} {{$to->postal_code}}<br>
          Phone: {{$to->phone}}<br>
          Email: {{$to->user->email}}
        </address>
      </div>
      <!-- /.col -->
      @php
        $status = array(
                      1=>"Menunggu Pembayaran",
                      2=>"Pembayaran Diterima",
                      3=>"Barang Diproses",
                      4=>"Barang Dikirim",
                      5=>"Selesai"
                  );

        $setStatus = array(
                      1=>"Konfirmasi Pembayaran",
                      2=>"Proses Barang",
                      3=>"Kirim Barang",
                      4=>"Selesai"
                  );

        $index=array_search($transaction->status,$status);

        $icons = array(
                  "fa-hourglass-start",
                  "fa-credit-card",
                  "fa-gift",
                  "fa-truck",
                  "fa-check"
                );
      @endphp
      <div class="col-sm-4 invoice-col">
        <b>Invoice #{{$transaction->transaction_id}}</b><br>
        <br>
        <b>Order ID:</b> {{$transaction->order_id}}<br>
        <b>Account:</b> {{$transaction->buyer->user_id}}<br>
        <b>Transaction Status:</b> <small class="label {{$transaction->status!='Dibatalkan'?'label-success':'label-danger'}}"><i class="fa {{$transaction->status!='Dibatalkan'?$icons[$index-1]:''}}"></i> {{$transaction->status}}</small>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Qty</th>
              <th>Product</th>
              <th>Description</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($transaction->cart as $c)
              <tr>
                <td>{{$c->amount}}</td>
                <td>{{$c->product->product_name}}</td>
                <td>{{$c->product->description}}</td>
                <td>Rp {{number_format($c->total_price)}}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-6">
        <p class="lead">Payment Methods:</p>
        <img src="{{asset('AdminLTE/dist/img/credit/visa.png')}}" alt="Visa">
        <img src="{{asset('AdminLTE/dist/img/credit/mastercard.png')}}" alt="Mastercard">
        <img src="{{asset('AdminLTE/dist/img/credit/american-express.png')}}" alt="American Express">
        <img src="{{asset('AdminLTE/dist/img/credit/paypal2.png')}}" alt="Paypal">

        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
          Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
          dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
        </p>
      </div>
      <!-- /.col -->
      <div class="col-xs-6">
        <p class="lead">Amount Due {{$transaction->date_format()}}</p>

        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Subtotal:</th>
              <td>Rp {{number_format($total_price=$transaction->cart->sum('total_price'))}}</td>
            </tr>
            <tr>
              <th>Shipping:</th>
              <td>Rp {{number_format($shipping_price=$transaction->shipping_price)}}</td>
            </tr>
            <tr>
              <th>Total:</th>
              <td>Rp {{number_format($total_price+$shipping_price)}}</td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
      <div class="col-xs-12">
        <a href="{{url('/admin/transaction_detail_print/'.$transaction->order_id)}}" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>

        @if ($index!=false)
          @if ($index==5)
            <button type="button" disabled class="btn btn-success pull-right">Transaksi sudah sukses</button>
          @else
            @if ($index==3)
              <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#tracking_number">
                  <i class="fa {{$icons[$index]}}"></i> {{$setStatus[$index]}}
              </button>
            @else
              <form class="" action="{{url('/admin/change_status_transaction/')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="order_id" value="{{$transaction->order_id}}">
                <button type="submit" class="btn btn-success pull-right">
                    <i class="fa {{$icons[$index]}}"></i> {{$setStatus[$index]}}
                </button>
              </form>
            @endif
            <a href="{{url('/admin/cancel_transaction/'.$transaction->order_id)}}" class="btn btn-danger pull-right" style="margin-right: 5px;">
              <i class="fa fa-close"></i> Batalkan Transaksi
            </a>
          @endif
        @endif
      </div>
    </div>
  </section>
</div>
@endsection

@section('js')
<script type="text/javascript">
  $('#example2').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false
  });
</script>
@endsection
