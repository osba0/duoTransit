@extends('backend.master')

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Utilisateurs</h2>
                <a href="#" class="text-white h2 btn btn-success" data-toggle="modal" data-target="#newUser">
                    <i class="fa fa-plus" aria-hidden="true"></i> Nouveau user
                </a>
            </div>
            
            <userlist list-roles='{{json_encode($roles)}}' list-clients='{{$clients}}' is-admin='{{$isAdmin}}'></userlist>
           
        </div>
    </div>
</div>
@endsection