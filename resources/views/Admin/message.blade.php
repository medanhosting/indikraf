@extends('layouts.layout_admin_lte')

@section('content_header','Pesan Masuk')

@section('content')
    {{-- <div class="col-md-3">
      <a href="compose.html" class="btn btn-primary btn-block margin-bottom">Compose</a>

      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Folders</h3>

          <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked">
            <li class="active">
              <a href="#"><i class="fa fa-inbox"></i> Inbox
                @if (count($user->unreadNotifications->where('type','App\Notifications\Message')))
                  <span class="label label-primary pull-right">{{count($user->unreadNotifications->where('type','App\Notifications\Message'))}}</span>
                @endif

              </a>
            </li>
            <li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
          </ul>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. box -->
    </div> --}}
    <!-- /.col -->
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Inbox</h3>

          <div class="box-tools pull-right">
            {{-- <div class="has-feedback">
              <input type="text" class="form-control input-sm" placeholder="Search Mail">
              <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div> --}}
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
          <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped">
              <tbody>
                @foreach ($messages as $m)
                  <tr>
                    <td class="mailbox-name">
                      <a href="{{url('/admin/read_message/'.$m->message_id)}}">{{$m->sender}}</a>
                      @if (array_search($m->body,$new_message)!=false)
                        <small class="label bg-green">new</small>
                      @endif
                    </td>
                    <td class="mailbox-subject">
                      <b>{{$m->subject}}</b> - {{str_limit($m->body, $limit = 20, $end = '...')}}
                    </td>
                    <td class="mailbox-date">{{$m->created_at->diffForHumans()}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <!-- /.table -->
          </div>
          <!-- /.mail-box-messages -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer no-padding">
          <div class="mailbox-controls">
            <div class="pull-right">
              1-{{$messages->total()>10?'10':$messages->total()}}/{{$messages->total()}}
              @if ($messages->hasMorePages())
                <div class="btn-group">
                  <a href="{{$messages->nextPageUrl()}}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                  <a href="{{$messages->previousPageUrl()}}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                </div>
              @endif
              <!-- /.btn-group -->
            </div>
            <!-- /.pull-right -->
          </div>
        </div>
      </div>
      <!-- /. box -->
    </div>
    <!-- /.col -->
@endsection
