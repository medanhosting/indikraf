@extends('layouts.layout_admin_lte')

@section('content_header','Edit Artikel')

@section('content')
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border"><h4 class="box-title">Edit Artikel {{$post->title}}</h4></div>
                <div class="box-body">
                    <form action="{{url('/admin/update_article')}}" enctype="multipart/form-data" method="post">
                      {{csrf_field()}}
                      <input type="hidden" name="post_id" value="{{$post->post_id}}">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                          <label class="control-default">Judul</label>
                          <input type="text" name="title" class="form-control" placeholder="Judul Artikel" value="{{old('title')==null?$post->title:old('title')}}" required autofocus>
                          @if ($errors->has('title'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('title') }}</strong>
                              </span>
                          @endif
                        </div>
                        <div class="form-group{{ $errors->has('post') ? ' has-error' : '' }}">
                          <label class="control-default">Artikel</label>
                          <textarea name="post" rows="8" class="form-control" id="bodyField" placeholder="Tulis sesuatu untuk membuat web ini menarik">
                            @if (old('post')==null)
                              {!!$post->post!!}
                            @else
                              {{old('post')}}
                            @endif
                          </textarea>
                          @if ($errors->has('post'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('post') }}</strong>
                              </span>
                          @endif
                        </div>
                        <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                          <label class="control-default">Gambar Depan (max:500px)</label>
                          <input type="file" name="file" value="">
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
                          <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keyword" value="{{old('meta_keyword')==null?$post->meta_keyword:old('meta_keyword')}}">
                          @if ($errors->has('meta_keyword'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('meta_keyword') }}</strong>
                              </span>
                          @endif
                        </div>
                        <div class="form-group{{ $errors->has('meta_description') ? ' has-error' : '' }}">
                          <label class="control-default">Meta Description</label>
                          <textarea name="meta_description" class="form-control" rows="3" placeholder="Meta Description">
                            @if (old('post')==null)
                              {{($post->meta_description)}}
                            @else
                              {{old('meta_description')}}
                            @endif
                          </textarea>
                          @if ($errors->has('meta_description'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('meta_description') }}</strong>
                              </span>
                          @endif
                        </div>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-bullhorn"></i> Update</button>
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
  </script>
@endsection
