@php
    $grLogoSrc = null;
    $logoPath = base_path('assets/thumb_960x960_image_1.jpg');

    if (is_file($logoPath)) {
        $mimeType = mime_content_type($logoPath) ?: 'image/png';
        $grLogoSrc = 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($logoPath));
    }
@endphp

<section
    class="chatbot-widget"
    data-chatbot
    data-chatbot-endpoint="{{ route('chatbot.message') }}"
    data-chatbot-locale="{{ app()->getLocale() }}"
>
    <button
        type="button"
        class="chatbot-widget-launcher"
        data-chatbot-toggle
        aria-expanded="false"
        aria-controls="chatbot-panel"
    >
        <span class="chatbot-widget-launcher-logo-fallback" aria-hidden="true">GR</span>
        <img
            src="{{ $grLogoSrc ?? asset('img/gtcup-splash-logo.png') }}"
            alt="{{ __('GR AI') }}"
            class="chatbot-widget-launcher-logo"
            loading="lazy"
        >
    </button>

    <div class="chatbot-widget-panel" id="chatbot-panel" data-chatbot-panel aria-hidden="true">
        <header class="chatbot-widget-header">
            <div class="chatbot-widget-header-copy">
                <h3>{{ __('GR AI') }}</h3>
                <p>{{ __('Ask anything about tournaments, news, and registration.') }}</p>
            </div>
            <button type="button" class="chatbot-widget-close" data-chatbot-close aria-label="{{ __('Close') }}">
                &times;
            </button>
        </header>

        <div class="chatbot-widget-messages" data-chatbot-messages>
            <article class="chatbot-widget-bubble chatbot-widget-bubble-bot">
                {{ __('Hi! I am GR AI. How can I help you today?') }}
            </article>
        </div>

        <div class="chatbot-widget-status" data-chatbot-status aria-live="polite"></div>

        <form class="chatbot-widget-form" data-chatbot-form>
            <label class="sr-only" for="chatbot-message">{{ __('Message') }}</label>
            <input
                id="chatbot-message"
                name="message"
                type="text"
                class="chatbot-widget-input"
                data-chatbot-input
                placeholder="{{ __('Type your question...') }}"
                maxlength="2000"
                autocomplete="off"
                required
            >
            <button type="submit" class="chatbot-widget-send" data-chatbot-send>
                {{ __('Send') }}
            </button>
        </form>
    </div>
</section>
