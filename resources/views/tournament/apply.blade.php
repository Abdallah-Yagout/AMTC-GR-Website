<x-app-layout>
    <div class="flex flex-col items-center pt-6 sm:pt-0">
        <section class="w-full bg-primary-200 px-6 sm:px-20 py-4 text-white">
            <h1 class="text-3xl font-bold">{{ __('Join the Competition') }}</h1>
            <p class="mb-8">{{ __('Submit your entry and monitor your progress — full speed ahead!') }}</p>
        </section>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="color: red; border: 1px solid red; padding: 15px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="text-white py-12 px-6 md:px-20">
        <div class="bg-secondary rounded-lg p-8 w-full max-w-5xl border-1 border-[#999999] mx-auto">
            <h2 class="text-xl text-center font-bold mb-6">{{ __('Participant Application') }}</h2>

            @guest
                <div class="text-center p-8">
                    <p class="text-xl mb-4">{{ __('You need to be logged in to submit an application.') }}</p>
                    <a href="{{ route('login') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg inline-block">
                        {{ __('Login to Apply') }}
                    </a>
                    <p class="mt-4 text-gray-300">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('register') }}" class="text-red-400 hover:text-red-300">{{ __('Register here') }}</a>
                    </p>
                </div>
            @else
                @if($hasSubmitted)
                    <div class="text-green-600 p-4 rounded mb-6">
                        <p class="font-bold">{{ __('You have already submitted your application for this tournament!') }}</p>
                        <p>{{ __('We look forward to seeing you at the event.') }}</p>
                    </div>
                @else
                    <form action="{{route('tournament.submit')}}" method="post">
                        <input type="hidden" name="tournamentId" value="{{$tournament->id}}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- First Name -->
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-white">{{ __('Name') }}</label>
                                <input
                                    type="text"
                                    name="name"
                                    required
                                    readonly
                                    value="{{ auth()->user()->name }}"
                                    placeholder="{{ __('Enter your name') }}"
                                    class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent"
                                />
                            </div>
                        </div>

                        <!-- Combined row for Gender, City, Address, and Phone -->
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mt-4">
                            <!-- Gender -->
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-white">{{ __('Gender') }}</label>
                                <input
                                    type="text"
                                    readonly
                                    class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent"
                                    name="gender"
                                    value="{{ auth()->user()->profile->gender ?? '' }}"
                                >
                            </div>

                            <!-- City -->
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-white">{{ __('City') }}</label>
                                <input
                                    type="text"
                                    readonly
                                    class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent"
                                    name="city"
                                    value="{{ auth()->user()->profile->city ?? '' }}"
                                >
                            </div>

                            <!-- Phone Number -->
                            <div class="space-y-1 md:col-span-2">
                                <label class="block text-sm font-medium text-white">{{ __('Phone') }}</label>
                                <input
                                    type="text"
                                    name="phone"
                                    required
                                    readonly
                                    value="{{ auth()->user()->phone }}"
                                    placeholder="{{ __('Phone number') }}"
                                    class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent"
                                />
                            </div>
                        </div>

                        <!-- Email and Racing Experience -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Email Address -->
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-white">{{ __('Email Address') }}</label>
                                <input
                                    type="email"
                                    name="email"
                                    required
                                    readonly
                                    value="{{ auth()->user()->email }}"
                                    placeholder="{{ __('Enter your email address') }}"
                                    class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent"
                                />
                            </div>
                        </div>

                        <button type="submit" class="mt-6 bg-red-600 hover:bg-red-700 px-6 py-2 rounded">{{ __('Submit') }}</button>
                    </form>
                @endif
            @endguest
        </div>
    </div>
</x-app-layout>
