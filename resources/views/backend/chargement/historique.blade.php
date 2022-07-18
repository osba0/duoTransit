@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
             <div class="d-flex justify-content-between mb-3">
                <h2>Historique Empotage</h2>
            </div>
            <historique id-client = {{$id_client}} id-user= {{$id_user}} type-cmd= {{json_encode($typeCmd)}}></historique>
            
        </div>
    </div>
</div>
@endsection