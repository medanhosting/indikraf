<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Indikraf | @yield('content_header')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{asset('AdminLTE/bootstrap/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('AdminLTE/dist/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link rel="stylesheet" href="{{asset('AdminLTE/dist/css/skins/skin-green.min.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('AdminLTE/plugins/datatables/dataTables.bootstrap.css')}}">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="stylesheet" href="{{asset('AdminLTE/plugins/pace/pace.min.css')}}">
  @yield('css')
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-green sidebar-mini fixed">
<div class="loading"></div>
<div class="wrapper">
  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="{{url('/')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>I</b>ndi</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Indi</b>Kraf</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              @if (count($user->unreadNotifications->where('type','App\Notifications\Message')))
                <span class="label label-danger">{{count($user->unreadNotifications->where('type','App\Notifications\Message'))}}</span>
              @endif

            </a>
            <ul class="dropdown-menu">
              @if (count($user->unreadNotifications->where('type','App\Notifications\Message')))
                <li class="header">You have {{count($user->unreadNotifications->where('type','App\Notifications\Message'))}} messages</li>
              @else
                <li class="header">You don't have message</li>
              @endif
              <li>
                <ul class="menu">
                  @foreach ($user->unreadNotifications->where('type','App\Notifications\Message') as $n)
                    <li><!-- start message -->
                      <a href="{{url('/admin/message')}}">
                        <h4>
                          {{$n->data['name']}}
                        </h4>
                        <p>{{$n->data['message']}}</p>
                      </a>
                    </li>
                  @endforeach
                </ul>
              </li>
              <li class="footer"><a href="{{url('/admin/message')}}">See All Messages</a></li>
            </ul>
          </li>

          <!-- Notifications Menu -->
          <li class="dropdown notifications-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              @if (count($user->unreadNotifications->where('type','!=','App\Notifications\Message')))
                <span class="label label-warning">{{count($user->unreadNotifications->where('type','!=','App\Notifications\Message'))}}</span>
              @endif
            </a>
            <ul class="dropdown-menu">
              @if (count($user->unreadNotifications->where('type','!=','App\Notifications\Message')))
                <li class="header">You have {{count($user->unreadNotifications->where('type','!=','App\Notifications\Message'))}} notifications</li>
              @else
                <li class="header">You don't have notification</li>
              @endif
              <li>
                <!-- Inner Menu: contains the notifications -->
                <ul class="menu">
                  <li><!-- start notification -->
                    @php
                      $notif_order=array();
                      $notif_comment=array();
                      $notif_registered_user=array();
                      $notif_registered_store=array();
                    @endphp
                    @if (count($user->unreadNotifications->where('type','!=','App\Notifications\Message')))
                      @foreach ($user->unreadNotifications->where('type','!=','App\Notifications\Message') as $n)
                        @if ($n->type=="App\Notifications\NewOrder")
                          @php
                            $notif_order[$n->id]=$n->data;
                          @endphp
                        @elseif ($n->type=="App\Notifications\CommentArticle")
                          @php
                            $notif_comment[$n->id]=$n->data;
                          @endphp
                          <a href="{{url('/admin/post/'.$n->data["post_id"])}}">
                            <i class="fa fa-comment text-primary"></i> {{$n->data['name']}} mengomentari artikel Anda
                          </a>
                        @elseif ($n->type=="App\Notifications\NewUserRegistration")
                          @php
                            $notif_registered_user[$n->id]=$n->data;
                          @endphp
                        @elseif ($n->type=="App\Notifications\NewStoreRegister")
                          @php
                            $notif_registered_store[$n->id]=$n->data;
                          @endphp
                        @endif
                      @endforeach
                    @endif
                    @if (count($notif_order))
                      <a href="{{url('/admin/transaction/')}}">
                        <i class="fa fa-money text-green"></i> {{count($notif_order)}} Transaksi Baru
                      </a>
                    @endif
                    @if (count($notif_registered_store))
                      <a href="{{url('/admin/submit_product')}}">
                        <i class="fa fa-archive text-maroon"></i> {{count($notif_registered_store)}} toko baru terdaftar
                      </a>
                    @endif
                    @if (count($notif_registered_user))
                      <a href="{{url('/admin/users/')}}">
                        <i class="fa fa-users text-aqua"></i> {{count($notif_registered_user)}} user baru terdaftar
                      </a>
                    @endif
                  </li>
                  <!-- end notification -->
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>

          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="{{asset('uploads/foto_profil/'.$user->profile->profile_image)}}" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{$user->profile->first_name." ".$user->profile->last_name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="{{asset('uploads/foto_profil/'.$user->profile->profile_image)}}" class="img-circle" alt="User Image">

                <p>
                  {{$user->profile->first_name." ".$user->profile->last_name}}
                  <small>Admin</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{url('/admin/profile')}}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                      onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                    <i class="fa fa-power-off"></i>  Logout
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('uploads/foto_profil/'.$user->profile->profile_image)}}" style="height: 45px;" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{$user->profile->first_name." ".$user->profile->last_name}}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      @include('Admin.admin_sidebar',['notif_order'=>$notif_order,'notif_comment'=>$notif_comment,'notif_registered_user'=>$notif_registered_user,'notif_registered_store'=>$notif_registered_store])
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('content_header')
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">@yield('content_header')</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        @yield('content')
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2016 <a href="#">Company</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript::;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript::;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                  <span class="label label-danger pull-right">70%</span>
                </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="{{asset('AdminLTE/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<script src="{{asset('AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('AdminLTE/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('AdminLTE/dist/js/app.min.js')}}"></script>
<script src="{{asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('AdminLTE/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('AdminLTE/plugins/pace/pace.min.js')}}"></script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
@yield('js')
</body>
</html>
