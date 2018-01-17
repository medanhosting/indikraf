@extends('layouts.layout_admin_lte')

@section('content_header','Detail Artikel')

@section('content')

<div id="delete_article" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Hapus Artikel {{$post->title}}</h4>
      </div>
      <div class="modal-body">
        <p>Anda yakin ingin menghapus artikel {{$post->title}}?</p>
      </div>
      <div class="modal-footer">
        <a href="{{url('/admin/delete_post/'.$post->post_id)}}" class="btn btn-danger">Hapus</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

<div id="delete_comment" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Hapus Komentar</h4>
      </div>
      <div class="modal-body">
        <p>Anda yakin ingin menghapus komentar ini?</p>
      </div>
      <div class="modal-footer">
        <a href="{{url('/admin/delete_comment/')}}" id="delete" class="btn btn-danger">Hapus</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

<div id="approve_comment" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Setujui Komentar</h4>
      </div>
      <div class="modal-body">
        <p>Anda yakin ingin menyetujui komentar ini?</p>
      </div>
      <div class="modal-footer">
        <a href="{{url('/admin/approve_comment/')}}" id="approve" class="btn btn-success">Setujui</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

<div class="col-md-12">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
          <div class="box-header with-border">
            <div class="col-md-8">
              <h4 class="box-title">Artikel {{$post->title}}</h4>
            </div>
            <div class="col-md-4" style="text-align:right">
              <a href="{{url('/admin/update_article/'.$post->post_id)}}" class="btn btn-success"><i class="fa fa-edit"></i> Edit Artikel</a>
              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_article"><i class="fa fa-close"></i> Hapus Artikel</button>
            </div>
          </div>
          <div class="box-body">
            @if (Session::has('comment'))
              <div class="alert alert-info alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <big><i class="fa fa-check-circle-o"></i></big> {{Session::get('comment')}}
              </div>
            @endif

            <center><h2>{{$post->title}}</h2></center>
            @php
              $path="uploads/gambar_artikel/".$post->writer->user_id."_".$post->writer->profile->first_name."/artikel".$post->post_id."/";
            @endphp
            <center><img src="{{asset($path.$post->default_image)}}" width="400px"></center><br>
            <p align="left">
              {!!$post->post!!}
            </p><br>
          </div>
          <div class="box-footer">
            {{$post->date_format()}} {{$post->time_format()}}
          </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <h4><i class="fa fa-comment"></i> Komentar</h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-8" style="padding-left:30px">
          @if (count($post->comments))
            @foreach ($post->comments as $c)
              <div class="box box-default">
                <div class="box-header with-border">
                  <h5 class="box-title">Komentar dari {{$c->name}}</h5>
                  @if ($c->status!=1)
                    <button type="button" class="btn btn-success pull-right" onclick="approve_comment({{$c->comment_id}})" data-toggle="modal" data-target="#approve_comment">Setujui</button>
                  @endif
                  <button type="button" class="btn btn-danger pull-right" onclick="delete_comment({{$c->comment_id}})" data-toggle="modal" data-target="#delete_comment"><i class="fa fa-close"></i></button>
                </div>
                <div class="box-body">
                  <p>
                    {{$c->comment}}
                  </p>
                </div>
                <div class="box-footer">
                  {{$c->date_format()}} {{$c->time_format()}}
                </div>
              </div>
            @endforeach
          @else
            Belum ada komentar untuk artikel ini
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <div class="box box-default">
            <div class="box-header with-border"><h5 class="box-title">Berikan Komentar</h5></div>
            <div class="box-body">
              <form action="{{url('/admin/comment')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="post_id" value="{{$post->post_id}}">
                <textarea name="comment" class="form-control" rows="8" placeholder="Komentar Anda disini" style="resize:none" required></textarea><br>
                <button type="submit" class="btn btn-primary">Kirim Komentar</button>
              </form>
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
  function delete_comment(id){
    $('#delete').attr('href','/admin/delete_comment/'+id);
  }
  function approve_comment(id){
    $('#approve').attr('href','/admin/approve_comment/'+id);
  }
</script>
@endsection
