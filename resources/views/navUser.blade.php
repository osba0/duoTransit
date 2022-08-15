<div class="image d-flex align-items-center">
    <ul class="notificationUL nav pull-right navTop d-none1">
        <li class="nav-item dropdown">
        <a class="notif p-2 d-flex align-items-center text-primary" data-toggle="dropdown" href="#" aria-expanded="false">
          <i class="fa fa-bell-o"></i>
          <span class="badge {{Auth::user()->roles[0]=='root'?'badge-danger ':''}} {{Auth::user()->roles[0]=='client'?'badge-warning ':''}} {{Auth::user()->roles[0]=='user'?'badge-secondary ':''}} {{Auth::user()->roles[0]=='admin'?'badge-info ':''}} navbar-badge p-0" id="js-count">{{ auth()->user()->unreadNotifications->count() }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">
                {{ auth()->user()->unreadNotifications->count()==0 ? 'Aucune': auth()->user()->unreadNotifications->count() }} Notification{{ auth()->user()->unreadNotifications->count()>1 ? 's': '' }}
            </span>
            <div class="contentNotif">
            @foreach(auth()->user()->unreadNotifications as $notification) 
                  @php $diff = floor((strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at))) @endphp
                   <div class="dropdown-divider"></div>
                    <a href="/notifications/mark-as-read/{{ $notification->data['slug'] }}/{{$notification->id}}" title="" class="dropdown-item d-flex justify-content-between align-items-top"> 
                        <!--i class="fa fa-info mr-2 pt-2"></i--> 
                        <label class="flex-1 m-0" style="white-space: break-spaces;">{{ $notification->data['title'] }}<br><em class="text-sm userNotif badge badge-info">by {{ $notification->data['user']['username'] }}</em>
                        </label>
                        <span class="float-right text-muted text-sm">
                            @if(($diff / 60) < 60)
                                <i class="fa fa-clock mr-1"></i>{{ floor($diff/ 60).'mn' }}
                            @elseif(($diff/ 3600) < 24)
                                <i class="fa fa-clock mr-1"></i>{{ floor($diff / 3600).'h' }}
                            @else
                                <i class="fa fa-clock mr-1"></i>{{ floor($diff / 86400).'j' }}
                            @endif  
                        </span>
                  </a>
            @endforeach
            </div>
            @if(auth()->user()->unreadNotifications->count()>0)
           <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer text-center">Afficher toutes les notifications</a>
           @endif  
        </div>
      </li>
    </ul>
    <div class="pull-right mr-3">
        <span class="badge badge-default text-uppercase">Profil:</span> 
        <span class="badge text-uppercase {{Auth::user()->roles[0]=='root'?'badge-danger ':''}} {{Auth::user()->roles[0]=='client'?'badge-warning ':''}} {{Auth::user()->roles[0]=='user'?'badge-secondary ':''}} {{Auth::user()->roles[0]=='admin'?'badge-info ':''}}">{{Auth::user()->roles[0]}}</span>
    </div>
    <ul class="nav pull-right navTop">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle text-uppercase" data-toggle="dropdown"><span class="initial elevation-1">{{ Str::upper(substr(Auth::user()->firstname,0,1)) }}{{ Str::upper(substr(Auth::user()->lastname,0,1)) }}</span> {{ Auth::user()->username }} <b class="caret"></b></a>
            <ul class="dropdown-menu p-0">
                <li class="text-center py-2 profilLi">
                    <span class="initial elevation-1 xls {{Auth::user()->roles[0]=='root'?'badge-danger ':''}} {{Auth::user()->roles[0]=='client'?'badge-warning ':''}} {{Auth::user()->roles[0]=='user'?'badge-secondary ':''}} {{Auth::user()->roles[0]=='admin'?'badge-info ':''}}">{{ Str::upper(substr(Auth::user()->lastname,0,1)) }}{{ Str::upper(substr(Auth::user()->firstname,0,1)) }}</span> </span>
                    <a><label>{{Auth::user()->firstname}} {{Auth::user()->lastname}}</label></a>
                     <span class="badge text-uppercase {{Auth::user()->roles[0]=='root'?'badge-danger ':''}} {{Auth::user()->roles[0]=='client'?'badge-warning ':''}} {{Auth::user()->roles[0]=='user'?'badge-secondary ':''}} {{Auth::user()->roles[0]=='admin'?'badge-info ':''}}">{{Auth::user()->roles[0]}}</span>
                </li>
                <li><a href="#" data-toggle="modal" data-target="#changePwd" class="px-3 py-2 border-bottom"><i class="fa fa-lock" aria-hidden="true"></i> Modifier mot de passe</a></li>
                <li class="divider"></li>
                <!--li><a href="/auth/logout" class="px-3 py-2"><i class="fa fa-sign-out" aria-hidden="true"></i> Déconnexion</a></li-->
            </ul>
        </li>
    </ul>
      <!-- Authentication -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
         <a onclick="event.preventDefault();
                            this.closest('form').submit();" class='block ml-1 px-3 py-2 text-lg mb-0 leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out h4'>
             <i class="fa fa-power-off" aria-hidden="true"></i>
        </a>
    </form>
   
</div>