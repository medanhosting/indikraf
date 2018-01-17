@extends('layouts.layout_admin_lte')

@section('content_header','User')

@section('content')
<div class="col-md-12">
    <div class="box box-success">
        <div class="box-header with-border"><h4 class="box-title">User terdaftar</h4></div>
        <div class="box-body">
          <table class="table table-responsive table-striped table-hovered table-bordered" id="example2">
            <thead>
              <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php($n=1)
              @foreach ($users as $u)
                <tr>
                  <td>{{$n++}}</td>
                  <td>
                    {{$u->profile->first_name." ".$u->profile->last_name}}
                    @if (array_search($u->user_id,$new_users)!=false)
                      <small class="label pull-right bg-green">new</small>
                    @endif
                  </td>
                  <td>{{$u->email}}</td>
                  <td>
                    @if (count($u->address))
                      {{$u->address[0]->phone}}
                    @else
                      Belum mengisi nomor telepon
                    @endif
                  </td>
                  <td>
                    @if (count($u->address))
                      {{$u->address[0]->address}}
                    @else
                      Belum mengisi alamat
                    @endif
                  </td>
                  <td>
                    <a href="{{url('/admin/users/'.$u->user_id)}}" class="btn btn-primary">Detail</a>
                    {{-- @if ($u->role->role_name!="Admin")
                      <button type="button" class="btn btn-primary" onclick="event.preventDefault();
                               document.getElementById('make_admin_{{$u->user_id}}').submit();"><i class="fa fa-user"></i> Jadikan Admin</button>
                      <form id="make_admin_{{$u->user_id}}" action="{{url('admin/makeUserAdmin')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{$u->user_id}}">
                      </form>
                    @endif --}}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
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
