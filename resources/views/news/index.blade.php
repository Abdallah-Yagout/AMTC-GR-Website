<x-app-layout>
    <section class="news-modern-page px-4 py-10 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-white text-center text-4xl font-bold mb-10">{{ __('News') }}</h1>

            @if($featuredNews)
                <article class="news-modern-featured">
                    <a href="{{ route('news.view', ['slug' => $featuredNews->slug]) }}" class="news-modern-featured-media">
                        <img src="{{ \App\Support\GrStockImage::forStorage($featuredNews->image, 'news-featured-'.$featuredNews->id) }}" alt="{{ $featuredNews->title }}">
                    </a>
                    <div class="news-modern-featured-content">
                        <p class="news-modern-meta">
                            {{ \Carbon\Carbon::parse($featuredNews->created_at)->translatedFormat('M j') }} • {{ __('10 min read') }}
                        </p>
                        <a href="{{ route('news.view', ['slug' => $featuredNews->slug]) }}" class="news-modern-featured-title">
                            {{ $featuredNews->title }}
                        </a>
                        <p class="news-modern-featured-excerpt">
                            {{ \Illuminate\Support\Str::limit(strip_tags((string) $featuredNews->description), 180) }}
                        </p>
                    </div>
                </article>

                <div class="news-modern-latest mt-8">
                    <div class="news-modern-latest-header">
                        <h2 class="news-modern-section-title">{{ __('Latest News') }}</h2>
                    </div>

                    <div class="news-modern-latest-grid">
                        @foreach($latestNews as $post)
                            <a href="{{ route('news.view', ['slug' => $post->slug]) }}" class="news-modern-card">
                                <img src="{{ \App\Support\GrStockImage::forStorage($post->image, 'news-card-'.$post->id) }}" alt="{{ $post->title }}" class="news-modern-card-image">
                                <div class="news-modern-card-content">
                                    <p class="news-modern-meta">
                                        {{ \Carbon\Carbon::parse($post->created_at)->translatedFormat('M j') }} • {{ __('10 min read') }}
                                    </p>
                                    <h3 class="news-modern-card-title">{{ $post->title }}</h3>
                                    <p class="news-modern-card-excerpt">
                                        {{ \Illuminate\Support\Str::limit(strip_tags((string) $post->description), 90) }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="news-modern-empty">{{ __('No news available yet.') }}</div>
            @endif
        </div>
    </section>
</x-app-layout>
