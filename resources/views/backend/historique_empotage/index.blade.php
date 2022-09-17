@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
             <div class="d-flex justify-content-between mb-3">
                <h2>Historique Empotage </h2>
            </div>
            <historique-empotage :list-fournisseurs = "{{ json_encode($fournisseurs) }}" 
                :type-cmd="{{ json_encode($typeCmd) }}"
                :client-current="{{ json_encode($client) }}"
                :list-entrepots="{{ json_encode($entrepots) }}"
                user-role="{{ auth()->user()->roles[0] }}"
                gestion-docim="0"
                ></historique-empotage>
            
        </div>
    </div>
</div>
@endsection