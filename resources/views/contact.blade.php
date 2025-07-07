<x-app-layout>
    <!-- Hero Section -->
    <section class="bg-primary-200 px-6 py-8 md:px-10 md:py-10 text-white">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ __('We\'d Love to Hear from You') }}</h1>
            <p class="text-base md:text-lg">{{ __('Reach out with your inquiries, feedback, or support requests — we\'ll get back to you as fast as we race.') }}</p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 p-6 md:p-10 gap-8 md:gap-12">
            <!-- Contact Information -->
            <div class="p-6 md:p-10 order-2 lg:order-1">
                <h2 class="text-xl md:text-2xl text-white font-bold mb-6">{{ __('Get In Touch') }}</h2>
                <div class="space-y-4 md:space-y-6">
                    <div>
                        <h3 class="font-semibold text-white text-base md:text-lg mb-1 md:mb-2">{{ __('Email') }}</h3>
                        <a href="mailto:contact@example.com" class="hover:underline text-gray-400">contact@gryemen.com</a>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white text-base md:text-lg mb-1 md:mb-2">{{ __('Phone') }}</h3>
                        <p class="text-gray-400">+42387234</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white text-base md:text-lg mb-1 md:mb-2">{{ __('Address') }}</h3>
                        <p class="text-gray-400">{{ __('Google Android Phone') }}</p>
                        <p class="text-gray-400">{{ __('Santa Yates, CA 93465') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-secondary w-full lg:w-3/4 p-6 md:p-10 rounded-lg order-1 lg:order-2">
                <h2 class="text-xl md:text-2xl text-white font-bold mb-6 md:mb-8">{{ __('Send Message') }}</h2>
                <form method="POST" action="{{ route('contact.store') }}" class="space-y-4 md:space-y-6">
                    @csrf

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div>
                        <label for="name" class="block mb-1 md:mb-2 text-white text-sm font-medium">{{ __('Name') }}</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ auth()->check() ? auth()->user()->name : old('name') }}"
                            placeholder="{{ __('Name') }}"
                            {{ auth()->check() ? 'readonly' : '' }}

                            class="w-full px-4 py-2 md:py-3 rounded-lg border border-gray-600 bg-transparent text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                        @error('name')
                        <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block mb-1 md:mb-2 text-white text-sm font-medium">{{ __('Email') }}</label>
                        <input
                            type="email"
                            id="email"
                            required
                            name="email"
                            value="{{ auth()->check() ? auth()->user()->email : old('email') }}"
                            placeholder="{{ __('you@company.com') }}"
                            {{ auth()->check() ? 'readonly' : '' }}

                            class="w-full px-4 py-2 md:py-3 rounded-lg border border-gray-600 bg-transparent text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                        @error('email')
                        <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block mb-1 md:mb-2 text-white text-sm font-medium">{{ __('Message') }}</label>
                        <textarea
                            id="message"
                            name="message"
                            required
                            rows="4"
                            placeholder="{{ __('Write a message here') }}"
                            class="w-full px-4 py-2 md:py-3 rounded-lg border border-gray-600 bg-transparent text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        >{{ old('message') }}</textarea>
                        @error('message')
                        <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full cursor-pointer bg-primary text-white font-medium py-2 md:py-3 px-6 rounded-lg hover:bg-primary-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50"
                    >
                        {{ __('Send Message') }}
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>
