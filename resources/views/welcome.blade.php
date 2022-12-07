@php
    $config = [
        'appName' => config('app.name')
    ];
@endphp

@if(is_null(request()->route('currententite')))
    @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) || auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION))
        @php $entite = $slug  ; @endphp
    @else
        @php $entite = auth()->user()->getEntite(auth()->user()->entites_id)[0]['slug'] @endphp
    @endif
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
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="/images/logo-itransit-white.png">
  <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/app/font-awesome.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/custom.css') }}">
  <title>@if($count > 0) ● @endif {{ config('app.name') }}</title>
  <style>
    .img-circle {
        border-radius: 50%;
    }
    .box{
        height: 170px;
    }
    .box div{
        background: rgba(52,73,94,0.94);
        color: #fff;
    }
    .logoCl{
        top: -40px;
        height: 85px;
        min-width: 150px;
    }
    .bloc {
        margin-bottom: 1.875rem;
        background-color: #fff;
        box-shadow: 0 1px 2px rgb(214 219 226 / 50%), 0 1px 2px rgb(214 219 226 / 50%);
    }
    .bloc.block-rounded>.block-content:first-child {
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
    }

    .block-content {
        transition: opacity .25s ease-out;
        width: 100%;
        margin: 0 auto;
        padding: 1.25rem 1.25rem 1px;
        overflow-x: visible;
    }
    .item.item-rounded-lg {
        border-radius: 1.5rem;
    }
    .bg-body-light {
        background-color: #f6f7f9!important;
    }
    .item {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 4rem;
        height: 4rem;
        transition: opacity .25s ease-out,transform .25s ease-out;
        border-radius: 100% !important;
    }
    .fs-3 {
        font-size: 1.5rem!important;
    }
    .nowrap{
        white-space: nowrap; 
    }
    html{
         height: 100%;
    }
    body{
        background-image: url("{{ asset('images/bgApp.png') }}") !important;
        background-repeat: no-repeat !important;
        background-position: center 100% !important;
        height: 100%;
    }

  </style>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>

