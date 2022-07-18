@extends('backend.master')

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom mb-4">
                <h2>Dossier n° <b>{{ $numero }}</b></h2>
                <div class="d-flex mb-0 justify-content-end h4">
                     Prévision de chargement pour le <b class="px-2">{{ $dateDebut }}</b> ET <b class="px-2">{{ $dateCloture }}</b>
                </div>
            </div>
            
             <chargement-view id-client ={{$id_client}} num-dossier={{$numero}} date-debut={{$dateDebut}} date-cloture={{$dateCloture}} id-user="{{Auth::user()->id}}"></chargement-view>
           
        </div>
    </div>
</div>
@endsection