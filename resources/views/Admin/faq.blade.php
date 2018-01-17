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
          <form id="edit_form" action="{{url('/admin/edit_faq/')}}" method="post">
              {{csrf_field()}}
              <input type="hidden" name="faq_id" class="form-control">
              <div class="form-group">
                <label for="question" class="control-label">Pertanyaan</label>
                <textarea name="question" rows="2" class="form-control" placeholder="Pertanyaan"></textarea>
              </div>
              <div class="form-group">
                <label for="answer" class="control-label">Jawaban</label>
                <textarea name="answer" rows="2" class="form-control" placeholder="Jawaban"></textarea>
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

  <!--Modal Hapus faq-->
  <div id="delete" class="modal fade" role="dialog">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Hapus faq</h4>
  			</div>
  			<div class="modal-body">
  				Apakah Anda yakin ingin menghapus faq:
  			</div>
  			<div class="modal-footer">
  				<a class="btn btn-danger btn-hapus" href="{{url('/admin/delete_faq/')}}">Hapus</a>
  				<button class="btn btn-default" data-dismiss="modal">Batal</button>
  			</div>
  		</div>
  	</div>
  </div>
<!--End Modal Hapus faq-->
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border"><h4 class="box-title">Faq</h4></div>
                <div class="box-body">
                    @if (Session::has('status_faq'))
                      <div class="alert alert-info alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_faq')}}
                      </div>
                    @endif
                    <form action="{{url('/admin/add_faq')}}" method="post">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label for="question" class="control-label">Pertanyaan</label>
                        <textarea name="question" class="form-control" rows="4" placeholder="Masukkan Pertanyaan"></textarea>
                      </div>
                      <div class="form-group">
                        <label for="answer" class="control-label">Jawaban</label>
                        <textarea name="answer" class="form-control" rows="4" placeholder="Masukkan Jawaban"></textarea>
                      </div>
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
              <div class="box-header with-border"><h4 class="box-title">Faq sebelumnya</h4></div>
              <div class="box-body">
                <table class="table table-striped" id="example2">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Pertanyaan</th>
                      <th>Jawaban</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  @php($arizi=1)
                  @foreach ($faqs as $f)
                    <tr>
                      <td>{{$arizi++}}</td>
                      <td>{{$f->question}}</td>
                      <td>{{str_limit(strip_tags($f->answer), $limit = 50, $end = '...')}}</td>
                      <td>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#edit" onclick="ajax_ed_faq('edit',{{$f->faq_id}})"><i class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete" onclick="ajax_ed_faq('delete',{{$f->faq_id}})"><i class="fa fa-close"></i></button>
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

  function ajax_ed_faq(jenis,id){
    $.ajax({
      type:"get",
      url:"/admin/ajax_ed_faq",
      data:{'id':id},
      success:function(data){
        if(jenis=="edit"){
          p_edit(id,data);
        }else{
          p_delete(id,data);
        }
      },
      error:function(data){
        alert('error '+data);
      }
    });
  }

  function p_edit(id,data){
    var faq=JSON.parse(data);
    var question=faq['question'];
    var answer=faq['answer'];

    $('#edit [name="faq_id"]').val(id);
    $('#edit [name="question"]').val(question);
    $('#edit [name="answer"]').val(answer);
  }

  function p_delete(id,data){
    var faq=JSON.parse(data);
    var question=faq['question'];

    $('#delete .modal-body').html("Apakah Anda yakin ingin menghapus pertanyaan: <br>"+question);
    $('#delete .modal-footer .btn-hapus').attr('href','/admin/delete_faq/'+id);
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
