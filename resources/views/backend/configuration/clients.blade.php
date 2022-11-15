@extends('backend.master')

@section('content')

<div id="app" style="position: relative;">
    <div class="row">
        <div class="col-md-12">            
            <client :list-type-cmds="{{ json_encode($listTypeCmds) }}" :list-fournisseurs="{{ json_encode($listFournisseurs) }}" :list-transitaire="{{ json_encode($listTransitaire) }}"></client>
        </div>
    </div>
    
</div>
 
@endsection