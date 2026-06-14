@push('body_start')
    @include('partials.gtcup-splash-early')
@endpush
<x-app-layout>
    <x-gtcup-splash />
    <!-- Hero Section -->
    @if(isset($heroSlides) && $heroSlides->isNotEmpty())
        <section class="home-hero-slider" data-home-hero>
            <div class="home-hero-slides" data-home-hero-track>
                @foreach($heroSlides as $index => $slide)
                    <article
                        class="home-hero-slide {{ $index === 0 ? 'is-active' : '' }}"
                        data-home-hero-slide
                        style="background-image: url('{{ $slide->image_url }}')"
                        aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                    >
                        <div class="home-hero-overlay"></div>
                        <div class="home-hero-content-wrap">
                            <div class="home-hero-content">
                                <h1 class="home-hero-title" data-home-hero-reveal>
                                    {{ $slide->title }}
                                </h1>

                                @if(filled($slide->subtitle))
                                    <p class="home-hero-subtitle" data-home-hero-reveal>
                                        {{ $slide->subtitle }}
                                    </p>
                                @endif

                                @if(filled($slide->cta_text))
                                    <a
                                        href="{{ filled($slide->cta_url) ? $slide->cta_url : '#' }}"
                                        class="home-hero-cta"
                                        data-home-hero-reveal
                                    >
                                        {{ $slide->cta_text }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <button type="button" class="home-hero-nav home-hero-nav-prev" data-home-hero-prev aria-label="{{ __('Previous slide') }}">
                &#10094;
            </button>
            <button type="button" class="home-hero-nav home-hero-nav-next" data-home-hero-next aria-label="{{ __('Next slide') }}">
                &#10095;
            </button>
            <div class="home-hero-dots" data-home-hero-dots aria-label="{{ __('Hero slide navigation') }}"></div>
        </section>
    @else
        <section class="relative flex min-h-screen items-center justify-center overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('img/gazo_car.png') }}"
                     alt="Toyota Gazoo Racing E-Sports Background"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/50"></div>
            </div>

            <div class="home-hero-fallback-content relative z-10 w-full max-w-4xl mx-auto text-center px-8 py-20 md:px-10 md:py-24">
                <h1 class="text-white font-bold text-4xl md:text-6xl leading-tight tracking-wide font-sans uppercase">
                    {{__('Welcome to')}} <br>
                    <span class="text-primary">{{__('Toyota Gazoo Racing')}}</span><br>
                    <span class="text-white">{{__('E-Sports Yemen')}}</span>
                </h1>
                <p class="text-white text-lg mt-6">
                    {{__("Experience the thrill of virtual racing with the world's most")}} <br class="hidden md:inline">
                    {{__('competitive e-motorsport platform')}}
                </p>
                <a href="#"
                   class="inline-block bg-primary hover:bg-primary-100 text-white font-bold mt-8 px-6 py-3 rounded-full transition-all duration-300 transform hover:scale-105">
                    ▶ {{(__('Watch Introduction'))}}
                </a>
            </div>
        </section>
    @endif


    <section class="next-race-section px-4 sm:px-6">
        @if ($upcoming_tournament)
            <x-countdown
                :date="$upcoming_tournament->start_date"
                :title="$upcoming_tournament->title"
            />
            @push('js')
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const targetDate = new Date("{{ \Carbon\Carbon::parse($upcoming_tournament->start_date)->toIso8601String() }}").getTime();

                        function animateChange(id, value) {
                            const el = document.getElementById(id + "-flip");

                            // Skip if unchanged
                            if (el.textContent === value) return;

                            // Animate out
                            el.classList.add("translate-y-full", "opacity-0", "scale-90");

                            setTimeout(() => {
                                el.textContent = value;

                                // Reset position and animate in
                                el.classList.remove("translate-y-full", "opacity-0", "scale-90");
                                el.classList.add("translate-y-[-100%]", "opacity-0", "scale-90");

                                requestAnimationFrame(() => {
                                    el.classList.remove("translate-y-[-100%]");
                                    el.classList.add("translate-y-0", "opacity-100", "scale-100");
                                });
                            }, 200);
                        }

                        function updateCountdown() {
                            const now = new Date().getTime();
                            const distance = targetDate - now;

                            if (distance < 0) return;

                            const days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                            const hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                            const minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                            const seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');

                            animateChange("days", days);
                            animateChange("hours", hours);
                            animateChange("minutes", minutes);
                            animateChange("seconds", seconds);
                        }

                        updateCountdown();
                        setInterval(updateCountdown, 1000);
                    });
                </script>
            @endpush
        @else
            <x-countdown :title="__('No upcoming tournaments')" />
            @push('js')
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        // Just animate the zeros once for visual consistency
                        ['days', 'hours', 'minutes', 'seconds'].forEach(unit => {
                            const el = document.getElementById(unit + '-flip');
                            el.classList.add("translate-y-full", "opacity-0", "scale-90");

                            setTimeout(() => {
                                el.textContent = '00';
                                el.classList.remove("translate-y-full", "opacity-0", "scale-90");
                                el.classList.add("translate-y-0", "opacity-100", "scale-100");
                            }, 200);
                        });
                    });
                </script>
            @endpush
        @endif
    </section>

    <!-- Upcoming Events Section -->
    <section class="home-tournaments bg-black py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl text-white font-bold text-center mb-8 sm:mb-12">
                {{__('Tournaments')}}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($tournaments as $tournament)
                    <x-event-card
                        :image="\App\Support\GrStockImage::forStorage($tournament->image, 'home-tournament-'.$tournament->id)"
                        :date="$tournament->start_date"
                        :title="$tournament->title"
                        :description="$tournament->description"
                        :location="$tournament->location"
                        :id="$tournament->id"
                        :status="$tournament->status"
                        class="w-full"
                    />
                @endforeach
            </div>
        </div>
    </section>

    <x-home-video-gallery :videos="$videos" />

    <section class="bg-black py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl text-white font-bold text-center mb-8 sm:mb-12">
                {{__('News')}}
            </h2>

            @php
                $featuredNews = $news->first();
                $latestNews = $news->skip(1);
            @endphp

            @if($featuredNews)
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-8 items-start">
                    <div class="md:col-span-8">
                        <a href="{{ route('news.view', ['slug' => $featuredNews->slug]) }}"
                           class="group block overflow-hidden rounded-2xl bg-secondary-100 text-white border border-white/10 shadow-xl">
                            <img
                                src="{{ \App\Support\GrStockImage::forStorage($featuredNews->image, 'home-news-featured-'.$featuredNews->id) }}"
                                alt="{{ $featuredNews->title }}"
                                class="w-full h-[260px] sm:h-[340px] lg:h-[400px] object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                            <div class="p-4 sm:p-6">
                                <span class="inline-block mb-3 px-3 py-1 rounded-full text-xs font-semibold text-white bg-primary">
                                    {{ __('News') }}
                                </span>
                                <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold leading-snug">
                                    {{ $featuredNews->title }}
                                </h3>
                                <p class="mt-2 text-sm text-white/70">
                                    {{ \Carbon\Carbon::parse($featuredNews->created_at)->translatedFormat('M j') }} • {{ __('10 min read') }}
                                </p>
                            </div>
                        </a>
                    </div>

                    <div class="md:col-span-4">
                        <div class="rounded-2xl bg-secondary-100 text-white p-4 sm:p-5 shadow-xl border border-white/10">
                            <h3 class="text-xl font-bold mb-4">{{ __('Latest News') }}</h3>

                            <div class="space-y-3">
                                @forelse($latestNews as $post)
                                    <a href="{{ route('news.view', ['slug' => $post->slug]) }}"
                                       class="group flex gap-3 rounded-xl p-2 transition-colors hover:bg-white/5">
                                        <img
                                            src="{{ \App\Support\GrStockImage::forStorage($post->image, 'home-news-'.$post->id) }}"
                                            alt="{{ $post->title }}"
                                            class="w-20 h-16 sm:w-24 sm:h-18 rounded-lg object-cover shrink-0"
                                        >
                                        <div class="min-w-0">
                                            <h4 class="text-sm sm:text-base font-semibold leading-tight group-hover:text-primary transition-colors">
                                                {{ $post->title }}
                                            </h4>
                                            <p class="mt-1 text-xs text-white/60">
                                                {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('M j') }} • {{ __('10 min read') }}
                                            </p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-sm text-white/60">{{ __('No additional posts yet.') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

