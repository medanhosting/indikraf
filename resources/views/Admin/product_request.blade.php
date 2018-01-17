@extends('layouts.layout_admin_lte')

@section('content_header','Toko')

@section('content')

<div id="delete" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Hapus request</h4>
			</div>
			<div class="modal-body">
				Apakah Anda yakin ingin menghapus request ini?
			</div>
			<div class="modal-footer">
				<a class="btn btn-danger btn-hapus" href="">Hapus</a>
				<button class="btn btn-default" data-dismiss="modal">Batal</button>
			</div>
		</div>
	</div>
</div>

<div class="col-md-12">
    <div class="box box-success">
        <div class="box-header with-border"><h4 class="box-title">Produk dari User</h4></div>
        <div class="box-body">
					@if (Session::has('status_delete'))
						<div class="alert alert-info alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_delete')}}
						</div>
					@endif
          <table class="table table-responsive table-striped table-hovered table-bordered" id="example2">
            <thead>
              <tr>
                <th>No.</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Tipe Daftar</th>
                <th>URL</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php($n=1)
              @foreach ($products as $b)
                <tr>
                  <td>{{$n++}}</td>
                  <td>
										{{$b->email}}
										@if (array_search($b->request_id,$new_store)!=false)
                      <small class="label bg-green">new</small>
                    @endif
									</td>
                  <td>{{$b->phone}}</td>
                  <td>{{$b->type}}</td>
                  <td>
                    @if ($b->type=="Image")
                      <img src="{{asset($b->url)}}" width="200px">
                    @else
                      <a target="_blank" href="{{"http://".$b->url}}">{{$b->url}}</a>
                    @endif
                  </td>
                  <td>
										<button class="btn btn-danger" data-toggle="modal" data-target="#delete" onclick="delete_request({{$b->request_id}})"><i class="fa fa-close"></i></button> 
										<form action="{{url('admin/store')}}" method="post">
											{{ csrf_field() }}
											<input type="hidden" name="email" value="{{$b->email}}">
											<input type="hidden" name="phone" value="{{$b->phone}}">
											<button type="submit" class="btn btn-primary">Input Toko</button>
										</form>
									</td>
                </tr>
              @endforeach
							@foreach ($user->unreadNotifications->where('type','App\Notifications\NewStoreRegister') as $n)
								@php($n->markAsRead())
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

  function delete_request(id){
    $('#delete .modal-footer .btn-hapus').attr('href','/admin/delete_submit_product/'+id);
  }
</script>
@endsection
