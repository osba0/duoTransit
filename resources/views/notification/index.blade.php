@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
            @php $totalUnread = auth()->user()->unreadNotifications->count(); @endphp
          	<notification-list :total-unread= "{{ $totalUnread }}" :client-current= "{{ json_encode($client) }}"></notification-list>
        </div>
    </div>
</div>
@endsection