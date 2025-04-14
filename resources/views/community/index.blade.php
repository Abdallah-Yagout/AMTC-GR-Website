<x-app-layout>
        <section class="bg-primary-200 px-40 py-6 w-fu">
            <h2 class="text-3xl font-bold text-white mb-2">Welcome to Our Forums</h2>
            <p class="text-white mb-6">Ask your questions, get expert support, and collaborate on meaningful topics.</p>
        </section>
    <div class="bg-black text-white py-10 px-4 md:px-16 min-h-screen">
        <div class="max-w-7xl mx-auto">


            <!-- Tabs -->
            <div class="flex items-center gap-4 border-b border-gray-700 mb-6">
                <button class="pb-2 border-b-2 border-red-600 text-white">Popular</button>
                <button class="pb-2 text-gray-400 hover:text-white">Newest</button>
                <button class="pb-2 text-gray-400 hover:text-white">Following</button>
                <button class="ml-auto bg-red-600 text-white px-4 py-1 rounded hover:bg-red-700">Post</button>
            </div>

            <!-- Forum Posts -->
            <div class="space-y-4">
                <div class="bg-zinc-900 rounded p-4 flex flex-col md:flex-row justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="border rounded border-gray-50 w-20 flex items-center  justify-between px-4 py-2">
                            <img src="{{asset('img/arrow.png')}}" alt="">
                            <span class="font-semibold text-red-500">200</span>
                        </div>

                        <div>
                            <p class="font-semibold mb-1">An incredible performance at Spa-Francorchamps secures Davidson’s second victory of the season.</p>
                            <div class="flex items-center space-x-2 text-sm text-gray-400">
                                <img src="{{asset('img/image 11.png')}}" alt="User Avatar" class="w-6 h-6 rounded-full object-cover" />
                                <p><span class="text-primary font-medium">Mohammed</span> • 2 hours ago</p>
                            </div>

                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-white self-start md:self-center">🔗</button>
                </div>

                <!-- Repeat forum card as needed -->
            </div>

            <!-- Sidebar (optional for larger screens) -->
            <div class="hidden lg:flex justify-between mt-10 gap-10">
                <!-- New Discussion -->
                <div class="flex-1">
                    <h3 class="text-lg font-semibold mb-4">New Discussion</h3>
                    <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
                        <div class="bg-gray-700 w-8 h-8 rounded-full"></div>
                        <div>
                            <p class="text-white font-medium">Mohammed</p>
                            <p class="text-gray-400 text-xs">Toyota Gazoo Racing and the Rise of eSports – Future of Motorsports?</p>
                        </div>
                    </div>
                    <!-- Repeat new discussions -->
                </div>

                <!-- Sidebar Right -->
                <div class="flex-1 space-y-6">
                    <!-- Latest Startups -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Latest Startups</h3>
                        <div class="flex flex-col gap-2">
                            <span class="flex items-center justify-between text-sm text-white">GR Garage Gamers <span class="text-xs bg-red-600 rounded px-2 py-0.5">Follow</span></span>
                            <span class="flex items-center justify-between text-sm text-white">GR Shift Lab <span class="text-xs bg-red-600 rounded px-2 py-0.5">Following</span></span>
                        </div>
                    </div>

                    <!-- Popular Post -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Popular Post</h3>
                        <div class="text-sm text-gray-400">
                            <p class="text-white font-medium">Mohammed</p>
                            <p class="text-xs">Toyota Gazoo Racing and the Rise of eSports – Future of Motorsports?</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Sections -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12">
                <div class="bg-zinc-800 p-6 rounded">
                    <h4 class="text-lg font-semibold mb-2">News & Updates</h4>
                    <p class="text-sm text-gray-400">Check out the latest news and updates from the community</p>
                </div>
                <div class="bg-zinc-800 p-6 rounded">
                    <h4 class="text-lg font-semibold mb-2">Support</h4>
                    <p class="text-sm text-gray-400">Feel free to contact us, we are here to help you</p>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
