@extends('layouts.layout_admin_lte')

@section('content_header','Informasi')

@section('content')
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border"><h4 class="box-title">Information</h4></div>
                <div class="box-body">
                      @if (Session::has('status_information'))
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_information')}}
                        </div>
                      @endif
                      <form action="{{url('/admin/control_information')}}" enctype="multipart/form-data" method="post">
                        <div class="form-group">
                          <label class="control-default">Jenis Informasi</label>
                          <select class="form-control" name="kind" required>
                            <option disabled selected>Pilih Jenis Informasi</option>
                            @foreach ($informations as $i)
                              <option value="{{$i->information_id}}">{{$i->title}}</option>
                            @endforeach
                          </select><br>
                      </div>
                        {{csrf_field()}}
                          <div class="form-group">
                            <label class="control-default">Isi Informasi</label>
                            <textarea name="post" rows="8" class="form-control" id="bodyField" placeholder="Tulis sesuatu untuk membuat web ini menarik" required></textarea>
                          </div>
                          <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-bullhorn"></i> Post</button>
                      </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
  <script src="/vendor/ckeditor/ckeditor.js"></script>
  <script>
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      CKEDITOR.replace( 'bodyField' ,
      {
        filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token='+csrf_token,
        filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='+csrf_token
      });

      $('select[name=kind]').on('change',function(){
        $.ajax({
          type:"get",
          url:"/admin/get_information",
          data:{id:$(this).val()},
          success:function(salim){
            // data=salim.parse();
            // alert(data);
            var data=JSON.parse(salim);
            CKEDITOR.instances.bodyField.setData( data.post );
          },
          error:function(arizi){

          }
        });
      });
  </script>
@endsection