</head>
<body>
   
    <header class="border-bottom mb-3 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between">
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="{{ asset('images/itransit-logo.png') }}" style="height: 40px">
                        <h1 class="text-center h4 py-2 px-2 m-0" style="color: #0692cc;"><b>{{ config('app.name') }}</b></h1>
                         @if((auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) || auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION)) && $slug !='')
                            <div class="dropdown ml-3">
                                  <button class="btn btn-default dropdown-toggle box-shadow-none" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @foreach(auth()->user()->entiteList() as $enti)
                                        @if($enti['slug'] == $slug)
                                        <a><img src="/images/logo/{{ $enti['logo']}}" alt="{{$enti['nom']}}" width="100" class="bg-white inline-block"></a>
                                        @endif
                                    @endforeach
                                  </button>
                                  <div class="dropdown-menu px-2" aria-labelledby="dropdownMenu2">
                                    @foreach(auth()->user()->entiteList() as $enti)
                                        @if($enti['slug'] != $slug)
                                        <a href="/transitaire/{{ $enti['slug'] }}">
                                            <img src="/images/logo/{{ $enti['logo']}}" alt="{{$enti['nom']}}" width="100" class="bg-white inline-block" 
                                                   >
                                        </a>
                                        @endif
                                    @endforeach
                                  </div>
                            </div>
                            
                        @endif
                    </div>
                    <div class="pl-3 py-2" style="right:0">
                        
                       @include('navUser')

                  </div><!-- 1 -->
                </div>
            </div>
        </div>
    </header>
    <div class="container" id="app_run">
    @php if (!isset($chooseEntite)) : @endphp
    
        <div class="content">
          <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-start py-2">
            <div class="mb-1 mb-md-0 pr-3">
              <h1 class="h3 fw-bold mb-2 white-space-nowrap ">
                @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION))
                    Tableau de bord
                @else
                    Accueil
                @endif
              </h1>
              <h2 class="h6 fw-medium fw-medium text-muted mb-0">
                Bienvenu <a class="text-primary">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</a>
              </h2>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT))
                @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) || auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION))
                    <div class="d-flex align-items-center">
                        <div class="">
                            <choose-entite :list-entite="{{ json_encode(auth()->user()->entiteList()) }}" config-mode='small' slug-entite="{{$slug}}"></choose-entite>
                        </div>
                        <!--img src="{{ asset('/images/logo/'.auth()->user()->getEntiteBySlug($slug)['logo']) }}" class="border rounded-lg ml-3" style="width: 200px"-->
                    </div>
                    
                @else
                <div class="row d-select">
                    <div class="col cursor-pointer">
                        <div class="mb-4 rounded-lg p-2 bg-light-blue">
                            <div role="button" class="rounded-lg bg-white text-center p-1">
                                <div class="h1 text-primary font-weight-bold text-center">
                                     <img src="{{ asset('/images/logo/'.auth()->user()->getEntite(auth()->user()->entites_id)[0]['logo']) }}" class="" style="height: 50px">
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
               
                @endif
             @endif 
            </div>
          </div>
        </div>

        @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT))
            <div class="row">
                <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 pb-4">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$totalCmdAttente}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Commande(s) en Attente</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-list-ul h2 m-0 text-white" aria-hidden="true"></i>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 pb-4">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$four}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Fournisseur(s)</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-handshake-o  text-white h2 m-0" aria-hidden="true"></i>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 pb-4">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$typeCmdTotal}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Type(s) Commande(s)</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-arrows text-white h2 m-0" aria-hidden="true"></i>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 pb-4 {{ $recap['nbrJourMoyen']<=8?'bg-success':'' }} {{ ($recap['nbrJourMoyen']>8 && $recap['nbrJourMoyen']<=15)?'bg-warning':'' }} {{ $recap['nbrJourMoyen']>15?'bg-danger':'' }}">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold {{ ($recap['nbrJourMoyen']>8 && $recap['nbrJourMoyen']<=15)?'text-dark':'text-white' }}" >{{$recap['nbrJourMoyen']}} Jour(s)</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium {{ ($recap['nbrJourMoyen']>8 && $recap['nbrJourMoyen']<=15)?'text-dark':'text-white' }}  mb-0">Délai Moyen des commandes</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-white">
                            <i class="fa fa-clock-o text-dark h2 m-0" aria-hidden="true"></i>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @userCan(\App\Models\UserRole::ROLE_ADMIN)
            <div class="row">
                 @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT))
                 <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$totalCommande}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Commandes</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-list-ul h2 m-0 text-white" aria-hidden="true"></i>
                          </div>
                        </div>
                        <div class="bg-white mt-3 rounded-bottom">
                          <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
                            <span></span>
                            <i class="fa fa-arrow-circle-right h3 m-0 ms-1 opacity-0 fs-base"></i>
                          </a>
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-md-3 mt-3">
                    
                    @php if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT)) : @endphp
                        @php 
                            $link = route("typecommande"); 
                            $link_four = route("fournisseurs"); 
                            $link_user = route("utilisateurs"); 
                        @endphp
                    @php else:  @endphp
                        @php 
                            $link = route("typecommandeAdmin", ["currententite" => $slug]);
                            $link_four = route("fournisseursAdmin", ["currententite" => $slug]);
                            $link_user = route("utilisateursAdmin", ["currententite" => $slug]);
                        @endphp
                   
                    @php endif  @endphp

                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 cursor-pointer" onclick="javascript:location.href='{{ $link }}'">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$typeCmdNbr}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Type de Commande</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-arrows text-white h2 m-0" aria-hidden="true"></i>
                          </div>
                        </div>
                        <div class="bg-body-light mt-3 rounded-bottom">
                          <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ $link }}">
                            <span>Afficher</span>
                            <i class="fa fa-arrow-circle-right h3 m-0 ms-1 opacity-25 fs-base"></i>
                          </a>
                        </div>
                    </div>
                </div>
                
                 <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 cursor-pointer" onclick="javascript:location.href='{{ $link_user }}'">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{ $totalUser}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Utilisateurs</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-users text-white h2 m-0" aria-hidden="true"></i>
                          </div>
                        </div>
                        <div class="bg-body-light mt-3 rounded-bottomz">
                           <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ $link_user }}">
                            <span>Afficher</span>
                            <i class="fa fa-arrow-circle-right h3 m-0 ms-1 opacity-25 fs-base"></i>
                          </a>
                        </div>
                    </div>
                </div> 
                <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 cursor-pointer" onclick="javascript:location.href='{{ $link_four }}'">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$fourNbr}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Fournisseurs</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-handshake-o  text-white h2 m-0" aria-hidden="true"></i>
                          </div>
                        </div>
                        <div class="bg-body-light mt-3 rounded-bottom">
                          <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ $link_four }}">
                            <span>Afficher</span>
                            <i class="fa fa-arrow-circle-right h3 m-0 ms-1 opacity-25 fs-base"></i>
                          </a>
                        </div>
                    </div>
                </div>
                
                @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT))
                <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 cursor-pointer" onclick="javascript:location.href='{{ route("clients") }}'"
