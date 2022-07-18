@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    
    <div class="row">
        <div class="col-md-12">
        <div class="mb-3"></div>
            @if($id_client!='')
                <prechargement-entite id-client = {{$id_client}} 
                :list-fournisseurs = "{{ json_encode($fournisseurs) }}" 
                :type-cmd="{{ json_encode($typeCmd) }}"
                :default-contenaire="{{ $defaultContenaire }}"
                :list-contenaire="{{ json_encode($listContenaire) }}"
                :current-client="{{ json_encode($client) }}"
                :current-entite="{{ json_encode($entite) }}"
               >
                
                </prechargement-entite>
            @else
                <div class="badge badge-warning p-3">Client non autorisé ou inexistant!</div>
            @endif
           
        </div>
    </div>
</div>
@endsection