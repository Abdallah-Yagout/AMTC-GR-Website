<nav
    x-data="{ open: false, langOpen: false }"
    class="site-header-shell {{ request()->routeIs('home') ? 'site-header-home is-at-top' : '' }}"
>
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-visible">
        <div class="site-header-main flex justify-between h-16 overflow-visible">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a class="site-header-logo-lockup flex items-center justify-start" href="{{ route('home') }}">
                        <img class="site-header-logo-gtcup" src="{{ asset('img/gtcup-splash-logo.png') }}" alt="GT CUP Logo">
                        <img class="site-header-logo-main" src="{{ asset('img/logo.png') }}" alt="Toyota Logo">
                    </a>
                </div>

                <div class="hidden gap-8 sm:-my-px sm:ms-8 sm:flex site-header-links">
                    <x-nav-link class="site-header-link" href="{{ route('home') }}" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link class="site-header-link" href="{{ route('forum.index') }}" :active="request()->routeIs('forum.index')">
                        {{ __('Community') }}
                    </x-nav-link>
                    <x-nav-link class="site-header-link" href="{{ route('tournament.index') }}" :active="request()->routeIs('tournament.index')">
                        {{ __('Tournament') }}
                    </x-nav-link>
                    <x-nav-link class="site-header-link" href="{{ route('leaderboard.index') }}" :active="request()->routeIs('leaderboard.index')">
                        {{ __('Leaderboard') }}
                    </x-nav-link>
                    <x-nav-link class="site-header-link" href="{{ route('news.index') }}" :active="request()->routeIs('news')">
                        {{ __('News') }}
                    </x-nav-link>
                    <x-nav-link class="site-header-link" href="{{ route('games.index') }}" :active="request()->routeIs('games.index')">
                        {{ __('Games') }}
                    </x-nav-link>
                    <x-nav-link class="site-header-link site-header-gr-cars-nav" href="{{ route('gr-cars.index') }}" :active="request()->routeIs('gr-cars.index')">
                        {{ __('GR-CARS') }}
                    </x-nav-link>
                    <x-nav-link class="site-header-link" href="{{ route('contact.index') }}" :active="request()->routeIs('contact')">
                        {{ __('Contact') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Language Switcher -->

                <div class="site-header-lang relative ms-4 me-6 rounded-full" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-1 px-2.5 py-2 text-gray-300 hover:text-white focus:outline-none">
                        @if(app()->getLocale() == 'ar')
{{--                            <span class="fi fi-sa fis rounded"></span>--}}
                            <span class="text-sm">AR</span>
                        @else
{{--                            <span class="fi fi-gb fis rounded"></span>--}}
                            <span class="text-sm">EN</span>
                        @endif
{{--                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>--}}
{{--                        </svg>--}}
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="site-header-dropdown-panel absolute end-0 mt-2 w-40 bg-white rounded-md shadow-lg z-50">
                        <div class="py-1">
                            <a href="{{ route('language.switch', 'en') }}" class="flex items-center px-4 py-2 text-sm text-gray-500 hover:bg-gray-100">
                                <span class="fi fi-gb fis rounded me-2"></span>
                                English
                                @if(app()->getLocale() == 'en')
                                    <svg class="w-4 h-4 ms-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @endif
                            </a>
                            <a href="{{ route('language.switch', 'ar') }}" class="flex items-center px-4 py-2 text-sm text-gray-500 hover:bg-gray-100">
                                <span class="fi fi-sa fis rounded me-2"></span>
                                العربية
                                @if(app()->getLocale() == 'ar')
                                    <svg class="w-4 h-4 ms-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

                @auth
                    <!-- Teams Dropdown -->
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="ms-3 relative">
                            <x-dropdown align="right" width="60" dropdownClasses="site-header-dropdown-panel">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-blue-50 hover:text-gray-700 focus:outline-hidden focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            {{ Auth::user()->currentTeam->name }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="w-60">
                                        <!-- Team Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Manage Team') }}
                                        </div>

                                        <!-- Team Settings -->
                                        <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                            {{ __('Team Settings') }}
                                        </x-dropdown-link>

                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}">
                                                {{ __('Create New Team') }}
                                            </x-dropdown-link>
                                        @endcan

                                        <!-- Team Switcher -->
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t border-gray-200"></div>

                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('Switch Teams') }}
                                            </div>

                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" />
                                            @endforeach
                                        @endif
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- Settings Dropdown -->
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48" dropdownClasses="site-header-dropdown-panel">
                            <x-slot name="trigger">
                                <button
                                    type="button"
                                    class="site-header-user-trigger flex items-center gap-2.5 rounded-lg border text-start transition focus:outline-hidden focus-visible:ring-2 focus-visible:ring-red-500/70"
                                >
                                    <img
                                        class="site-header-user-avatar size-9 shrink-0 rounded-full object-cover ring-2 ring-red-600/55"
                                        src="{{ Auth::user()->profile_photo_url }}"
                                        alt="{{ Auth::user()->name }}"
                                        onerror="this.onerror=null;this.src={{ \Illuminate\Support\Js::from(Auth::user()->profilePhotoFallbackUrl()) }}"
                                    />
                                    <div class="site-header-user-meta min-w-0 max-w-[9.5rem] md:max-w-[13rem]">
                                        <span class="site-header-user-name block truncate">{{ Auth::user()->name }}</span>
                                        <span class="site-header-user-email block truncate">{{ Auth::user()->email }}</span>
                                    </div>
                                    <svg class="site-header-user-chevron ms-0.5 size-4 shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-gray-200"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('register') }}" class="site-header-join inline-block px-5 py-1.5 text-white rounded-3xl text-sm leading-normal">
                            {{ __('Join Race') }}
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="site-header-burger inline-flex items-center justify-center p-2 rounded-md text-gray-400 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden site-header-mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('tournament.index') }}" :active="request()->routeIs('tournament.index')">
                {{ __('Tournament') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('forum.index') }}" :active="request()->routeIs('forum.index')">
                {{ __('Community') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('leaderboard.index') }}" :active="request()->routeIs('leaderboard.index')">
                {{ __('Leaderboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('news.index') }}" :active="request()->routeIs('news.index')">
                {{ __('News') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('games.index') }}" :active="request()->routeIs('games.index')">
                {{ __('Games') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link class="site-header-gr-cars-nav" href="{{ route('gr-cars.index') }}" :active="request()->routeIs('gr-cars.index')">
                {{ __('GR-CARS') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('contact.index') }}" :active="request()->routeIs('contact.index')">
                {{ __('Contact') }}
            </x-responsive-nav-link>
        </div>

        <!-- Mobile Language Switcher -->
        <div class="pt-2 pb-3 border-t border-gray-200">
            <div class="px-4 py-2 text-sm font-medium text-gray-500">
                {{ __('Language') }}
            </div>
            <a href="{{ route('language.switch', 'en') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-100">
                <span class="fi fi-gb fis rounded me-2"></span>
                English
                @if(app()->getLocale() == 'en')
                    <svg class="w-4 h-4 ms-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                @endif
            </a>
            <a href="{{ route('language.switch', 'ar') }}" class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-100">
                <span class="fi fi-sa fis rounded me-2"></span>
                العربية
                @if(app()->getLocale() == 'ar')
                    <svg class="w-4 h-4 ms-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                @endif
            </a>
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-white/10">
                <div class="site-header-user-mobile mx-4 flex items-center gap-3 rounded-lg border px-3 py-2.5">
                    <img
                        class="site-header-user-avatar size-11 shrink-0 rounded-full object-cover ring-2 ring-red-600/55"
                        src="{{ Auth::user()->profile_photo_url }}"
                        alt="{{ Auth::user()->name }}"
                        onerror="this.onerror=null;this.src={{ \Illuminate\Support\Js::from(Auth::user()->profilePhotoFallbackUrl()) }}"
                    />
                    <div class="site-header-user-meta min-w-0 flex-1">
                        <span class="site-header-user-name site-header-user-name--mobile block truncate">{{ Auth::user()->name }}</span>
                        <span class="site-header-user-email block truncate">{{ Auth::user()->email }}</span>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                            {{ __('API Tokens') }}
                        </x-responsive-nav-link>
                    @endif

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>

                    <!-- Team Management -->
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Team') }}
                        </div>

                        <!-- Team Settings -->
                        <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                            {{ __('Team Settings') }}
                        </x-responsive-nav-link>

                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                            <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                                {{ __('Create New Team') }}
                            </x-responsive-nav-link>
                        @endcan

                        <!-- Team Switcher -->
                        @if (Auth::user()->allTeams()->count() > 1)
                            <div class="border-t border-gray-200"></div>

                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Switch Teams') }}
                            </div>

                            @foreach (Auth::user()->allTeams() as $team)
                                <x-switchable-team :team="$team" component="responsive-nav-link" />
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        @else
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                    {{ __('Log in') }}
                </x-responsive-nav-link>
                @if (Route::has('register'))
                    <x-responsive-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endif
            </div>
        @endauth
    </div>
</nav>

{{--<script>--}}
{{--    // You can add jQuery functionality here if needed--}}
{{--    $(document).ready(function() {--}}
{{--        // For RTL support when Arabic is selected--}}
{{--        if ($('html').attr('dir') === 'rtl') {--}}
{{--            $('body').addClass('rtl');--}}
{{--        } else {--}}
{{--            $('body').addClass('ltr');--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}

