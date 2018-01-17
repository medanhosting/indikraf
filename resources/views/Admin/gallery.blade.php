@extends('layouts.layout_admin_lte')

@section('css')
  <link rel="stylesheet" href="{{asset('css/dropzone.css')}}">
@endsection

@section('content_header','Galeri')

@section('content')

  <div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Gambar</h4>
        </div>
        <div class="modal-body">
          @if (Session::has('edit_gallery'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('edit_gallery')}}
            </div>
          @endif
          <form action="{{('/admin/edit_gallery')}}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="image_id" value="">
            <div class="form-group{{ $errors->has('image_name') ? ' has-error' : '' }}">
              <input type="text" name="image_name" class="form-control" placeholder="Nama Gambar" required>
              @if ($errors->has('image_name'))
                  <span class="help-block">
                      <strong>{{ $errors->first('image_name') }}</strong>
                  </span>
              @endif
            </div>

            <div class="form-group{{ $errors->has('image_category') ? ' has-error' : '' }}">
              <select name="image_category" class="form-control" required>
                <option value="" disabled selected>Pilih Kategori</option>
                @foreach ($image_category as $ic)
                  <option value="{{$ic->image_category_id}}">{{$ic->image_category_name}}</option>
                @endforeach
              </select>
              @if ($errors->has('image_category'))
                  <span class="help-block">
                      <strong>{{ $errors->first('image_category') }}</strong>
                  </span>
              @endif
            </div>

            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
              <textarea name="description" class="form-control" placeholder="Caption untuk gambar ini" required></textarea><br>
              @if ($errors->has('description'))
                  <span class="help-block">
                      <strong>{{ $errors->first('description') }}</strong>
                  </span>
              @endif
            </div>

            <div class="form-group{{ $errors->has('tooltip') ? ' has-error' : '' }}">
              <textarea name="tooltip" class="form-control" placeholder="tooltip untuk gambar ini" required></textarea>
              @if ($errors->has('tooltip'))
                  <span class="help-block">
                      <strong>{{ $errors->first('tooltip') }}</strong>
                  </span>
              @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <button class="btn btn-default" data-dismiss="modal">Batal</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="delete" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Hapus Gambar</h4>
        </div>
        <div class="modal-body">
          Apakah anda yakin ingin menghapus gambar ini?
        </div>
        <div class="modal-footer">
            <a href="{{url('/admin/delete_gallery/')}}" class="btn btn-danger" id="btn-delete">Hapus Gambar</a>
            <button class="btn btn-default" data-dismiss="modal">Batal</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal Category --}}
  <div id="modal_category" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Kategori</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <input type="text" name="image_category_name" class="form-control" placeholder="Nama Kategori">
            </div>
            <div class="col-md-2">
              <button type="button" class="btn btn-primary" id="add_category" data-dismiss="modal">Tambah Kategori</button>
            </div>
          </div><br>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal">Batal</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  {{-- End Modal Category --}}
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Galleri</div>
                <div class="panel-body">
                  @if (Session::has('delete_gallery'))
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('delete_gallery')}}
                    </div>
                  @endif
                  <form class="" action="{{url('/admin/add_gallery')}}" method="post" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-4">
                      Kategori:<br>
                      <div class="form-group{{ $errors->has('image_category_id') ? ' has-error' : '' }}">
                        <select name="image_category_id" class="form-control">
                          <option value="" disabled selected>Pilih Kategori</option>
                          @foreach ($image_category as $ic)
                            <option value="{{$ic->image_category_id}}">{{$ic->image_category_name}}</option>
                          @endforeach
                        </select>
                        @if ($errors->has('image_category_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('image_category_id') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <div class="col-md-4"><br>
                      <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modal_category">Tambah Kategori</button>
                      <a href="{{url('/admin/image_category')}}" class="btn btn-success">Kategori</a>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      {{csrf_field()}}
                      <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                      <textarea name="description" class="form-control" rows="8" placeholder="Masukan Caption"></textarea>
                      @if ($errors->has('description'))
                          <span class="help-block">
                              <strong>{{ $errors->first('description') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group{{ $errors->has('tooltip') ? ' has-error' : '' }}">
                        <textarea name="tooltip" class="form-control" rows="8" placeholder="Masukan Tooltip"></textarea>
                        @if ($errors->has('tooltip'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tooltip') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                        <input type="file" name="image">
                        @if ($errors->has('image'))
                            <span class="help-block">
                                <strong>{{ $errors->first('image') }}</strong>
                            </span>
                        @endif
                      </div>
                      {{-- <form action="/admin/add_gallery" class="dropzone" id="my-dropzone">
                        <div class="dz-message"><h1><i class="fa fa-photo"></i></h1>Drag dan Drop atau klik disini untuk upload (max:10 gambar)</div>
                        {{csrf_field()}}
                      </form>
                      <button id="submit-all" style="display:none" class="btn btn-primary" style=""><i class="fa fa-upload"></i> Upload</button>
                      <button type="reset" style="display:none" class="btn btn-danger" id="reset"><i class="fa fa-close"></i> Batal</button> --}}
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <button type="submit" class="btn btn-primary">Upload Gambar</button>
                    </div>
                  </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-md-12">
          <div class="panel panel-default">
              <div class="panel-heading">Galleri</div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-12">
                  <table class="table table-striped" id="example2">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Gambar</th>
                        <th>Nama Gambar</th>
                        <th>Caption</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php($n=1)
                      @foreach ($images as $i)
                        <tr>
                          <td>{{$n++}}</td>
                          <td>
                            @php
                							$path="/uploads/gallery/".$i->category->image_category_id."_".$i->category->image_category_name;
                						@endphp
                            <img src="{{asset($path."/".$i->image_path)}}" width="200px">
                          </td>
                          <td>{{$i->image_name}}</td>
                          <td>{{$i->description}}</td>
                          <td>{{$i->category->image_category_name}}</td>
                          <th>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#edit" onclick="ajax_ed('edit',{{$i->image_id}})"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete" onclick="ajax_ed('delete',{{$i->image_id}})"><i class="fa fa-close"></i></button>
                          </th>
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
<script src="{{ asset('js/dropzone.js') }}"></script>
<script type="text/javascript">

@if (Session::has('edit_gallery'))
  $(window).on('load',function(){
      $('#edit').modal('show');
      ajax_ed('edit',{{Session::get('image_id')}});
  });
@endif

var category_name;

$('#add_category').on('click',function(e){
  category_name=$('input[name=image_category_name]').val();
  ajax_category('add','none',category_name);
});

function ajax_category(type,category_id,category_name){
  $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type:"get",
      url:"/admin/add_image_category",
      data:{
            'category_id':category_id,
            'category_name':category_name
           },
      success:function(s){
        $('input[name=image_category_name]').val('');
        $('select[name=image_category]').empty();
        var obj = JSON.parse(s);

        $('select[name=image_category]')
        .append($('<option>', { value : 0 })
        .text('Pilih Kategori'))
        .attr('disabled');

        $.each(obj, function(key, value) {
          $('select[name=image_category]')
          .append($('<option>', { value : value.image_category_id })
          .text(value.image_category_name));
        });
      },
      error:function(a){
      }
    });
  }

  // Ajax Edit Delete Image
  function ajax_ed(jenis,id){
    $.ajax({
      type:"get",
      url:"/admin/ajax_ed_image",
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

    function p_edit(id,data){
      var image=JSON.parse(data);
      var image_name=image['image_name'];
      var image_category_id=image['image_category_id'];
      var description=image['description'];
      var tooltip=image['tooltip'];

      $('#edit [name=image_id]').val(id);
      $('#edit [name=image_name]').val(image_name);
      $('#edit [name=image_category]').val(image_category_id);
      $('#edit [name=description]').val(description);
      $('#edit [name=tooltip]').val(tooltip);
    }

    function p_delete(id,data){
      var image=JSON.parse(data);
      var image_name=image['image_name'];

      $('#delete .modal-body').html("Apakah Anda yakin ingin menghapus gambar: <label class='label label-danger'>"+image_name+"</label>");
      $('#delete .modal-footer #btn-delete').attr('href','/admin/delete_gallery/'+id);
    }
  }
  // End Ajax Edit Delete Image

  //Dropzone
    Dropzone.options.myDropzone = {

      // Prevents Dropzone from uploading dropped files immediately
      autoProcessQueue: false,
      acceptedFiles: "image/*",
      parallelUploads:10,
      init: function() {
      var submitButton = document.querySelector("#submit-all")
        myDropzone = this; // closure

      submitButton.addEventListener("click", function() {
        myDropzone.processQueue(); // Tell Dropzone to process all queued files.
      });

      var cancelButton=document.querySelector("#reset")
        myDropzone=this;

      cancelButton.addEventListener("click",function(){
        myDropzone.removeAllFiles(true);
        $("#submit-all").hide();
        $("#reset").hide();
      });

      myDropzone.options.url="/admin/add_gallery";
      // You might want to show the submit button only when
      // files are dropped here:
      this.on("addedfile", function() {
        $("#submit-all").show();
        $("#reset").show();
      });

      this.on('sending',function(file,xhr,formData){
        formData.append('description',$('textarea[name=description_image]').val());
        formData.append('image_category',$('select[name=image_category_id]').val());
      });

      this.on("error", function(file, message, xhr) {
        var header = xhr.status+": "+xhr.statusText;
        $(file.previewElement).find('.dz-error-message').html(header+".<br> Mohon pastikan Anda telah mengisi kategori dan caption dengan benar");
      });

      }
    };
  //Dropzone

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
