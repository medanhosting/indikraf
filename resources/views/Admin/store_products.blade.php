@extends('layouts.layout_admin_lte')

@section('content_header')
	Produk di {{$store->store_name}}
@stop

@section('content')

<div class="col-md-12">
    <div class="box box-success">
        <div class="box-header with-border"><h4 class="box-title">Produk dari Toko {{$store->store_name}}</h4></div>
        <div class="box-body">
          <table class="table table-responsive table-striped table-hovered table-bordered" id="example2">
            <thead>
              <tr>
                <th>No.</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Berat</th>
                <th>Stok</th>
              </tr>
            </thead>
            <tbody>
              @php($n=1)
              @foreach ($products as $b)
                <tr>
                  <td>{{$n++}}</td>
                  <td>
										{{$b->product_name}}
									</td>
                  <td>{{$b->price}}</td>
                  <td>{{number_format($b->weight)}}</td>
                  <td>{{number_format($b->stock)}}</td>
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
