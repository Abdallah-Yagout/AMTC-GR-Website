<x-app-layout>
    <x-authentication-card>

        <x-slot name="logo">
{{--            <x-authentication-card-logo />--}}
        </x-slot>

        <x-validation-errors class="mb-4" />
        <h1 class="text-3xl py-4 text-white font-bold mb-10">{{__('Register')}}</h1>
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
