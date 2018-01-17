@extends('layouts.layout_admin_lte')

@section('content_header','Toko')

@section('content')
  <!--Modal Edit produk-->
  <div id="edit" class="modal fade" role="dialog">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Edit produk</h4>
  			</div>
  			<div class="modal-body">
          @if (Session::has('status_edit_store'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_edit_store')}}
            </div>
          @endif
          <form id="edit_form" action="{{url('/admin/edit_store/')}}" method="post">
              {{csrf_field()}}
              <input type="hidden" name="store_id" class="form-control" placeholder="store_id"><br>
              <label for="store_name" class="control-label">Nama Toko</label>
              <input type="text" name="store_name" class="form-control" placeholder="Nama produk"><br>
              <label class="control-label">Alamat Toko</label>
              <textarea name="store_address" class="form-control" rows="8" style="resize:none" placeholder="Alamat Toko"></textarea><br>
              <label class="control-label">Provinsi</label>
              <select class="form-control province" name="province">
                <option value="" disabled selected>Pilih Provinsi</option>
                @foreach ($province as $p)
                  <option value="{{$p['province_id']}}">{{$p['province']}}</option>
                @endforeach
              </select><br>
              <label class="control-label">Kota/Kabupaten</label>
              <select class="form-control city" name="store_city">
                <option value="" disabled selected>Pilih Kabupaten/Kota</option>
              </select><br>
              <label class="control-label">Kode Pos</label>
              <input type="text" class="form-control" name="store_postal_code" placeholder="Kode Pos"><br>
              <label class="control-label">Email</label>
              <input type="text" class="form-control" name="store_email" placeholder="Email Toko"><br>
              <label class="control-label">Telepon</label>
              <input type="text" class="form-control" name="store_phone" placeholder="Telepon Toko">
  			</div>
  			<div class="modal-footer">
  					<button type="submit" class="btn btn-success">Edit</button>
  					<button class="btn btn-default" data-dismiss="modal">Batal</button>
  				</form>
  			</div>
  		</div>
  	</div>
  </div>
  <!--End Modal Edit produk-->

  <!--Modal Hapus produk-->
  <div id="hapus" class="modal fade" role="dialog">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Hapus produk</h4>
  			</div>
  			<div class="modal-body">
  				Apakah Anda yakin ingin menghapus produk:
  			</div>
  			<div class="modal-footer">
  				<a class="btn btn-danger btn-hapus" href="{{url('/admin/delete_product/')}}">Hapus</a>
  				<button class="btn btn-default" data-dismiss="modal">Batal</button>
  			</div>
  		</div>
  	</div>
  </div>
<!--End Modal Hapus produk-->

  <div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border"><h4 class="box-title">Toko</h4></div>
                <div class="box-body">
                    @if (Session::has('status_add_store'))
                      <div class="alert alert-info alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_add_store')}}
                      </div>
                    @endif
                    @if (Session::has('status_delete_store'))
                      <div class="alert alert-info alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_delete_store')}}
                      </div>
                    @endif
                    <form action="{{url('/admin/add_store')}}" method="post">
                      {{ csrf_field() }}
                      <input type="text" class="form-control" name="store_name" placeholder="Nama Toko"><br>
                      <textarea name="store_address" class="form-control" rows="8" placeholder="Alamat Lengkap Toko"></textarea><br>
                      <select class="form-control province" name="province">
                        <option value="" disabled selected>Pilih Provinsi</option>
                        @foreach ($province as $p)
                          <option value="{{$p['province_id']}}">{{$p['province']}}</option>
                        @endforeach
                      </select><br>
                      <select class="form-control city" name="store_city">
                        <option value="" disabled selected>Pilih Kota/Kabupaten</option>
                      </select><br>
                      <input type="text" class="form-control" name="store_postal_code" placeholder="Kode Pos Toko"><br>
                      <input type="text" class="form-control" name="store_email" placeholder="Email Toko" value="{{$email}}"><br>
                      <input type="text" class="form-control" name="store_phone" placeholder="Nomor Telepon Toko" value="{{$phone}}"><br>
                      <button type="submit" class="btn btn-primary pull-right">Simpan Toko</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border"><h4 class="box-title">Toko Yang sudah terdaftar di Indikraf</h4></div>
                <div class="box-body">
                  <table class="table table-striped" id="example2">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Toko</th>
                        <th>Produk</th>
                        <th>Alamat</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php($salim=1)
                      @foreach ($store as $s)
                        <tr>
                          <td>{{$salim++}}</td>
                          <td>{{$s->store_name}}</td>
                          <td>{{number_format(count($s->products))}}</td>
                          <td>{{$s->store_address}}</td>
                          <td>{{$s->store_email}}</td>
                          <td>{{$s->store_phone}}</td>
                          <td>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#edit" onclick="ajax_ed('edit',{{$s->store_id}})"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapus" onclick="ajax_ed('hapus',{{$s->store_id}})"><i class="fa fa-close"></i></button>
                            <a href="{{url('/admin/store_products/'.$s->store_id)}}" class="btn btn-default">Lihat Produk</a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection

@section('js')
  <script type="text/javascript">
  @if (Session::has('status_edit_store'))
    $(window).on('load',function(){
        $('#edit').modal('show');
        ajax_ed('edit',{{Session::get('store_id')}});
    });
  @endif

  $('.province').on('change',function(e){
    ambil_ajax('provinsi','.city',$(this).val());
  });

  function ambil_ajax(j,k,prov){
    $.ajax({
      type:"get",
      url:"/ambil_lokasi",
      data:{jenis:j,prov:prov},
      success:function(e){
        $(k).html(e);
      },
      error:function(e){}
    });
  }

  function ajax_ed(jenis,id){
    $.ajax({
      type:"get",
      url:"/admin/ajax_ed_store",
      data:{'id':id},
      success:function(data){
        if(jenis=="edit"){
          p_edit(id,data);
        }else if(jenis=="hapus"){
          p_delete(id,data);
        }
      },
      error:function(data){
        alert('error '+data);
      }
    });
  }

  function p_edit(id,data){
    var store=JSON.parse(data);
    var store_name=store['store_name'];
    var store_address=store['store_address'];
    var store_postal_code=store['store_postal_code'];
    var store_email=store['store_email'];
    var store_phone=store['store_phone'];

    $('#edit [name="store_id"]').val(id);
    $('#edit [name="store_name"]').val(store_name);
    $('#edit [name="store_address"]').val(store_address);
    $('#edit [name="store_postal_code"]').val(store_postal_code);
    $('#edit [name="store_email"]').val(store_email);
    $('#edit [name="store_phone"]').val(store_phone);
  }

  function p_delete(id,data){
    var store=JSON.parse(data);
    var store_name=store['store_name'];

    $('#hapus .modal-body').html("Apakah Anda yakin ingin menghapus toko: <label class='label label-danger'>"+store_name+"</label>");
    $('#hapus .modal-footer .btn-hapus').attr('href','/admin/delete_store/'+id);
  }

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
