<x-app-layout>
    <x-authentication-card>

        <x-slot name="logo">
{{--            <x-authentication-card-logo />--}}
        </x-slot>

        <x-validation-errors class="mb-4" />
        <h1 class="text-3xl py-4 text-white font-bold mb-10">{{__('Register')}}</h1>

        @php($googleReady = \App\Models\OAuthSetting::isGoogleLoginConfigured())
        <div class="mb-6">
            @if ($googleReady)
                <a href="{{ route('auth.google.redirect') }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-md border border-zinc-600 bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-zinc-950">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    {{ __('Continue with Google') }}
                </a>
            @else
                <div class="rounded-md border border-dashed border-zinc-600 bg-zinc-900/50 px-4 py-3 text-center">
                    <span class="inline-flex w-full items-center justify-center gap-2 text-sm font-semibold text-zinc-500">
                        <svg class="h-5 w-5 shrink-0 opacity-70" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        {{ __('Continue with Google') }}
                    </span>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-500">
                        {{ __('Add Client ID, secret, and redirect URL in Admin → Settings → Google login, or set GOOGLE_* in .env.') }}
                    </p>
                </div>
            @endif
        </div>
        <p class="mb-6 text-center text-xs text-zinc-500">{{ __('Or register with email') }}</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <x-label for="name"  value="{{ __('Name') }}" />
                <x-input placeholder="{{__('name')}}" id="name" class="block  mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-6">
                <x-label  for="email" value="{{ __('Email') }}" />
                <x-input placeholder="{{__('email')}}" id="email" class="block mt-1 w-full " type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-6">
                <x-label  for="password" value="{{ __('Password') }}" />
                <x-input placeholder="{{__('password')}}" id="password" class="block mt-1 w-full " type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-6">
                <x-label  for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input placeholder="{{__('confirm password')}}" id="password_confirmation" class="block mt-1 w-full " type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm  text-gray-400  hover:text-gray-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-400  hover:text-gray-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex flex-col items-center justify-end mt-4">

               <button type="submit" class="inline-flex items-center px-4 py-2  w-full bg-primary border border-transparent rounded-md font-semibold text-xs text-white  uppercase mt-6 tracking-widest hover:bg-primary-100 focus:bg-primary-100 active:bg-primary-100  focus:outline-hidden justify-center focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150">
                    {{ __('Register') }}
               </button>
                <a class="underline mt-6 text-sm text-white hover:text-gray-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

            </div>
        </form>
    </x-authentication-card>
</x-app-layout>
