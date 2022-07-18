@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">

            <precharger id-client = {{$id_client}} num-dossier = {{$numero}} date-debut={{$dateDebut}} date-cloture={{$dateCloture}} id-typcmd = {{$id_typeCmd}} volume-Pied40={{ Config::get('constants.volume_40_Pied') }}  volume-Pied20={{ Config::get('constants.volume_20_Pied') }}></precharger>
            
        </div>
    </div>
</div>
@endsection