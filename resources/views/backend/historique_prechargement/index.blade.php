@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
             <div class="d-flex justify-content-between mb-3">
                <h2>Historique Préchargement</h2>
            </div>
            <historique-prechargement :list-fournisseurs = "{{ json_encode($fournisseurs) }}" 
                :type-cmd="{{ json_encode($typeCmd) }}"
                :client-current="{{ json_encode($client) }}"
                :list-entrepots="{{ json_encode($entrepots) }}"></historique-prechargement>
            
        </div>
    </div>
</div>
@endsection