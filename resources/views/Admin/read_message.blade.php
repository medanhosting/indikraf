@extends('layouts.layout_admin_lte')

@section('content_header','Baca Pesan')

@section('content')
  <div id="delete" class="modal fade" role="dialog">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title">Hapus pesan</h4>
  			</div>
  			<div class="modal-body">
  				Apakah Anda yakin ingin menghapus pesan ini?
  			</div>
  			<div class="modal-footer">
  				<a class="btn btn-danger btn-hapus" href="">Hapus</a>
  				<button class="btn btn-default" data-dismiss="modal">Batal</button>
  			</div>
  		</div>
  	</div>
  </div>

  <!-- /.col -->
  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Baca Pesan</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body no-padding">
        <div class="mailbox-read-info">
          <h3><b>{{$message->subject}}</b></h3>
          <h5>From: {{$message->sender}}
            <span class="mailbox-read-time pull-right">{{$message->date_format()." ".date('h:i A', strtotime($message->created_at))}}</span>
          </h5>
        </div>
        <!-- /.mailbox-read-info -->
        <!-- /.mailbox-controls -->
        <div class="mailbox-read-message">
          {{$message->body}}
        </div>
        <!-- /.mailbox-read-message -->
      </div>
      <!-- /.box-body -->

      <!-- /.box-footer -->
      <div class="box-footer">
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#delete" onclick="delete_message({{$message->message_id}})"><i class="fa fa-trash-o"></i> Delete</button>
      </div>
      <!-- /.box-footer -->
    </div>
    <!-- /. box -->
  </div>
  <!-- /.col -->
@endsection

@section('js')
<script type="text/javascript">
function delete_message(id){
  $('#delete .modal-footer .btn-hapus').attr('href','/admin/delete_message/'+id);
}
</script>
@endsection
