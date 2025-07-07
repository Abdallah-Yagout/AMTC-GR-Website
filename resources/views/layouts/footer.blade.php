<footer class="bg-black text-white py-10 border-t border-[#17171A]">
    <div class="container mx-auto px-4 text-center space-y-6">
        <!-- Top Links -->
        <div class="flex flex-wrap justify-center gap-6 text-sm text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-white">{{ __('Home') }}</a>
            <a href="{{ route('forum.index') }}" class="hover:text-white">{{ __('Community') }}</a>
            <a href="{{ route('tournament.index') }}" class="hover:text-white">{{ __('Tournament') }}</a>
            <a href="{{ route('leaderboard.index') }}" class="hover:text-white">{{ __('Leaderboard') }}</a>
            <a href="{{ route('news.index') }}" class="hover:text-white">{{ __('News') }}</a>
        </div>

        <!-- Logo -->
        <div class="text-red-600 font-semibold text-xl">TOYOTA AMTC</div>

        <!-- Bottom Links -->
        <div class="text-xs text-gray-400 underline">
            <a href="https://arkadia.dev/"> {{__('Powered by ')}}{{__(' Arkadia Studio')}}</a>
        </div>

        <!-- Copyright -->
        <div class="text-xs text-gray-600">
            {{__('© 2025 TOYOTA MOTOR CORPORATION. All Rights Reserved.')}}
        </div>
    </div>
</footer>
