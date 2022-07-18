<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <div class="text-center">
            <a href="/" class="text-center">
                    <!--x-application-logo class="w-20 h-20 fill-current text-gray-500" /-->
                    <img src="{{ asset('images/itransit-logo.png') }}" class="text-center" style="display:inline-block;">
                </a>
                <h1 class="text-center" style="font-size: 40px">{{ env('APP_NAME') }}</h1>
            </div>
        </x-slot>

          

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

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
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié?') }}
                    </a>
                @endif

                <x-button class="ml-3">
                    {{ __('Se connecter') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
