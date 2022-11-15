@extends('welcome')

@section('content')
<div id="app">
  
  @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT))
    @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT) || auth()->user()->hasRole(\App\Models\UserRole::ROLE_CONSULTATION))
      
      @php $currentEntite = auth()->user()->getEntiteBySlug($slug)['slug']; @endphp

    @else

      @php $currentEntite = auth()->user()->getSlugEnite();  @endphp

    @endif

    <client-list client-sup="{{$clientSup}}" role-user="{{$roles}}" slug-entite="{{ $currentEntite }}"></client-list> 
  @endif

  @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT) ||
      auth()->user()->hasRole(\App\Models\UserRole::ROLE_ADMIN))
    
  @endif
  @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_USER))  

  @endif

  @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_CLIENT)) 

  @endif

 
</div>
@endsection



