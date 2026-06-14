<x-app-layout>
    <section class="contact-page">
        <div class="contact-shell">
            <div class="contact-hero">
                <div class="contact-hero-inner max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="contact-kicker">{{ __('Toyota Gazoo Racing') }}</p>
                    <h1 class="contact-title">{{ __('Contact') }}</h1>
                    <p class="contact-subtitle">
                        <span class="contact-subtitle-lead">{{ __('We\'d Love to Hear from You') }}</span>
                        {{ __('Reach out with your inquiries, feedback, or support requests — we\'ll get back to you as fast as we race.') }}
                    </p>
                </div>
            </div>

            <div class="contact-body max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="contact-body-grid">
                    <div class="contact-info-column order-2 lg:order-1">
                        <h2 class="contact-section-heading">{{ __('Get In Touch') }}</h2>
                        <div class="contact-info-stack">
                            <div class="contact-info-item">
                                <h3 class="contact-info-label">{{ __('Email') }}</h3>
                                <a href="mailto:contact@gryemen.com" class="contact-info-value contact-info-link">contact@gryemen.com</a>
                            </div>
                            <div class="contact-info-item">
                                <h3 class="contact-info-label">{{ __('Phone') }}</h3>
                                <p class="contact-info-value">+42387234</p>
                            </div>
                            <div class="contact-info-item">
                                <h3 class="contact-info-label">{{ __('Address') }}</h3>
                                <p class="contact-info-value">{{ __("Sana'a, Yemen") }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-form-column order-1 lg:order-2">
                        <div class="contact-form-panel">
                            <h2 class="contact-section-heading contact-section-heading--form">{{ __('Send Message') }}</h2>
                            <form method="POST" action="{{ route('contact.store') }}" class="contact-form-fields">
                                @csrf

                                @if(session('success'))
                                    <div class="contact-alert contact-alert--success" role="status">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="contact-alert contact-alert--error" role="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <div class="contact-field">
                                    <label for="name" class="contact-field-label">{{ __('Name') }}</label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ auth()->check() ? auth()->user()->name : old('name') }}"
                                        placeholder="{{ __('Name') }}"
                                        {{ auth()->check() ? 'readonly' : '' }}
                                        class="contact-input"
                                    >
                                    @error('name')
                                        <p class="contact-field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="contact-field">
                                    <label for="email" class="contact-field-label">{{ __('Email') }}</label>
                                    <input
                                        type="email"
                                        id="email"
                                        required
                                        name="email"
                                        value="{{ auth()->check() ? auth()->user()->email : old('email') }}"
                                        placeholder="{{ __('you@company.com') }}"
                                        {{ auth()->check() ? 'readonly' : '' }}
                                        class="contact-input"
                                    >
                                    @error('email')
                                        <p class="contact-field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="contact-field">
                                    <label for="message" class="contact-field-label">{{ __('Message') }}</label>
                                    <textarea
                                        id="message"
                                        name="message"
                                        required
                                        rows="5"
                                        placeholder="{{ __('Write a message here') }}"
                                        class="contact-textarea"
                                    >{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="contact-field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="contact-submit">
                                    {{ __('Send Message') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
