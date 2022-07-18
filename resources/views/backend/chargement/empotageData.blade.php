@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">

            <empotageData id-client = {{$id_client}} details-empotage={{json_encode($detailsEmpoatge)}} volume-Pied40={{ Config::get('constants.volume_40_Pied') }}  volume-Pied20={{ Config::get('constants.volume_20_Pied') }} id-selected= {{$id_Selected}} name-client={{$name_client}} pays-client="{{$pays_client}}"></empotageData>
            
        </div>
    </div>
</div>
@endsection