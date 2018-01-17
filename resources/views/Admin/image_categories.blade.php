@extends('layouts.layout_admin_lte')

@section('content_header','Kategori Galeri')

@section('content')
  <!--Modal Edit kategori-->
  <div id="edit" class="modal fade" role="dialog">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Edit Kategori</h4>
  			</div>
  			<div class="modal-body">
          @if (Session::has('status_edit_category'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_edit_category')}}
            </div>
          @endif
          <form id="edit_form" action="{{url('/admin/edit_image_category')}}" method="post">
              <label for="category_name" class="control-label">Nama Kategori</label>
              <input type="text" name="category_name" class="form-control" placeholder="Nama kategori" required><br>
              {{csrf_field()}}
              <input type="hidden" name="category_id" class="form-control" placeholder="category_id"><br>
  			</div>
  			<div class="modal-footer">
  					<button type="submit" class="btn btn-success">Edit</button>
  					<button class="btn btn-default" data-dismiss="modal">Batal</button>
  				</form>
  			</div>
  		</div>
  	</div>
  </div>
  <!--End Modal Edit kategori-->

  <!--Modal Hapus kategori-->
  <div id="delete" class="modal fade" role="dialog">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Hapus kategori</h4>
  			</div>
  			<div class="modal-body">
  				Apakah Anda yakin ingin menghapus kategori:
  			</div>
  			<div class="modal-footer">
  				<a class="btn btn-danger btn-hapus" href="{{url('/admin/delete_image_category/')}}">Hapus</a>
  				<button class="btn btn-default" data-dismiss="modal">Batal</button>
  			</div>
  		</div>
  	</div>
  </div>
<!--End Modal Hapus kategori-->

  <div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border"><h4 class="box-title">Kategori</h4></div>
                <div class="box-body">
                    @if (Session::has('status_image_category'))
                      <div class="alert alert-info alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_image_category')}}
                      </div>
                    @endif

                    <form action="{{url('/admin/add_image_category')}}" method="post">
                      {{ csrf_field() }}
                      <input type="text" class="form-control" name="category_name" placeholder="Nama Kategori" required><br>
                      <button type="submit" class="btn btn-primary pull-right">Simpan Kategori</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border"><h4 class="box-title">Kategori Galeri di Indikraf</h4></div>
                <div class="box-body">
                  <table class="table table-striped" id="example2">
                    <thead>
                      <tr>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($image_category as $c)
                        <tr>
                          <td>{{$c->image_category_name}}</td>
                          <td>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#edit" onclick="ajax_ed('edit',{{$c->image_category_id}})"><i class="fa fa-edit"></i> Edit</button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete" onclick="ajax_ed('delete',{{$c->image_category_id}})"><i class="fa fa-close"></i> Hapus</button>
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
  @if (Session::has('status_edit_category'))
    $(window).on('load',function(){
        $('#edit').modal('show');
        ajax_ed('edit',{{Session::get('category_id')}});
    });
  @endif

  function ajax_ed(jenis,id){
    $.ajax({
      type:"get",
      url:"/admin/ajax_ed_image_category",
      data:{'id':id},
      success:function(data){
        if(jenis=="edit"){
          p_edit(id,data);
        }else if(jenis=="delete"){
          p_delete(id,data);
        }
      },
      error:function(data){
        alert('error '+data);
      }
    });
  }

  function p_edit(id,data){
    var category=JSON.parse(data);
    var category_name=category['image_category_name'];

    $('#edit [name="category_id"]').val(id);
    $('#edit [name="category_name"]').val(category_name);
  }

  function p_delete(id,data){
    var category=JSON.parse(data);
    var category_name=category['image_category_name'];

    $('#delete .modal-body').html("Apakah Anda yakin ingin menghapus Kategori: <label class='label label-danger'>"+category_name+"</label><br> <font color='red'>Note: Menghapus Kategori akan menghapus gambar yang termasuk kategori ini</font>");
    $('#delete .modal-footer .btn-hapus').attr('href','/admin/delete_image_category/'+id);
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