{{--    <section class="bg-[#111] text-white py-12 border-t border-[#17171A]">--}}
{{--        <div class="container mx-auto px-4">--}}
{{--            <h2 class="text-3xl md:text-4xl font-semibold text-center mb-10">{{__('Community Highlights')}}</h2>--}}

{{--            <div class="grid md:grid-cols-2 gap-8">--}}
{{--                <!-- Card 1 -->--}}
{{--                <div class="flex items-start gap-4 bg-[#111] p-6 rounded-lg">--}}
{{--                    <img src="{{asset('img/image 11.png')}}" alt="User Image" class="w-12 h-12 rounded-full">--}}
{{--                    <div>--}}
{{--                        <h3 class="font-bold text-lg">{{__('Mohammed (Winner of GR Supra GT Cup Round 2)')}}</h3>--}}
{{--                        <p class="text-sm text-gray-300 mt-1">--}}
{{--                            {{__('An incredible performance at Spa-Francorchamps secures Davidson’s second victory of the season.')}}--}}
{{--                        </p>--}}
{{--                        <div class="flex text-xs text-gray-500 mt-2 space-x-4">--}}
{{--                            <span>{{__('2 days ago')}}</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Card 2 -->--}}
{{--                <div class="flex items-start gap-4 bg-[#111] p-6 rounded-lg">--}}
{{--                    <img src="{{asset('img/image 12.png')}}" alt="User Image" class="w-12 h-12 rounded-full">--}}
{{--                    <div>--}}
{{--                        <h3 class="font-bold text-lg">{{__('Toni Breidinger')}}</h3>--}}
{{--                        <p class="text-sm text-gray-300 mt-1">--}}
{{--                            {{__('Toyota Gazoo Racing announces new track pack featuring iconic Japanese circuits.')}}--}}
{{--                        </p>--}}
{{--                        <div class="flex text-xs text-gray-500 mt-2 space-x-4">--}}
{{--                            <span>{{__('4 days ago')}}</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}


</x-app-layout>
