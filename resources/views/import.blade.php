@extends('backend.master')

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Import commandes</h2>
            </div>
            <import-commande :type-cmd="{{ json_encode($typeCmd) }}" slug-client="{{request()->route('slug')}}" :fournisseur-list="{{ json_encode($fournisseurListe) }}"></import-commande> 
            
        </div>
    </div>
</div>
@endsection
