@props(['videos'])

@if($videos->isNotEmpty())
    <section class="home-video-gallery bg-black py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8" data-video-gallery>
        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl text-white font-bold text-center mb-10 sm:mb-12">
                {{ __('Video Gallery') }}
            </h2>

            <div class="video-coverflow">
                <button type="button" class="video-coverflow-nav video-coverflow-nav-prev" aria-label="{{ __('Previous video') }}" data-video-prev>
                    &#10094;
                </button>

                <div class="video-coverflow-track" data-video-track>
                    @foreach($videos as $video)
                        <article
                            class="video-coverflow-slide"
                            data-video-slide
                            data-title="{{ $video->title }}"
                            data-source-type="{{ $video->source_type }}"
                            data-embed-url="{{ $video->embed_url }}"
                            data-upload-url="{{ $video->playback_url }}"
                        >
                            <div class="video-coverflow-media">
                                <img
                                    src="{{ \App\Support\GrStockImage::forUrl($video->preview_image, 'video-gallery-'.$video->id) }}"
                                    alt="{{ $video->title }}"
                                    loading="lazy"
                                >
                                <div class="video-coverflow-overlay"></div>
                                <button type="button" class="video-coverflow-play" data-video-open>
                                    <span class="video-coverflow-play-icon">&#9658;</span>
                                </button>
                            </div>
                            <h3 class="video-coverflow-title">{{ $video->title }}</h3>
                            @if($video->description)
                                <p class="video-coverflow-description">{{ \Illuminate\Support\Str::limit(strip_tags($video->description), 110) }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>

                <button type="button" class="video-coverflow-nav video-coverflow-nav-next" aria-label="{{ __('Next video') }}" data-video-next>
                    &#10095;
                </button>
            </div>

            <div class="video-coverflow-dots" data-video-dots></div>
        </div>

        <div class="video-modal hidden" data-video-modal>
            <div class="video-modal-backdrop" data-video-close></div>
            <div class="video-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="video-modal-title">
                <button type="button" class="video-modal-close" aria-label="{{ __('Close video') }}" data-video-close>
                    &times;
                </button>
                <h3 id="video-modal-title" class="video-modal-title"></h3>
                <div class="video-modal-player" data-video-player></div>
            </div>
        </div>
    </section>
@endif
