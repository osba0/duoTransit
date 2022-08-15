@extends('backend.master')

@section('client_logo')
<img src="{{ url('/images/logo/'.$logo) }}" alt="" style="height: 40px;">
@endsection

@section('content')
<div id="app">
    <div class="row mt-4">
        <div class="col-md-12">
          <div class="card p-3">
          		<h4>{{ $notification->data['title']}}</h4>
          		<div class="mb-2">{{ $notification->data['description']}}</div>
          	 	<span class="float-right text-muted text-sm">
          	  	    @php $diff = floor((strtotime(date('Y-m-d H:i:s')) - strtotime($notification->created_at))) @endphp
                    @if(($diff / 60) < 60)
                        <i class="fa fa-clock mr-1"></i>{{ floor($diff/ 60).'mn' }}
                    @elseif(($diff/ 3600) < 24)
                        <i class="fa fa-clock mr-1"></i>{{ floor($diff / 3600).'h' }}
                    @else
                        <i class="fa fa-clock mr-1"></i>{{ floor($diff / 86400).'j' }}
                    @endif  
             	</span>
             	<div class="mb-2"><em class="badge badge-info d-block float-left">by {{ $notification->data['user']['username'] }}</em></div>

             	<object data="{{ $notification->data['fichier']}}" type="application/pdf" height="600">
			        <div>No online PDF viewer installed</div>
			    </object>
          </div>
        </div>
    </div>
</div>
@endsection