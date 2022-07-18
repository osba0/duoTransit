@php
$config = [
    'appName' => config('app.name')
];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="/images/logo-itransit-white.png">
  <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/app/font-awesome.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/custom.css') }}">
  <title>{{ config('app.name') }}</title>
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
                    </div>
                    <div class="px-3 py-2" style="right:0">
                        
                       @include('navUser')

                  </div><!-- 1 -->
                </div>
            </div>
        </div>
    </header>
    <div class="container" id="app_run">
          <div class="content">
          <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2">
            <div class="flex-grow-1 mb-1 mb-md-0">
              <h1 class="h3 fw-bold mb-2">
                Tableau de bord
              </h1>
              <h2 class="h6 fw-medium fw-medium text-muted mb-0">
                Bienvenu <a class="text-primary">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</a>
              </h2>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
             
            </div>
          </div>
        </div>
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
                        <div class="bg-body-light mt-3 rounded-bottom">
                          <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
                            <span>Afficher</span>
                            <i class="fa fa-arrow-circle-right h3 m-0 ms-1 opacity-25 fs-base"></i>
                          </a>
                        </div>
                    </div>
                </div>
                @endif
                
                 <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 cursor-pointer" onclick="javascript:location.href='{{ route("utilisateurs") }}'">
                        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                          <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{ $totalUser}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Utilisateurs</dd>
                          </dl>
                          <div class="item item-rounded-lg bg-primary">
                            <i class="fa fa-users text-white h2 m-0" aria-hidden="true"></i>
                          </div>
                        </div>
                        <div class="bg-body-light mt-3 rounded-bottom">
                           <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('utilisateurs') }}">
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
                            <i class="fa fa-male text-white h2 m-0" aria-hidden="true"></i>
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
                @endif
                
                @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT))
                </div>
                <div class="row mt-3">
                @endif


                  <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 cursor-pointer" onclick="javascript:location.href='{{ route("typecommande") }}'">
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
                          <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('typecommande') }}">
                            <span>Afficher</span>
                            <i class="fa fa-arrow-circle-right h3 m-0 ms-1 opacity-25 fs-base"></i>
                          </a>
                        </div>
                    </div>
                </div>
            @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_ADMIN))
            </div>
           
            @endif
            
    @endUserCan

     @userCan(\App\Models\UserRole::ROLE_ROOT)
            
                 
               
                <div class="col-md-3 mt-3">
                    <div class="bloc block-rounded d-flex flex-column h-100 mb-0 cursor-pointer" onclick="javascript:location.href='{{ route("fournisseurs") }}'">
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
                          <a class="text-primary block-content block-content-full p-2 text-center block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('fournisseurs') }}">
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
                
                
               
            </div>
    @endUserCan

  
  
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
