<x-app-layout>
    <x-authentication-card>
           <x-slot name="logo">
{{--            <x-authentication-card-logo />--}}
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center flex-col justify-end mt-4">
                    <button class="ms-4 inline-flex items-center px-4 py-2  justify-center w-full bg-primary border border-transparent rounded-md font-semibold text-xs text-white  uppercase tracking-widest hover:bg-primary-100 focus:bg-white active:bg-primary-100 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150">
                    {{ __('Log in') }}
                    </button>
                @if (Route::has('password.request'))
                    <a class="underline mt-6 text-sm text-gray-400 hover:text-gray-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

            </div>
        </form>
    </x-authentication-card>
</x-app-layout>
