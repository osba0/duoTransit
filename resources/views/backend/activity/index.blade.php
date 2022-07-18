@extends('backend.master')

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Journal</h2>
            </div>

            <activity-index :client-current="{{ json_encode($client) }}" :type-activity="{{  json_encode(\App\Models\TypActivity::getTypeActivity()) }}"></activity-index>
            
            <!--div>
                <span class="badge badge-warning p-2 ">En cours de conception</span>
                <p class="py-3">Cette page visualise toutes les actions effectuées sur la plateforme iTransit.</p>
            </div-->
        </div>
    </div>
</div>
@endsection