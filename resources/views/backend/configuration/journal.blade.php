@extends('backend.master')

@section('content')

@php 
    $isRoot = 0;
    if( auth()->user()->hasRole(\App\Models\UserRole::ROLE_ROOT) ){
        $isRoot = true;
    }
@endphp

<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2>Journal</h2>
            </div>
            
            <activity-journal :client-current="{{ json_encode($clients) }}" :type-activity="{{  json_encode(\App\Models\TypActivity::getTypeActivity()) }}"></activity-journal>
            
           
        </div>
    </div>
</div>
@endsection