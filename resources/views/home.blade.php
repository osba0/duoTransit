@extends('welcome')

@section('content')
<div id="app">
  
  @if(!auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT))
    <client-list client-sup="{{$clientSup}}" role-user="{{$roles}}"></client-list> 
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



