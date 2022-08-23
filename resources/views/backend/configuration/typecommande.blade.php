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
            
          
            
            <typecommande :is-root={{ $isRoot }}></typecommande>
        </div>
    </div>
</div>
@endsection