@extends('backend.master')

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Transitaires</h2>
                <a href="#" class="text-white h2 btn btn-primary font-weight-bold" data-toggle="modal" data-target="#newEntite">
                    <i class="fa fa-plus" aria-hidden="true"></i> Ajouter transitaire
                </a>
            </div>
            <div>
                <entite :list-entrepots="{{ json_encode($entrepots) }}" :list-contenaires="{{ json_encode($contenaires) }}" ></entite>
            </div>
        </div>
    </div>
</div>
@endsection