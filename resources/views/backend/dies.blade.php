@extends('backend.master')

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Dry</h2>
                <a href="#" class="text-white h2 btn btn-success" data-toggle="modal" data-target="#newDry">
                    <i class="fa fa-plus" aria-hidden="true"></i> Créer un nouveau dossier
                </a>
            </div>
            
            <dry-index></dry-index>
        </div>
    </div>
</div>
@endsection
