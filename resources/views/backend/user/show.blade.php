@extends('layouts.app')

@section('css')
    <link href="{{ asset('open-iconic/font/css/open-iconic-bootstrap.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="h1">{{$data['full_name'] ?? ''}}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <user-show :user='@json($data)'>
                    </user-show>
            </div>
        </div>
    </div>
@endsection
