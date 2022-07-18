@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Chargement</h2>
                <a href="#" class="text-white h2 btn btn-success" data-toggle="modal" data-target="#newChargement">
                    <i class="fa fa-plus" aria-hidden="true"></i> Nouveau dossier
                </a>
            </div>
            
            <chargement id-client = {{$id_client}} :list-fournisseurs = "{{ json_encode(request()->session()->get('fournis'))}}"></chargement>
        </div>
    </div>
</div>
@endsection