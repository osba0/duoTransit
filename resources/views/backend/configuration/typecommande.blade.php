@extends('backend.master')

@section('content')

@php 
    $isRoot = 0;
    if( auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT) ){
        $isRoot = true;
    }
@endphp

<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Liste des Type de commande</h2>
                    @if($isRoot == true)
                        <a href="#" class="text-white h2 btn btn-primary" data-toggle="modal" data-target="#newTŷpeCommande">
                            <i class="fa fa-plus" aria-hidden="true"></i> Nouvel Type de Commande
                        </a>
                    @endif
              
            </div>
            
            @if(auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT))
                <typecommande :is-root={{ $isRoot }} :list-client="{{ json_encode($listClient) }}" :id-entite=-1 slug-client=''></typecommande>
            @else
                <typecommande :is-root={{ $isRoot }} :id-entite="{{  auth()->user()->getIDEntite(request()->route('currententite')) }}" :list-client="{{ json_encode($listClient) }}" slug-client="{{request()->route('currententite')}}"></typecommande>
            @endif
            
            
        </div>
    </div>
</div>
@endsection