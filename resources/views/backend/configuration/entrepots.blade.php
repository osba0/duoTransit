@extends('backend.master')

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Entrepôt</h2>
               <a href="#" class="text-white h2 btn btn-primary" data-toggle="modal" data-target="#newEntrepot">
                    <i class="fa fa-plus" aria-hidden="true"></i> Ajouter entrepôt
                </a>
            </div>
             <div>
                <entrepot></entrepot>
            </div>
        </div>
    </div>
</div>
@endsection