>
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$totalClient}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Sociétés</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-briefcase text-white h2 m-0" aria-hidden="true"></i>
                          </div>
                        </div>
                        <div class="bg-body-light mt-3 rounded-bottom">
                          <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('clients') }}">
                            <span>Afficher</span>
                            <i class="fa fa-arrow-circle-right h3 m-0 ms-1 opacity-25 fs-base"></i>
                          </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 cursor-pointer" onclick="javascript:location.href='{{ route("entrepots") }}'">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$totaEntrepot}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Entrepôts</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-cube text-white h2 m-0" aria-hidden="true"></i>
                          </div>
                        </div>
                        <div class="bg-body-light mt-3 rounded-bottom">
                          <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('entrepots') }}">
                            <span>Afficher</span>
                            <i class="fa fa-arrow-circle-right h3 m-0 ms-1 opacity-25 fs-base"></i>
                          </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 cursor-pointer" onclick="javascript:location.href='{{ route("entite") }}'">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$totalEntite}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Transitaires</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-rocket h2 m-0 text-white" aria-hidden="true"></i>
                          </div>
                        </div>
                        <div class="bg-body-light mt-3 rounded-bottom">
                          <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"  href="{{ route('entite') }}">
                            <span>Afficher</span>
                            <i class="fa fa-arrow-circle-right h3 m-0 ms-1 opacity-25 fs-base"></i>
                          </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 cursor-pointer" onclick="javascript:location.href='{{ route("contenaires") }}'">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{ $totalContenaire}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Contenaires</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-cubes text-white h2 m-0" aria-hidden="true"></i>
                          </div>
                        </div>
                        <div class="bg-body-light mt-3 rounded-bottom">
                           <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('contenaires') }}">
                            <span>Afficher</span>
                            <i class="fa fa-arrow-circle-right h3 m-0 ms-1 opacity-25 fs-base"></i>
                          </a>
                        </div>
                    </div>
                </div> 
                <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 cursor-pointer" onclick="javascript:location.href='{{ route("contenaires") }}'">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{ $totalJournal}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Journal</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-history text-white h2 m-0" aria-hidden="true"></i>
                          </div>
                        </div>
                        <div class="bg-body-light mt-3 rounded-bottom">
                           <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('mouchard') }}">
                            <span>Afficher</span>
                            <i class="fa fa-arrow-circle-right h3 m-0 ms-1 opacity-25 fs-base"></i>
                          </a>
                        </div>
                    </div>
                </div> 
                @endif
                
                @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT))
                </div>
                <div class="row mt-3">
                @endif


                  
            @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_ADMIN))
            </div>
           
            @endif
            
    @endUserCan

     @userCan(\App\Models\UserRole::ROLE_ROOT)
            
                 
                
                
               
            </div>
    @endUserCan

   @php endif @endphp
  
    @yield('content')  
      <changepassword id-client = {{$id_client?? ''}}></changepassword>
      
   </div>
  {{-- Global configuration object --}}
  <script>
    window.config = @json($config);

    window.User = {
        id: {{ auth()->user()->id }} 
    }

    window.pagination = 6;
  </script>

  {{-- Load the application scripts --}}
  <script src="{{ mix('js/app.js') }}"></script>


</body>
</html>
