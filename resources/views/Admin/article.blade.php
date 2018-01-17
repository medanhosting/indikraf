@extends('layouts.layout_admin_lte')

@section('content_header','Artikel')

@section('css')
  <link rel="stylesheet" href="{{asset('trumbo/ui/trumbowyg.min.css')}}">
@endsection
@section('content')
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border"><h4 class="box-title">Artikel</h4></div>
                <div class="box-body">
                    @if (Session::has('status_article'))
                      <div class="alert alert-info alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('status_article')}}
                      </div>
                    @endif
                    <form action="{{url('/admin/posting')}}" enctype="multipart/form-data" method="post">
                      {{csrf_field()}}
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                          <label class="control-default">Judul</label>
                          <input type="text" name="title" class="form-control" placeholder="Judul Artikel" value="{{old('title')}}" required autofocus>
                          @if ($errors->has('title'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('title') }}</strong>
                              </span>
                          @endif
                        </div>
                        <div class="form-group{{ $errors->has('post') ? ' has-error' : '' }}">
                          <label class="control-default">Artikel</label>
                          <textarea name="post" rows="8" class="form-control" id="editor" placeholder="Tulis sesuatu untuk membuat web ini menarik">{{old('post')}}</textarea>
                          @if ($errors->has('post'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('post') }}</strong>
                              </span>
                          @endif
                        </div>
                        <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                          <label class="control-default">Gambar Depan (max:500px)</label>
                          <input type="file" name="file" value="" required>
                          @if ($errors->has('file'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('file') }}</strong>
                              </span>
                          @endif
                        </div>
                        <div class="form-group{{ $errors->has('thumbnail') ? ' has-error' : '' }}">
                          <label class="control-default">Gambar Thumbnail (max:200px)</label>
                          <input type="file" name="thumbnail" value="" required>
                          @if ($errors->has('thumbnail'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('thumbnail') }}</strong>
                              </span>
                          @endif
                        </div>
                        <div class="form-group{{ $errors->has('meta_keyword') ? ' has-error' : '' }}">
                          <label class="control-default">Meta Keyword</label>
                          <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keyword" value="{{old('meta_keyword')}}">
                          @if ($errors->has('meta_keyword'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('meta_keyword') }}</strong>
                              </span>
                          @endif
                        </div>
                        <div class="form-group{{ $errors->has('meta_description') ? ' has-error' : '' }}">
                          <label class="control-default">Meta Description</label>
                          <textarea name="meta_description" class="form-control" rows="3" placeholder="Meta Description">{{old('meta_description')}}</textarea>
                          @if ($errors->has('meta_description'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('meta_description') }}</strong>
                              </span>
                          @endif
                        </div>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-bullhorn"></i> Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border"><h4 class="box-title">Artikel Sebelumnya</h4></div>
                <div class="box-body">
                  <table class="table table-striped" id="example2">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Gambar Depan</th>
                        <th>Judul</th>
                        <th>Isi</th>
                        <th>Komentar</th>
                        <th>Detail</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php($alim=1)
                      @foreach ($post as $p)
                        <tr>
                          <td>{{$alim++}}</td>
                          <td>
                            @php
                              $path="uploads/gambar_artikel/".$p->writer->user_id."_".$p->writer->profile->first_name."/artikel".$p->post_id."/";
                            @endphp
                            <img src="{{asset($path.$p->default_image)}}" width="200px">
                          </td>
                          <td>{{$p->title}}</td>
                          <td>{{str_limit(strip_tags($p->post), $limit = 50, $end = '...')}}</td>
                          <td>{{count($p->comments)>0?count($p->comments):'Belum ada komentar'}} Komentar</td>
                          <td><a href="{{url('/admin/post/'.$p->post_id)}}" class="btn btn-success">Detail</a></td>
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
  <script src="{{asset('trumbo/trumbowyg.min.js')}}"></script>
  <script src="{{asset('trumbo/plugins/upload/trumbowyg.upload.min.js')}}"></script>
  <script>
      /** Default editor configuration **/

      $.trumbowyg.svgPath = '{{asset('trumbo/ui/icons.svg')}}';
      $('#editor').trumbowyg({
          btnsDef: {
              // Customizables dropdowns
              image: {
                  dropdown: ['insertImage', 'upload', 'base64', 'noEmbed'],
                  ico: 'insertImage'
              }
          },
          btns: [
              ['viewHTML'],
              ['undo', 'redo'],
              ['formatting'],
              'btnGrp-design',
              ['link'],
              ['image'],
              'btnGrp-justify',
              'btnGrp-lists',
              ['foreColor', 'backColor'],
              ['preformatted'],
              ['horizontalRule'],
              ['fullscreen']
          ],
          plugins: {
              // Add imagur parameters to upload plugin
              upload: {
                  serverPath: 'https://api.imgur.com/3/image',
                  fileFieldName: 'image',
                  headers: {
                      'Authorization': 'Client-ID 05fb5485f52dfb8'
                  },
                  urlPropertyName: 'data.link'
              }
          }
      });

  </script>
  {{-- <script src="/vendor/ckeditor/ckeditor.js"></script>
  <script>
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      CKEDITOR.replace( 'editor' ,
      {
        // filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        // filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token='+csrf_token,
        // filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        // filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='+csrf_token
      });

      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
      });
  </script> --}}
@endsection
