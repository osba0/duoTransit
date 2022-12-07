@extends('backend.master')

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Utilisateurs</h2>
                <a href="#" class="text-white h2 btn btn-primary" data-toggle="modal" data-target="#newUser">
                    <i class="fa fa-plus" aria-hidden="true"></i> Nouveau utilisateur
                </a>
            </div>
             @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT))
              <userlist list-roles='{{json_encode($roles)}}' list-clients='{{$clients}}' list-entites='{{$entites}}' is-admin='{{$isAdmin}}' slug-client='' ></userlist>
            @else
                 <userlist list-roles='{{json_encode($roles)}}' list-clients='{{$clients}}' list-entites='{{$entites}}' is-admin='{{$isAdmin}}'  slug-client="{{request()->route('currententite')}}" :id-entite="{{  auth()->user()->getIDEntite(request()->route('currententite')) }}"></userlist>
            @endif
           
           
        </div>
    </div>
</div>
@endsection