<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <div class="text-center">
            <a href="/" class="text-center">
                    <!--x-application-logo class="w-20 h-20 fill-current text-gray-500" /-->
                    <img src="{{ asset('images/itransit-logo.png') }}" class="text-center" style="display:inline-block;">
                </a>
                <h1 class="text-center" style="font-size: 40px; color: #3498db; font-weight:bold;">{{ env('APP_NAME') }}</h1>
            </div>
        </x-slot>
        <div class="pb-1 mt-2 mb-0 h3 font-weight-bold" style="color: #3e5267 !important;">Connexion</div>
            <hr class="mb-3">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
     
        <!-- Validation Errors -->
        <!--x-auth-validation-errors class="mb-4" :errors="$errors" /-->

        @if (session('error'))
             <div class="alert alert-danger">
                 {{ session('error') }}
             </div>
          @endif
        @if($errors->any())
         <div class="alert alert-danger">
            Login ou Mot de passe incorrect.
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="app_run">
            @csrf
            
            <!-- Email Address -->
            <div>
                <x-label for="username" :value="__('Login')" />

                <x-input id="username" class="block mt-1 w-full" type="text" name="username" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Mot de  passe')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <!--div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div-->

            <!-- Mot de passe oublié -->
            <div class="block mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié?') }}
                    </a>
                @endif
            </div>

            <div class="flex items-center justify-end mt-4">
               

                <x-button class="ml-3">
                    {{ __('Se connecter') }}
                </x-button>
            </div>
    </x-auth-card>
    </form>
</x-guest-layout>
<style type="text/css">
    body{
        background: url(../../images/bg-contenaire.jpg) !important;/*bgApp.png*/
        background-position-x: 0%;
        background-position-y: 0%;
        background-repeat: repeat;
        background-repeat: no-repeat !important;
        background-position: center 50% !important;
        background-size: cover !important;
    }
    .bg-gray-100{
        background: none !important;
    }
    .bg-login{
        position: absolute;
        top:0;
        left:0;
        width:100%;
        height:100%;
        opacity: 0.6;
        background: white;
        z-index:-1;
    }
</style>