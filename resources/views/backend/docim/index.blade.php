@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
             <div class="d-flex justify-content-between mb-3">
                <h2>Gestion des numéros DOCIM</h2>
            </div>
            <historique-empotage :list-fournisseurs = "{{ json_encode($fournisseurs) }}" 
                :type-cmd="{{ json_encode($typeCmd) }}"
                :client-current="{{ json_encode($client) }}"
                :list-entrepots="{{ json_encode($entrepots) }}"
                user-role="{{ auth()->user()->roles[0] }}"
                username="{{ auth()->user()->username }}"
                gestion-docim="1"
                :id-entite="{{  auth()->user()->getIDEntite(request()->route('currententite')) }}"
                ></historique-empotage>
            
        </div>
    </div>
</div>
@endsection
