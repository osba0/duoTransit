@extends('backend.master')

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Contenaire</h2>
               <a href="#" class="text-white h2 btn btn-success" data-toggle="modal" data-target="#newContenaire">
                    <i class="fa fa-plus" aria-hidden="true"></i> Ajouter contenaire
                </a>
            </div>
             <div>
                <contenaire></contenaire>
            </div>
        </div>
    </div>
</div>
@endsection