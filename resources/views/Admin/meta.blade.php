@extends('layouts.layout_admin_lte')

@section('content_header','FAQ')

@section('content')
  <!--Modal Edit faq-->
  <div id="edit" class="modal fade" role="dialog">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Edit faq</h4>
  			</div>
  			<div class="modal-body">
          @if (Session::has('status_edit_faq'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_edit_faq')}}
            </div>
          @endif
          <form id="edit_form" action="{{url('/admin/edit_meta/')}}" method="post">
              {{csrf_field()}}
              <input type="hidden" name="page_id" class="form-control" value="">
              <div class="form-group">
                <label for="title" class="control-label">Title</label>
                <input type="text" name="title" class="form-control" placeholder="Title">
              </div>
              <div class="form-group">
                <label for="keyword" class="control-label">Keyword</label>
                <textarea name="keyword" rows="2" class="form-control" placeholder="Keyword"></textarea>
              </div>
              <div class="form-group">
                <label for="description" class="control-label">Description</label>
                <textarea name="description" rows="2" class="form-control" placeholder="Description"></textarea>
              </div>
  			</div>
  			<div class="modal-footer">
  					<button type="submit" class="btn btn-success">Edit</button>
  					<button class="btn btn-default" data-dismiss="modal">Batal</button>
  				</form>
  			</div>
  		</div>
  	</div>
  </div>
  <!--End Modal Edit faq-->

    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
              <div class="box-header with-border"><h4 class="box-title">Halaman Indikraf</h4></div>
              <div class="box-body">
                <table class="table table-striped" id="example2">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nama Halaman</th>
                      <th>Title</th>
                      <th>Keyword</th>
                      <th>Description</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  @php($arizi=1)
                  @foreach ($meta as $m)
                    <tr>
                      <td>{{$arizi++}}</td>
                      <td>{{$m->page_name}}</td>
                      <td>{{$m->title}}</td>
                      <td>{{$m->keyword}}</td>
                      <td>{{$m->description}}</td>
                      <td>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#edit" onclick="ajax_ed_meta({{$m->page_id}})"><i class="fa fa-edit"></i></button>
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
  @if (Session::has('status_edit_faq'))
    $(window).on('load',function(){
        $('#edit').modal('show');
        ajax_ed_faq('edit',{{Session::get('faq_id')}});
    });
  @endif

  function ajax_ed_meta(id){
    $.ajax({
      type:"get",
      url:"/admin/ajax_ed_meta",
      data:{'id':id},
      success:function(data){
        p_edit(id,data);
      },
      error:function(data){
        alert('error '+data);
      }
    });
  }

  function p_edit(id,data){
    var meta=JSON.parse(data);
    var title=meta['title'];
    var keyword=meta['keyword'];
    var description=meta['description'];

    $('#edit [name="page_id"]').val(id);
    $('#edit [name="title"]').val(title);
    $('#edit [name="keyword"]').val(keyword);
    $('#edit [name="description"]').val(description);
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
