@extends('backend.master')

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Liste des fournisseurs</h2>
                <a href="#" class="text-white h2 btn btn-success" data-toggle="modal" data-target="#newFournisseur">
                    <i class="fa fa-plus" aria-hidden="true"></i> Nouveau fournisseur
                </a>
            </div>
            
            <fournisseur :list-client="{{ json_encode($listClient) }}"></fournisseur>
        </div>
    </div>
</div>
@endsection