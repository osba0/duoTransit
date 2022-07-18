@extends('layouts.app')

@section('css')
    <link href="{{ asset('open-iconic/font/css/open-iconic-bootstrap.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="h1">Edit the user: {{ $user->full_name }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <user-edit
                        :data='@json($user)'
                        :roles='@json($roles)'
                    >
                    </user-edit>
            </div>
        </div>
    </div>
@endsection
