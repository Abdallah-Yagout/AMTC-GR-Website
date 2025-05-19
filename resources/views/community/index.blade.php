<x-app-layout>


    <div class="bg-black text-white flex items-center justify-center h-screen">
    <h1 class="text-4xl sm:text-5xl md:text-6xl text-primary lg:text-7xl font-bold text-center">
        {{__('Coming Soon...')}}
    </h1>
    </div>
{{--    <!-- Hero Section -->--}}
{{--    <section class="bg-primary-200 px-6 md:px-40 py-8">--}}
{{--        <h2 class="text-3xl font-bold text-white mb-2">{{__('Welcome to Our Forums')}}</h2>--}}
{{--        <p class="text-white">{{__('Ask your questions, get expert support, and collaborate on meaningful topics.')}}</p>--}}
{{--    </section>--}}
{{--    {{dd(app()->getLocale())}}--}}
{{--    <div class="bg-black text-white py-10 px-4 md:px-16 min-h-screen">--}}
{{--        <div class="max-w-7xl mx-auto">--}}

{{--            <!-- Tabs -->--}}
{{--            <div class="flex items-center gap-4 border-b border-gray-700 mb-6">--}}
{{--                <button class="pb-2 border-b-2 border-red-600 text-white font-medium">Popular</button>--}}
{{--                <button class="pb-2 text-gray-400 hover:text-white">Newest</button>--}}
{{--                <button class="pb-2 text-gray-400 hover:text-white">Following</button>--}}
{{--                <button class="ml-auto bg-red-600 text-white px-4 py-1 rounded-full hover:bg-red-700">Post</button>--}}
{{--            </div>--}}

{{--            <!-- Main Content and Sidebar -->--}}
{{--            <div class="lg:flex lg:gap-10">--}}

{{--                <!-- Forum Posts -->--}}
{{--                <div class="flex-1 space-y-4">--}}
{{--                    @foreach ($forums as $forum)--}}
{{--                        <x-forum-card :forum="$forum" />--}}
{{--                    @endforeach--}}
{{--                </div>--}}


{{--                <!-- Sidebar -->--}}
{{--                <div class="w-full lg:w-1/3 mt-10 lg:mt-0 space-y-10">--}}
{{--                    <!-- New Discussions -->--}}
{{--                    <div>--}}
{{--                        <h3 class="text-lg font-semibold mb-4">New Discussion</h3>--}}
{{--                        @for($i = 0; $i < 3; $i++)--}}
{{--                            <div class="flex items-start gap-2 text-sm text-gray-400 mb-4">--}}
{{--                                <img src="{{ asset('img/image 11.png') }}" alt="User Avatar" class="w-6 h-6 rounded-full object-cover">--}}
{{--                                <div>--}}
{{--                                    <p class="text-white font-medium">Mohammed</p>--}}
{{--                                    <p class="text-xs text-white">Toyota Gazoo Racing and the Rise of eSports – Future of Motorsports?</p>--}}
{{--                                    <p class="text-sm mt-2 text-gray-400">31 Jan</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endfor--}}
{{--                    </div>--}}


{{--                    <!-- Latest Startups -->--}}
{{--                    <div>--}}
{{--                        <h3 class="text-lg font-semibold mb-2">Latest Startups</h3>--}}
{{--                        <div class="flex flex-col gap-2">--}}
{{--                            <div class="flex items-center justify-between text-sm text-white">--}}
{{--                                <div class="flex items-center gap-2">--}}
{{--                                    <img src="{{ asset('img/image 11.png') }}" alt="Startup" class="w-8 h-8 rounded-full object-cover">--}}
{{--                                    GR Garage Gamers--}}
{{--                                </div>--}}
{{--                                <button class="text-xs bg-red-600 rounded px-2 py-0.5 hover:bg-red-700">Follow</button>--}}
{{--                            </div>--}}
{{--                            <div class="flex items-center justify-between text-sm text-white">--}}
{{--                                <div class="flex items-center gap-2">--}}
{{--                                    <img src="{{ asset('img/image 11.png') }}" alt="Startup" class="w-8 h-8 rounded-full object-cover">--}}
{{--                                    GR Shift Lab--}}
{{--                                </div>--}}
{{--                                <button class="text-xs bg-red-800 rounded px-2 py-0.5 hover:bg-red-900">Following</button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}


{{--                    <!-- Popular Post -->--}}
{{--                    <div>--}}
{{--                        <h3 class="text-lg font-semibold mb-2">Popular Post</h3>--}}
{{--                        <div class="text-sm text-gray-400">--}}
{{--                            <p class="text-white font-medium">Mohammed</p>--}}
{{--                            <p class="text-xs">Toyota Gazoo Racing and the Rise of eSports – Future of Motorsports?</p>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <!-- Bottom Sections -->--}}
{{--            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">--}}
{{--                <div class="bg-zinc-800 p-6 rounded">--}}
{{--                    <h4 class="text-lg font-semibold mb-2">News & Updates</h4>--}}
{{--                    <p class="text-sm text-gray-400">Check out the latest news and updates from the community.</p>--}}
{{--                </div>--}}
{{--                <div class="bg-zinc-800 p-6 rounded">--}}
{{--                    <h4 class="text-lg font-semibold mb-2">Support</h4>--}}
{{--                    <p class="text-sm text-gray-400">Feel free to contact us, we’re here to help you.</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</x-app-layout>
