@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            @php $totalUnread = auth()->user()->unreadNotifications->count(); @endphp
          	<notification-list :total-unread= "{{ $totalUnread }}" :client-current= "{{ json_encode($client) }}" :id-entite="{{  auth()->user()->getIDEntite(request()->route('currententite')) }}" slug-entite="{{ request()->route('currententite') }}"></notification-list>
        </div>
    </div>
</div>
@endsection