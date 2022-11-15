@extends('welcome')

@section('content')
<div id="app">
  <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card border-0">
                    <div class="card-header text-primary h2 font-weight-bold bg-white">Choisir un Transitaire</div>

                    <div class="card-body">
                        <choose-entite :list-entite="{{ json_encode(auth()->user()->entiteList()) }}" config-mode='normal' slug-entite=""></choose-entite>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
 
</div>
@endsection



