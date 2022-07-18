@extends('layouts.app')

@section('css')
    <link href="{{ asset('open-iconic/font/css/open-iconic-bootstrap.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="h1">Create a new user</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <user-create :roles='@json($roles)'>
                    </user-create>
            </div>
        </div>
    </div>
@endsection
