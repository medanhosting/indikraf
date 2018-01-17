@extends('layouts.layout_admin_lte')

@section('content_header','Transaksi')

@section('content')
<div class="col-md-12">
    <div class="box box-success">
        <div class="box-header with-border"><h4 class="box-title">Transaksi</h4></div>
        <div class="box-body">
          <table class="table table-responsive table-striped table-hovered table-bordered" id="example2">
            <thead>
              <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Order Id</th>
                <th>Pemesan</th>
                <th>Jumlah Barang</th>
                <th>Total Pembayaran</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
                <th>Detail</th>
              </tr>
            </thead>
            <tbody>
              @php($n=1)
              @foreach ($transactions as $t)
                <tr>
                  <td>{{$n++}}</td>
                  <td>{{$t->date_format()}}</td>
                  <td>
                    {{$t->order_id}}
                    @if (array_search($t->order_id,$new_order)!=false)
                      <small class="label pull-right bg-green">new</small>
                    @endif
                  </td>
                  <td>{{$t->buyer->profile->first_name." ".$t->buyer->profile->last_name}}</td>
                  <td>{{$t->amount}}</td>
                  <td>{{number_format($t->cart->sum('total_price')+$t->shipping_price)}}</td>
                  <td>{{$t->payment_method}}</td>
                  <td><small class="label {{$t->status!='Dibatalkan'?'label-success':'label-danger'}}">{{$t->status}}</small></td>
                  <td><a href="{{url('/admin/transaction/'.$t->order_id)}}" class="btn btn-primary">Detail</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
    </div>
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
