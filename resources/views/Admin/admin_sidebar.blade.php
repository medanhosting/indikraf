<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu">
  <li class="header">MENU</li>
  {{-- <li class="active treeview">
    <a href="#">
      <i class="fa fa-dashboard"></i> <span>Dashboard</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
  </li> --}}
  <li class="{{url()->current()==url('/admin')?'active':''}}">
    <a href="{{url('/')}}">
      <i class="fa fa-dashboard"></i> <span>Dashboard</span>
      <span class="pull-right-container">
        @if (count($user->unreadNotifications))
          <small class="label pull-right bg-green">new</small>
        @endif
      </span>
    </a>
  </li>

  <li class="{{url()->current()==url('/admin/analisys')?'active':''}}">
    <a href="{{url('/admin/analisys')}}">
      <i class="fa fa-bar-chart"></i> <span>Analisis</span>
    </a>
  </li>

  <li class="{{url()->current()==url('/admin/transaction')?'active':''}}">
    <a href="{{url('/admin/transaction')}}">
      <i class="fa fa-money"></i> <span>Transaction</span>
      <span class="pull-right-container">
        @if (count($notif_order))
          <small class="label pull-right bg-green">{{count($notif_order)}} new</small>
        @endif
      </span>
    </a>
  </li>

  <li class="{{url()->current()==url('/admin/users')?'active':''}}">
    <a href="{{url('/admin/users')}}">
      <i class="fa fa-users"></i> <span>User</span>
      <span class="pull-right-container">
        @if (count($notif_registered_user))
          <small class="label pull-right bg-green">{{count($notif_registered_user)}} new</small>
        @endif
      </span>
    </a>
  </li>

  <li class="treeview {{url()->current()==url('/admin/selling_product') || url()->current()==url('/admin/store') || url()->current()==url('/admin/submit_product') || url()->current()==url('/admin/email') || url()->current()==url('/admin/product_category')?'active':''}}">
    <a href="#">
      <i class="fa fa-shopping-bag"></i> <span>Market</span>
      @if (count($notif_registered_store))
        <small class="label bg-green">{{count($notif_registered_store)}} new</small>
      @endif
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li class="{{url()->current()==url('/admin/selling_product')?'active':''}}"><a href="{{url('/admin/selling_product')}}"><i class="fa fa-archive"></i> Jual Produk</a></li>
      <li class="{{url()->current()==url('/admin/product_category')?'active':''}}"><a href="{{url('/admin/product_category')}}"><i class="fa fa-tags"></i> Kategori Produk</a></li>
      <li class="{{url()->current()==url('/admin/store')?'active':''}}"><a href="{{url('/admin/store')}}"><i class="fa fa-shopping-bag"></i> Toko</a></li>
      <li class="{{url()->current()==url('/admin/submit_product')?'active':''}}">
        <a href="{{url('/admin/submit_product')}}">
          <i class="fa fa-balance-scale"></i> Pengajuan Produk
          @if (count($notif_registered_store))
            <small class="label bg-green">{{count($notif_registered_store)}} new</small>
          @endif
        </a>
      </li>
      <li class="{{url()->current()==url('/admin/email')?'active':''}}"><a href="{{url('/admin/email')}}"><i class="fa fa-envelope-o"></i> Email Blast</a></li>
    </ul>
  </li>

  <li class="treeview {{url()->current()==url('/admin/posting') || url()->current()==url('/admin/gallery') || url()->current()==url('/admin/video')?'active':''}}">
    <a href="#">
      <i class="fa fa-facebook-square"></i> <span>Social</span>
      @if (count($notif_comment))
        <small class="label bg-green">{{count($notif_comment)}} new</small>
      @endif
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li class="{{url()->current()==url('/admin/posting')?'active':''}}">
        <a href="{{url('/admin/posting')}}"><i class="fa fa-bullhorn"></i> Artikel
          @if (count($notif_comment))
            <small class="label bg-green">{{count($notif_comment)}} new</small>
          @endif
        </a>
      </li>
      <li class="{{url()->current()==url('/admin/gallery')?'active':''}}"><a href="{{url('/admin/gallery')}}"><i class="fa fa-image"></i> Galleri</a></li>
      <li class="{{url()->current()==url('/admin/video')?'active':''}}"><a href="{{url('/admin/video')}}"><i class="fa fa-youtube-square"></i> Video</a></li>
    </ul>
  </li>

  <li class="treeview {{url()->current()==url('/admin/information') || url()->current()==url('/admin/faq') || url()->current()==url('/admin/message')?'active':''}}">
    <a href="#">
      <i class="fa fa-gears"></i> <span>Service</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li class="{{url()->current()==url('/admin/information')?'active':''}}"><a href="{{url('/admin/information')}}"><i class="fa fa-info"></i> Informasi</a></li>
      <li class="{{url()->current()==url('/admin/faq')?'active':''}}"><a href="{{url('/admin/faq')}}"><i class="fa fa-question-circle"></i> Faq</a></li>
      <li class="{{url()->current()==url('/admin/meta')?'active':''}}"><a href="{{url('/admin/meta')}}"><i class="fa fa-cog"></i> Meta</a></li>
      <li class="{{url()->current()==url('/admin/message')?'active':''}}"><a href="{{url('/admin/message')}}"><i class="fa fa-envelope-o"></i> Pesan Masuk</a></li>
    </ul>
  </li>
</ul>
