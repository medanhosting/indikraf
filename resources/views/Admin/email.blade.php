@extends('layouts.layout_admin_lte')

@section('content_header','Email')

@section('content')
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border"><h4 class="box-title">Email</h4></div>
                <div class="box-body">
                    {{-- <table class="table table-striped table-responsive" id="example2">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>Nama</th>
                          <th>Email</th>
                        </tr>
                      </thead>
                    <tbody>
                      @php($n=1)
                      @foreach ($email as $e)
                        <tr>
                          <td>{{$n++}}</td>
                          <td>{{$e->name}}</td>
                          <td>{{$e->email}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                    </table> --}}
                    @if (Session::has('status_email'))
          						<div class="alert alert-info alert-dismissible">
          								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          								<big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_email')}}
          						</div>
          					@endif
                    <form action="/admin/email/send_email" method="post">
                      <input type="text" name="subject" class="form-control" placeholder="Subjek" required><br>
                      <textarea name="text" class="form-control" id="bodyField" rows="8" cols="80" placeholder="Text" required></textarea>
                      {{csrf_field()}}<br>
                      <button type="submit" class="btn btn-primary pull-right">Kirim email untuk semua</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/vendor/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
  $('#example2').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false
  });

  var csrf_token = $('meta[name="csrf-token"]').attr('content');
  CKEDITOR.replace( 'bodyField' ,
  {
    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
    filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token='+csrf_token,
    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
    filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='+csrf_token
  });

  // $('#example2').DataTable({
  //   "paging": true,
  //   "lengthChange": false,
  //   "searching": true,
  //   "ordering": true,
  //   "info": true,
  //   "autoWidth": false
  // });
</script>
@endsection
