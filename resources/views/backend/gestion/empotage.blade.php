@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    
    <div class="row">
        <div class="col-md-12">
            @if($id_client!='')
                <empotage-entite id-client = {{$id_client}} 
                :list-fournisseurs = "{{ json_encode($fournisseurs) }}" 
                :type-cmd="{{ json_encode($typeCmd) }}"
                :default-contenaire="{{ $defaultContenaire }}"
                :list-contenaire="{{ json_encode($listContenaire) }}"
                :client-current="{{ json_encode($client) }}"
                :current-entite="{{ json_encode($entite) }}"
                :list-entrepots="{{ json_encode($entrepots) }}"
                :liste-dossier="{{json_encode($listeDossier)}}"
                :id-entite="{{  auth()->user()->getIDEntite(request()->route('currententite')) }}"></empotage-entite>
            @else
                <div class="badge badge-warning p-3">Client non autorisé ou inexistant!</div>
            @endif
           
        </div>
    </div>
</div>
@endsection