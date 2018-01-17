@extends('layouts.layout_admin_lte')

@section('content_header','Video')

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
          @if (Session::has('status_edit_video'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_edit_video')}}
            </div>
          @endif
          <form id="edit_form" action="{{url('/admin/edit_video')}}" method="post">
              <label for="video" class="control-label">Judul Video</label>
              <input type="text" name="title" class="form-control" placeholder="Title" required><br>
              <label for="video" class="control-label">Link Video</label>
              <input type="text" name="video_url" class="form-control" placeholder="Link Video" required><br>
              <label for="video" class="control-label">Deskripsi Video</label>
              <textarea name="description" class="form-control" placeholder="Deskripsi" required></textarea>
              {{csrf_field()}}
              <input type="hidden" name="video_id" class="form-control" placeholder="video_id"><br>
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
  				<h4 class="modal-title">Hapus Video</h4>
  			</div>
  			<div class="modal-body">
  				Apakah Anda yakin ingin menghapus video:
  			</div>
  			<div class="modal-footer">
  				<a class="btn btn-danger btn-hapus" href="{{url('/admin/delete_video/')}}">Hapus</a>
  				<button class="btn btn-default" data-dismiss="modal">Batal</button>
  			</div>
  		</div>
  	</div>
  </div>
<!--End Modal Hapus kategori-->

<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Video</div>
                <div class="panel-body">
                  <div class="rows">
                    <div class="col-md-12">
                      @if (Session::has('status_video'))
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_video')}}
                        </div>
                      @endif
                      <form action="{{url('/admin/add_videos')}}" method="post">
                        {{ csrf_field() }}
                        <label for="video" class="control-label">Judul Video</label>
                        <input type="text" name="title" class="form-control" placeholder="Title" required><br>
                        <label for="video" class="control-label">Link Video</label>
                        <input type="text" name="video_url" class="form-control" placeholder="Link Video" required><br>
                        <label for="video" class="control-label">Deskripsi Video</label>
                        <textarea name="description" rows="8" class="form-control" placeholder="Tulis deskripsi disini" required></textarea><br>
                        <button type="submit" class="btn btn-primary">Submit Videos</button>
                      </form>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Video sebelumnya</div>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-12">
                      <table class="table table-striped" id="example2">
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th>Url/Link Video</th>
                            <th>Tanggal</th>
                            <th>Hapus</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php($salim=1)
                          @foreach ($videos as $v)
                            <tr>
                              <td>{{$salim++}}</td>
                              <td>{{$v->video_url}}</td>
                              <td>{{$v->date_format()}}</td>
                              <td>
                                <button class="btn btn-success" data-toggle="modal" data-target="#edit" onclick="ajax_ed('edit',{{$v->video_id}})"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#delete" onclick="ajax_ed('delete',{{$v->video_id}})"><i class="fa fa-close"></i></button>
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
    </div>
</div>
@endsection

@section('js')

<script type="text/javascript">
  @if (Session::has('status_edit_video'))
    $(window).on('load',function(){
        $('#edit').modal('show');
        ajax_ed('edit',{{Session::get('video_id')}});
    });
  @endif
  $('#example2').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false
  });

  function ajax_ed(jenis,id){
    $.ajax({
      type:"get",
      url:"/admin/ajax_ed_video",
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
    var video=JSON.parse(data);
    var video_url=video['video_url'];
    var title=video['title'];
    var description=video['description'];

    $('#edit [name="video_id"]').val(id);
    $('#edit [name="video_url"]').val(video_url);
    $('#edit [name="title"]').val(title);
    $('#edit [name="description"]').val(description);
  }

  function p_delete(id,data){
    var video=JSON.parse(data);
    var video_url=video['video_url'];

    $('#delete .modal-body').html("Apakah Anda yakin ingin menghapus Kategori: <label class='label label-danger'>"+video_url+"</label>");
    $('#delete .modal-footer .btn-hapus').attr('href','/admin/delete_video/'+id);
  }
</script>
@endsection
