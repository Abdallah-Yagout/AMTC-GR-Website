<x-app-layout>
    <div class="flex flex-col items-center pt-6 sm:pt-0">
        <section class="w-full bg-primary-200 px-6 sm:px-20 py-4 dark:text-white">
            <h1 class="text-3xl font-bold">{{ __('Join the Competition') }}</h1>
            <p class="mb-8">{{ __('Submit your entry and monitor your progress — full speed ahead!') }}</p>
        </section>
    </div>

    <div class=" text-white py-12 px-6 md:px-20">
        <div class="bg-secondary rounded-lg p-8 w-full max-w-5xl border-1 border-[#999999] mx-auto">
            <h2 class="text-xl text-center font-bold mb-6">{{ __('Participant Application') }}</h2>
            <form action="{{route('tournament.submit')}}" method="post">
                <input type="hidden" name="tournamentId" value="{{$tournamentId}}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-white">{{ __('Name') }}</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ auth()->check() ? auth()->user()->name : old('name') }}"
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
                        <select
                            name="gender"
                            class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 dark:bg-transparent bg-transparent focus:bg-transparent text-gray-400 appearance-none hover:bg-transparent transition-colors duration-200">
                            <option value="">{{ __('Select') }}</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <!-- City -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-white">{{ __('City') }}</label>
                        <select
                            name="city"
                            class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 dark:bg-transparent bg-transparent focus:bg-transparent text-gray-400 appearance-none hover:bg-transparent transition-colors duration-200">
                            <option value="">{{ __('Select') }}</option>
                            @foreach(\App\Helpers\Location::cities() as $city)
                                <option value="{{$city}}">{{$city}}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Address -->
{{--                    <div class="space-y-1 md:col-span-2">--}}
{{--                        <label class="block text-sm font-medium text-white">{{ __('Address') }}</label>--}}
{{--                        <input--}}
{{--                            type="text"--}}
{{--                            name="address"--}}
{{--                            placeholder="{{ __('Enter your address') }}"--}}
{{--                            class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent"--}}
{{--                        />--}}
{{--                    </div>--}}

                    <!-- Phone Number -->
                    <div class="space-y-1 md:col-span-2">
                        <label class="block text-sm font-medium text-white">{{ __('Phone') }}</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ auth()->check() ? auth()->user()->phone : old('phone') }}"
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
                            value="{{ auth()->check() ? auth()->user()->email : old('email') }}"
                            placeholder="{{ __('Enter your email address') }}"
                            class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 bg-transparent"
                        />
                    </div>

                    <!-- Racing Experience -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-white">{{ __('Racing Experience') }}</label>
                        <select
                            name="racing_experience"
                            class="w-full p-2 rounded border border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400 dark:bg-transparent bg-transparent focus:bg-transparent text-gray-400 appearance-none hover:bg-transparent transition-colors duration-200">
                            <option value="">{{ __('Select level') }}</option>
                            <option value="Beginner">{{ __('Beginner (No experience)') }}</option>
                            <option value="Amateur1">{{ __('Amateur (1 year)') }}</option>
                            <option value="Amateur2">{{ __('Amateur (2-3 years)') }}</option>
                            <option value="Intermediate">{{ __('Intermediate (4-5 years)') }}</option>
                            <option value="Advanced">{{ __('Advanced (5+ years)') }}</option>
                            <option value="Professional">{{ __('Professional') }}</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="mt-6 bg-red-600 hover:bg-red-700 px-6 py-2 rounded">{{ __('Submit') }}</button>
            </form>
        </div>
    </div>
    <section class="bg-secondary mt-10 w-full py-8 px-4 rounded-lg text-white mx-auto">
        <h1 class="text-center text-3xl font-bold mb-12">{{__('Application Analytics')}}</h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Applications -->
            <div class="bg-black p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-2xl font-medium  text-gray-300">{{ __('Total Application') }}</p>
                    <x-icon name="heroicon-o-document" class="w-7 h-7 text-primary" />
                </div>
                <p class="text-2xl font-bold mb-1">1,234</p>
            </div>

            <!-- Accepted Racers -->
            <div class="bg-black p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-2xl font-medium text-gray-300">{{ __('Accepted Racers') }}</p>
                    <x-icon name="heroicon-o-user" class="w-7 h-7 text-primary" />
                </div>
                <p class="text-2xl font-bold mb-1">856</p>
            </div>

            <!-- Active Events -->
            <div class="bg-black p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-2xl font-medium text-gray-300">{{ __('Active Events') }}</p>
                    <x-icon name="heroicon-o-calendar" class="w-7 h-7 text-primary" />
                </div>
                <p class="text-2xl font-bold mb-1">12</p>
                <span class="text-xs text-gray-400">{{ __('Yemen') }}</span>
            </div>

            <!-- Viewers -->
            <div class="bg-black p-6 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-2xl font-medium text-gray-300">{{ __('Viewers') }}</p>
                    <x-icon name="heroicon-o-eye" class="w-7 h-7 text-primary" />
                </div>
                <p class="text-2xl font-bold mb-1">20,656</p>
            </div>
        </div>
    </section>

</x-app-layout>
