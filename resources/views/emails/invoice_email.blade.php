<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Indikraf | Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="http://indikraf.com/AdminLTE/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="http://indikraf.com/AdminLTE/dist/css/AdminLTE.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <img src="{{$message->embed("http://indikraf.com/assets/images/logo_small.png")}}" alt="">
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
        <img src="{{$message->embed('AdminLTE/dist/img/credit/visa.png')}}" alt="Visa">
        <img src="{{$message->embed('AdminLTE/dist/img/credit/mastercard.png')}}" alt="Mastercard">
        <img src="{{$message->embed('AdminLTE/dist/img/credit/american-express.png')}}" alt="American Express">
        <img src="{{$message->embed('AdminLTE/dist/img/credit/paypal2.png')}}" alt="Paypal">

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
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
