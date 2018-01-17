<template>
  <li class="dropdown messages-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <i class="fa fa-envelope-o"></i>
      @if (count(unread.length>0)
        <span class="label label-danger">{{count(unread.length)}}</span>
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
            <li><!-- start message -->
              <message-item v-for="unread in unread">
              </message>
              <a href="{{url('/admin/message')}}">
                <h4>
                  {{$n->data['name']}}
                </h4>
                <p>{{$n->data['message']}}</p>
              </a>
            </li>
        </ul>
      </li>
      <li class="footer"><a href="{{url('/admin/message')}}">See All Messages</a></li>
    </ul>
  </li>
</template>

<script>
    export default {
      props:['unread','userId'],
        mounted() {
          Echo.private('App.User.'+userId)
          .notification(notification)=>
              console.log(notification.type);
          });
            console.log('Component mounted.')
        }
    }
</script>
