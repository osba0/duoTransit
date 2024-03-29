@php
$config = [
    'appName' => config('app.name')
];
$currentEntite = request()->route('currententite');
@endphp

@if(is_null($currentEntite))
    @php $entite = auth()->user()->getEntite(auth()->user()->entites_id)[0]['slug'] @endphp
@else 
    @php $entite = request()->route('currententite') @endphp
@endif

@php $count = 0; @endphp

@foreach(auth()->user()->unreadNotifications as $notification) 
   
    @if(isset($notification->data['entite']))
        @if($entite == $notification->data['entite'])
            @php $count++; @endphp
        @endif
    @endif

@endforeach


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="/images/logo-itransit-white.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/app/adminlte.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/app.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/app/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/custom.css') }}">

    <title> @if($count > 0) ● @endif {{ $config['appName'] }}</title>
    @yield('styles')
</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed">

    <div class="wrapper" id="app_run">
        
        <changepassword id-client = {{$id_client?? ''}}></changepassword>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <div class="d-flex justify-content-between w-100 align-items-center">
                <div class="d-flex align-items-center">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-widget="pushmenu"><i class="fa fa-bars"></i></a>
                        </li>
                    </ul>
                     @if (\Request::is('configuration/*'))
                        @if (auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT))
                            <div class="ml-3 d-flex align-items-center">
                                <img src="/images/root.png" height="40">
                                <strong class="text-primary color-white px-2 fontAnton">Super Admin</strong>
                            </div>
                        @else
                            <div class="ml-3 d-flex align-items-center">
                                <strong class="text-primary color-white px-2 fontAnton">{{ auth()->user()->getEntiteBySlug(request()->route('currententite'))['nom'] }}</strong>

                            </div>
                        @endif
                     @else
                       <div class="d-flex align-items-center">
                           <strong class="d-none text-primary color-white px-2 fontAnton"><u>{{ auth()->user()->getEntiteBySlug(request()->route('currententite'))['nom'] }}</u></strong>
                           @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) || auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION) || auth()->user()->hasRole(\App\Models\UserRole::ROLE_AUXILIAIRE))
                            <div class="dropdown ml-3">
                                  <button class="px-0 btn btn-default dropdown-toggle box-shadow-none" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @foreach(auth()->user()->entiteList() as $enti)
                                        @if($enti['slug'] == $currentEntite)
                                        <a><img src="/images/logo/{{ $enti['logo']}}" alt="{{$enti['nom']}}" width="100" class="bg-white inline-block"></a>
                                        @endif
                                    @endforeach
                                  </button>
                                  <div class="dropdown-menu px-2" aria-labelledby="dropdownMenu2">
                                    @foreach(auth()->user()->entiteList() as $enti)
                                        @if($enti['slug'] != $currentEntite)
                                        <a href="/transitaire/{{ $enti['slug'] }}">
                                            <img src="/images/logo/{{ $enti['logo']}}" alt="{{$enti['nom']}}" width="100" class="bg-white inline-block" 
                                                   >
                                        </a>
                                        @endif
                                    @endforeach
                                  </div>
                            </div>
                           @else
                           <img src="{{ url('/images/logo/'.auth()->user()->getEntiteBySlug(request()->route('currententite'))['logo']) }}" alt="{{ auth()->user()->getEntiteBySlug(request()->route('currententite'))['nom'] }}" class="mr-0 pr-0 pl-2" style="height: 25px;"> 
                           @endif
                             
                            <img src="{{ url('/images/logo/'.$logo) }}" alt="" class="ml-3 pl-3 border-left" style="height: 40px;">
                           
                        </div>
                     @endif

                </div>
                
                <div class="pr-3">@include('navUser')</div>
            </div>

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary">
             <a href="/" class="brand-link border-right">
                <img src="{{ asset('images/logo-itransit-white.png') }}" alt="Logo"
                    class="brand-image" style="opacity: .8">
                <span class="brand-text small font-weight-light">
                 {{ $config['appName'] }}
                </span>
            </a>          

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="">

                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT))  
                        <li class="nav-item">
                            <a href="{{route('home')}}" class="nav-link {{ (request()->is('/')) ? 'active' : '' }}">
                                <i class="fa fa-tachometer nav-icon"></i>
                                @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION) || auth()->user()->hasRole(\App\Models\UserRole::ROLE_AUXILIAIRE))  
                                  <p>Accueil</p>
                                @else
                                 <p>Tableau de bord</p>
                                @endif
                            </a>
                        </li>
                        @endif
                        @if (\Request::is($currentEntite.'/reception/*') or \Request::is('activity/*') or \Request::is($currentEntite.'/precharger/*') or (\Request::is('incidents/*')) or 
                        \Request::is($currentEntite.'/numdocim/*') or (\Request::is('incidents/*')) or \Request::is('chargement-list/*') or \Request::is('chargement/*') or \Request::is($currentEntite.'/prechargement/*') or \Request::is('empotage/*') or  \Request::is('historique/*') or \Request::is($currentEntite.'/gerer/*') or \Request::is($currentEntite.'/historique-empotage/*') or \Request::is($currentEntite.'/historique-docim/*') or 
                        \Request::is($currentEntite.'/importCommande/*') or 
                        \Request::is($currentEntite.'/consultation/*') or 
                        \Request::is($currentEntite.'/historique_docim/*') or \Request::is($currentEntite.'/historique-prechargement/*') or \Request::is($currentEntite.'/notifications/*'))  
                            @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_AUXILIAIRE))  
                            <li class="nav-item">
                                <a href="/{{$currentEntite}}/reception/{{$client['slug']?? ''}}" class="nav-link {{ request()->is($currentEntite.'/reception/*') ? 'active' : '' }}"><i class="nav-icon fa fa-sign-in"></i> <p>Réceptionner</p></a>
                            </li>
                            @endif

                            @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT))  
                             
                            <li class="nav-item">
                                <a href="/{{$currentEntite}}/precharger/{{$client['slug']?? ''}}" class="nav-link {{ request()->is($currentEntite.'/precharger/*') ? 'active' : '' }}"><i class="nav-icon fa fa-asterisk"></i> <p>Préchargement</p></a>
                            </li>
                           
                            <li class="nav-item">
                                <a href="{{ route('historique-prechargement', ['id' => $client['slug']?? '', $currentEntite]) }}" class="nav-link {{ (request()->is($currentEntite.'/historique-prechargement/*')) ? 'active' : '' }}">
                                  <i class="fa fa-clock-o nav-icon"></i>
                                  <p style="line-height: 18px; margin-top:3px">Historique <br> préchargement</p>
                                </a>
                            </li>
                             <li class="nav-item">
                                <a href="/{{$currentEntite}}/numdocim/{{$client['slug']?? ''}}" class="nav-link {{ request()->is($currentEntite.'/numdocim/*') ? 'active' : '' }}"><i class="nav-icon fa fa-tags"></i> <p>Gestion DOCIM</p></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('historique-docim', ['id' => $client['slug']?? '', $currentEntite]) }}" class="nav-link {{ (request()->is($currentEntite.'/historique-docim/*')) ? 'active' : '' }}">
                                  <i class="fa  fa-clock-o nav-icon"></i>
                                  <p>Histo DOCIM</p>
                                </a>
                            </li>
                            <li class="nav-item"> 
                                <a href="/{{$currentEntite}}/importCommande/{{$client['slug']?? ''}}" class="nav-link {{ request()->is($currentEntite.'/importCommande/*') ? 'active' : '' }}"><i class="nav-icon fa fa-cloud-download"></i> <p>Import</p></a> 
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('listNotif', ['client' => $client['slug']?? '', $currentEntite]) }}" class="nav-link {{ (request()->is($currentEntite.'/notifications/*')) ? 'active' : '' }}">
                                  <i class="fa fa-bell-o nav-icon"></i>
                                  <p>Notifications</p>
                                </a>
                            </li>
                            @endif
                            
                            @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_AUXILIAIRE))  

                            <li class="nav-item">
                                <a href="/{{$currentEntite}}/numdocim/{{$client['slug']?? ''}}" class="nav-link {{ request()->is($currentEntite.'/numdocim/*') ? 'active' : '' }}"><i class="nav-icon fa fa-tags"></i> <p>Gestion DOCIM</p></a>
                            </li>
                            @endif

                            @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION))  

                            <li class="nav-item">
                                <a href="{{ route('consultation', ['id' => $client['slug']?? '', $currentEntite]) }}" class="nav-link {{ (request()->is($currentEntite.'/consultation/*')) ? 'active' : '' }}">
                                  <i class="fa  fa-clock-o nav-icon"></i>
                                  <p>Histo DOCIM</p>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_AUXILIAIRE))  

                            <li class="nav-item">
                                <a href="{{ route('consultation', ['id' => $client['slug']?? '', $currentEntite]) }}" class="nav-link {{ (request()->is($currentEntite.'/historique_docim/*')) ? 'active' : '' }}">
                                  <i class="fa  fa-clock-o nav-icon"></i>
                                  <p>Histo DOCIM</p>
                                </a>
                            </li>
                            @endif

                            

                            @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_AUXILIAIRE)  && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_USER))  
                                @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_ADMIN || auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT)))  
                                <li class="nav-item {{ request()->is($currentEntite.'/gerer/prechargement/*') || request()->is($currentEntite.'/gerer/empotage/*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link">
                                      <i class="nav-icon fa fa-download"></i>
                                      <p>
                                        Etat Stock
                                        <i class="fa fa-angle-left right"></i>
                                      </p>
                                    </a>
                                    <ul class="nav nav-treeview {{ request()->is($currentEntite.'/gerer/prechargement/*') ||  request()->is($currentEntite.'/gerer/empotage/*')  ? 'd-block' : '' }}" style="display: none">
                                      <li class="nav-item">


                                        <a href="/{{ $currentEntite }}/gerer/prechargement/{{$client['slug']?? ''}}" class="nav-link {{ request()->is($currentEntite.'/gerer/prechargement/*')   ? 'active' : '' }}">
                                            <i class="fa fa-circle-o" aria-hidden="true"></i>
                                          <p>Prévision</p>
                                        </a>
                                      </li>
                                      <li class="nav-item">
                                        <a href="{{ route('gerer_empotage', ['id' => $client['slug']?? '', 'currententite' => $currentEntite]) }}" class="nav-link {{ request()->is($currentEntite.'/gerer/empotage/*')   ? 'active' : '' }}">
                                            <i class="fa fa-circle-o" aria-hidden="true"></i>
                                          <p>Empotage</p>
                                        </a>
                                      </li>
                                      
                                    </ul>
                                  </li>
                                  @endif
                              @endif
                              @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_AUXILIAIRE) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_USER)) 
                               <li class="nav-item">
                                <a href="{{ route('historique-empotage', ['id' => $client['slug']?? '', 'currententite' => $currentEntite]) }}" class="nav-link {{ (request()->is($currentEntite.'/historique-empotage/*')) ? 'active' : '' }}">
                                  <i class="fa  fa-clock-o nav-icon"></i>
                                  <p>Histo empotage</p>
                                </a>
                              </li>
                              @endif
                               @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_AUXILIAIRE) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) && false)  
                                <li class="dropdown-submenu  nav-item {{ (request()->is('chargement-list/*') or \Request::is('chargement/*') or \Request::is('prechargement/*') or Request::is('empotage/*') or  \Request::is('historique/*')) ? 'menu-open' : '' }}">
                                <a href="/chargement-list/{{$client['slug']?? ''}}" class="nav-link {{ request()->is('chargement-list/*')? 'active' : '' }}">
                                  <i class="nav-icon fa fa-download"></i>
                                  <p>Chargement <i class="right fa fa-angle-left"></i>
                                  </p>
                                </a>
                                <ul class="nav nav-treeview">
                                  <li class="nav-item">
                                    <a href="/prechargement/{{$client['slug']?? ''}}" class="nav-link {{ (request()->is('prechargement/*')) ? 'active' : '' }}">
                                      <i class="fa fa-angle-double-down nav-icon"></i>
                                      <p>Préchargement</p>
                                    </a>
                                  </li>
                                  @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_AUXILIAIRE)) 
                                   <li class="nav-item">
                                    <a href="/empotage/{{$client['slug']?? ''}}" class="nav-link {{ (request()->is('empotage/*')) ? 'active' : '' }}">
                                      <i class="fa fa-cube nav-icon"></i>
                                      <p>Empotage</p>
                                    </a>
                                  </li>
                                  @endif
                                  @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_AUXILIAIRE) ) 
                                   <li class="nav-item">
                                        <a href="/historique/{{$client['slug']?? ''}}" class="nav-link {{ (request()->is('historique/*')) ? 'active' : '' }}">
                                          <i class="fa  fa-clock-o nav-icon"></i>
                                          <p>Histo empotage</p>
                                        </a>
                                   </li>

                                  @endif
                                   
                                </ul>  
                            </li>
                            @endif
                        @endif

                        @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION)  && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_AUXILIAIRE) && !auth()->user()->hasRole(\App\Models\UserRole::ROLE_USER))  
                            @if (!\Request::is('configuration/*'))
                                 <li class="nav-item">
                                    <a href="{{ route('listNotif', ['client' => $client['slug']?? '', $currentEntite]) }}" class="nav-link {{ (request()->is($currentEntite.'/notifications/*')) ? 'active' : '' }}">
                                      <i class="fa fa-bell-o nav-icon"></i>
                                      <p>Notifications</p>
                                    </a>
                                </li>
                               
                             @endif  
                        
                        @endif


                       
                        @if (\Request::is('configuration/*'))
                            @userCan(\App\Models\UserRole::ROLE_ROOT)
                             <li class="nav-item">
                                <a href="{{route('entite')}}" class="nav-link {{ request()->is('configuration/entite') ? 'active' : '' }}">
                                    <i class="fa fa-rocket nav-icon"></i>
                                    <p>Transitaires</p>
                                    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('clients')}}" class="nav-link {{ request()->is('configuration/clients') ? 'active' : '' }}"><i class="fa fa-briefcase nav-icon"></i><p class="d-block">Sociétés</p>  
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('entrepots')}}" class="nav-link {{ request()->is('configuration/entrepots') ? 'active' : '' }}">
                                    <i class="fa fa-cube nav-icon"></i>
                                    <p>Entrepôts</p>
                                    
                                </a>
                            </li>
                             <li class="nav-item">
                                <a href="{{route('contenaires')}}" class="nav-link {{ request()->is('configuration/contenaires') ? 'active' : '' }}">
                                    <i class="fa fa-cubes nav-icon"></i>
                                    <p>Contenaires</p>
                                    
                                </a>
                            </li>
                           
                            @endUserCan
                               
                            @userCan(\App\Models\UserRole::ROLE_ADMIN)
                            @php if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT)) : @endphp

                            <li class="nav-item">
                                <a href="{{route('typecommande')}}" class="nav-link {{ request()->is('configuration/typecommande/*') || request()->is('configuration/typecommande') ? 'active' : '' }}">
                                    <i class="fa fa-arrows nav-icon"></i>
                                    <p>Type Commandes</p>
                                    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('utilisateurs')}}" class="nav-link {{ request()->is('configuration/utilisateurs/*') || request()->is('configuration/utilisateurs')? 'active' : '' }}">
                                    <i class="fa fa-users nav-icon"></i>
                                    <p>Utilisateurs</p>
                                    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('fournisseurs')}}" class="nav-link {{ request()->is('configuration/fournisseurs/*') || request()->is('configuration/fournisseurs') ? 'active' : '' }}">
                                    <i class="fa fa-handshake-o nav-icon"></i>
                                    <p>Fournisseurs</p>
                                    
                                </a>
                            </li>
                               
                            @php else:  @endphp

                            <li class="nav-item">
                                <a href="{{route('typecommandeAdmin', $currentEntite)}}" class="nav-link {{ request()->is('configuration/typecommande/*') ? 'active' : '' }}">
                                    <i class="fa fa-arrows nav-icon"></i>
                                    <p>Type Commandes</p>
                                    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('utilisateursAdmin', $currentEntite)}}" class="nav-link {{ request()->is('configuration/utilisateurs/*') || request()->is('configuration/utilisateurs')? 'active' : '' }}">
                                    <i class="fa fa-users nav-icon"></i>
                                    <p>Utilisateurs</p>
                                    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('fournisseursAdmin', $currentEntite)}}" class="nav-link {{ request()->is('configuration/fournisseurs/*') ? 'active' : '' }}">
                                    <i class="fa fa-handshake-o nav-icon"></i>
                                    <p>Fournisseurs</p>
                                    
                                </a>
                            </li>
                               
                            @php endif @endphp
                             
                           
                            @endUserCan
                            @userCan(\App\Models\UserRole::ROLE_ROOT)
                            <li class="nav-item">
                                <a href="{{route('mouchard')}}" class="nav-link {{ request()->is('configuration/journal') ? 'active' : '' }}">
                                    <i class="fa fa-history nav-icon"></i>
                                    <p>Journal</p>
                                    
                                </a>
                            </li>
                            @endUserCan
                        @endif

                       

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content" style="position: relative;">
                <pageloader :is-loading=false></pageloader> 
                <div class="container-fluid">
                    <div class="pt-3 pb-5">@yield('content')</div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->



        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-flex align-items-center">
                <img src="{{ asset('images/itransit-logo.png') }}" alt="Logo"
                    class="brand-image" height="20"> <span>{{ $config['appName'] }} v3.0</span>
            </div>
            <!-- Default to the left -->
            <strong>&copy; {{ now()->year }} <a href="#">{{ config('app.name', 'Laravel') }}</a>.</strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->
            

    <!-- REQUIRED SCRIPTS -->
    <script src="{{ asset('backend/js/app/jquery.min.js') }}"></script>
   

    <script>

        window.User = {
            id: {{ auth()->user()->id }} 
        }

        window.pagination = 6;
    </script>

    <script src="{{ mix('js/app.js') }}"></script>
    <script type="text/javascript">
        $('.dropdown-submenu > a').on('click', function(e) {
            e.preventDefault();
            window.location.href = $(this).attr('href');
        });
    </script>
     
    @yield('modal')
    @yield('scripts')

    <!--script src="{{ asset('backend/js/app/bootstrap.bundle.min.js') }}"></script-->
    <script src="{{ asset('backend/js/app/adminlte.min.js') }}"></script>

  
   
</html>
