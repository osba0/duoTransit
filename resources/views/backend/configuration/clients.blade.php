@extends('backend.master')

@section('content')

<div id="app" style="position: relative;">
    <div class="row">
        <div class="col-md-12">            
            <client :list-fournisseurs = "{{ json_encode(request()->session()->get('fournis'))}}"></client>
        </div>
    </div>
    
</div>
 
@endsection