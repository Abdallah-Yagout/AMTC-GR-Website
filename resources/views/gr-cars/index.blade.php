<x-app-layout>
    <section class="gr-cars-page bg-black">
        <div class="gr-cars-frame">
            <div class="gr-cars-hero">
                <div class="gr-cars-hero-lights" aria-hidden="true"></div>
                <div class="gr-cars-hero-content max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="gr-cars-kicker">{{ __('Toyota Gazoo Racing') }}</p>
                    <h1 class="gr-cars-title">{{ __('GR Cars') }}</h1>
                    <p class="gr-cars-subtitle">
                        {{ __('Discover the engineering spirit of GR cars: built for speed, precision, and pure driving emotion. Explore each model with immersive visuals and race-inspired details.') }}
                    </p>
                </div>
            </div>

            <div class="gr-cars-list max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 sm:pb-20">
                @forelse($cars as $index => $car)
                    <article class="gr-cars-item {{ $index % 2 === 1 ? 'is-reversed' : '' }} gr-cars-reveal" data-gr-cars-reveal>
                        <div class="gr-cars-image-wrap">
                            <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="gr-cars-image" loading="lazy">
                        </div>
                        <div class="gr-cars-content">
                            @if($car->hero_tagline)
                                <p class="gr-cars-tagline">{{ $car->hero_tagline }}</p>
                            @endif
                            <h2 class="gr-cars-name">{{ $car->name }}</h2>
                            <p class="gr-cars-description">{{ $car->description }}</p>
                        </div>
                    </article>
                @empty
                    <div class="gr-cars-empty">
                        <h2>{{ __('No GR cars published yet') }}</h2>
                        <p>{{ __('Add your first GR car from Admin to display this page.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-app-layout>
