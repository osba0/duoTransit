@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    
    <div class="row pt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Rapport d'incident</h2>
                <a href="#" class="text-white h2 btn btn-success" data-toggle="modal" data-target="#newIncident">
                    <i class="fa fa-plus" aria-hidden="true"></i> Nouvel incident
                </a>
            </div>
            <incidents id-client = {{$id_client}} id-user= {{$id_user}}></incidents>
        </div>
    </div>
</div>
@endsection