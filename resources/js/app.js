import './bootstrap';

function initVideoGallery(root) {
    const track = root.querySelector('[data-video-track]');
    const slides = Array.from(root.querySelectorAll('[data-video-slide]'));
    const dotsHost = root.querySelector('[data-video-dots]');
    const prevButton = root.querySelector('[data-video-prev]');
    const nextButton = root.querySelector('[data-video-next]');
    const modal = root.querySelector('[data-video-modal]');
    const modalPlayer = root.querySelector('[data-video-player]');
    const modalTitle = root.querySelector('#video-modal-title');

    if (!track || slides.length === 0 || !dotsHost || !modal || !modalPlayer || !modalTitle) {
        return;
    }

    let activeIndex = 0;
    let autoplayTimer = null;

    const dots = slides.map((_, index) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'video-coverflow-dot';
        dot.setAttribute('aria-label', `Go to video ${index + 1}`);
        dot.addEventListener('click', () => {
            activeIndex = index;
            render();
        });
        dotsHost.appendChild(dot);
        return dot;
    });

    function render() {
        const slidesLength = slides.length;
        slides.forEach((slide, index) => {
            let offset = index - activeIndex;
            if (offset > slidesLength / 2) {
                offset -= slidesLength;
            } else if (offset < -slidesLength / 2) {
                offset += slidesLength;
            }
            const absOffset = Math.abs(offset);
            const rotate = offset * -18;
            const shiftX = offset * 44;
            const scale = 1 - Math.min(absOffset * 0.18, 0.42);
            const opacity = absOffset > 2 ? 0 : (1 - (absOffset * 0.22));

            slide.style.transform = `translateX(calc(-50% + ${shiftX}%)) rotateY(${rotate}deg) scale(${scale})`;
            slide.style.zIndex = String(100 - absOffset);
            slide.style.opacity = String(Math.max(opacity, 0));
            slide.classList.toggle('is-active', index === activeIndex);
        });

        dots.forEach((dot, index) => {
            dot.classList.toggle('is-active', index === activeIndex);
        });
    }

    function closeModal() {
        modal.classList.add('hidden');
        modalPlayer.innerHTML = '';
        document.body.style.overflow = '';
    }

    function openModal(slide) {
        const title = slide.dataset.title || '';
        const sourceType = slide.dataset.sourceType;
        const embedUrl = slide.dataset.embedUrl;
        const uploadUrl = slide.dataset.uploadUrl;

        modalTitle.textContent = title;

        if (sourceType === 'external' && embedUrl) {
            modalPlayer.innerHTML = `<iframe src="${embedUrl}" allowfullscreen allow="autoplay; encrypted-media; picture-in-picture"></iframe>`;
        } else if (sourceType === 'upload' && uploadUrl) {
            modalPlayer.innerHTML = `<video src="${uploadUrl}" controls autoplay playsinline></video>`;
        } else {
            modalPlayer.innerHTML = '<div class="p-6 text-white">Video unavailable.</div>';
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    slides.forEach((slide) => {
        const openButton = slide.querySelector('[data-video-open]');
        if (!openButton) {
            return;
        }
        openButton.addEventListener('click', () => openModal(slide));
    });

    root.querySelectorAll('[data-video-close]').forEach((closeEl) => {
        closeEl.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    if (prevButton) {
        prevButton.addEventListener('click', () => {
            activeIndex = (activeIndex - 1 + slides.length) % slides.length;
            render();
            restartAutoplay();
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            activeIndex = (activeIndex + 1) % slides.length;
            render();
            restartAutoplay();
        });
    }

    let startX = 0;
    let isSwiping = false;

    track.addEventListener('touchstart', (event) => {
        startX = event.touches[0].clientX;
        isSwiping = true;
    }, { passive: true });

    track.addEventListener('touchmove', (event) => {
        if (!isSwiping) {
            return;
        }
        const deltaX = event.touches[0].clientX - startX;
        if (Math.abs(deltaX) < 55) {
            return;
        }
        activeIndex = deltaX < 0
            ? (activeIndex + 1) % slides.length
            : (activeIndex - 1 + slides.length) % slides.length;
        isSwiping = false;
        render();
        restartAutoplay();
    }, { passive: true });

    track.addEventListener('touchend', () => {
        isSwiping = false;
    }, { passive: true });

    function startAutoplay() {
        if (slides.length <= 1) {
            return;
        }
        autoplayTimer = window.setInterval(() => {
            activeIndex = (activeIndex + 1) % slides.length;
            render();
        }, 4200);
    }

    function stopAutoplay() {
        if (autoplayTimer) {
            window.clearInterval(autoplayTimer);
            autoplayTimer = null;
        }
    }

    function restartAutoplay() {
        stopAutoplay();
        startAutoplay();
    }

    root.addEventListener('mouseenter', stopAutoplay);
    root.addEventListener('mouseleave', startAutoplay);
    root.addEventListener('focusin', stopAutoplay);
    root.addEventListener('focusout', startAutoplay);

    render();
    startAutoplay();
}

function initHomeVideoGalleries() {
    const galleries = document.querySelectorAll('[data-video-gallery]');
    galleries.forEach((root) => initVideoGallery(root));
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHomeVideoGalleries);
} else {
    initHomeVideoGalleries();
}

function initGRCarsPage() {
    const items = document.querySelectorAll('[data-gr-cars-reveal]');
    if (items.length === 0) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        items.forEach((item) => item.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, {
        threshold: 0.2,
    });

    items.forEach((item) => observer.observe(item));
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGRCarsPage);
} else {
    initGRCarsPage();
}

function initHomeHeroSlider() {
    const root = document.querySelector('[data-home-hero]');
    if (!root) {
        return;
    }

    const slides = Array.from(root.querySelectorAll('[data-home-hero-slide]'));
    const dotsHost = root.querySelector('[data-home-hero-dots]');
    const prevButton = root.querySelector('[data-home-hero-prev]');
    const nextButton = root.querySelector('[data-home-hero-next]');

    if (slides.length === 0 || !dotsHost) {
        return;
    }

    let activeIndex = 0;
    let autoTimer = null;
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const autoplayDelay = prefersReducedMotion ? 7000 : 4800;

    const dots = slides.map((_, index) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'home-hero-dot';
        dot.setAttribute('aria-label', `Go to slide ${index + 1}`);
        dot.addEventListener('click', () => {
            activeIndex = index;
            render();
            restartAutoplay();
        });
        dotsHost.appendChild(dot);
        return dot;
    });

    function render() {
        slides.forEach((slide, index) => {
            const isActive = index === activeIndex;
            slide.classList.toggle('is-active', isActive);
            slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
        });

        dots.forEach((dot, index) => {
            dot.classList.toggle('is-active', index === activeIndex);
        });
    }

    function next() {
        activeIndex = (activeIndex + 1) % slides.length;
        render();
    }

    function prev() {
        activeIndex = (activeIndex - 1 + slides.length) % slides.length;
        render();
    }

    function startAutoplay() {
        if (slides.length <= 1) {
            return;
        }
        autoTimer = window.setInterval(next, autoplayDelay);
    }

    function stopAutoplay() {
        if (autoTimer) {
            window.clearInterval(autoTimer);
            autoTimer = null;
        }
    }

    function restartAutoplay() {
        stopAutoplay();
        startAutoplay();
    }

    if (prevButton) {
        prevButton.addEventListener('click', () => {
            prev();
            restartAutoplay();
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            next();
            restartAutoplay();
        });
    }

    root.addEventListener('mouseenter', stopAutoplay);
    root.addEventListener('mouseleave', startAutoplay);
    root.addEventListener('focusin', stopAutoplay);
    root.addEventListener('focusout', startAutoplay);

    render();
    startAutoplay();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHomeHeroSlider);
} else {
    initHomeHeroSlider();
}

function initTransparentTopHeader() {
    const header = document.querySelector('.site-header-shell.site-header-home');
    if (!header) {
        return;
    }

    const topThreshold = 24;
    let rafId = null;

    const syncHeaderState = () => {
        const isAtTop = window.scrollY <= topThreshold;
        header.classList.toggle('is-at-top', isAtTop);
    };

    const onScroll = () => {
        if (rafId) {
            return;
        }
        rafId = window.requestAnimationFrame(() => {
            syncHeaderState();
            rafId = null;
        });
    };

    syncHeaderState();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', syncHeaderState);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTransparentTopHeader);
} else {
    initTransparentTopHeader();
}

function initChatbotWidget() {
    const root = document.querySelector('[data-chatbot]');
    if (!root) {
        return;
    }

    const toggleButton = root.querySelector('[data-chatbot-toggle]');
    const closeButton = root.querySelector('[data-chatbot-close]');
    const panel = root.querySelector('[data-chatbot-panel]');
    const messagesHost = root.querySelector('[data-chatbot-messages]');
    const form = root.querySelector('[data-chatbot-form]');
    const input = root.querySelector('[data-chatbot-input]');
    const sendButton = root.querySelector('[data-chatbot-send]');
    const status = root.querySelector('[data-chatbot-status]');
    const endpoint = root.dataset.chatbotEndpoint;
    const locale = root.dataset.chatbotLocale || 'en';

    if (!toggleButton || !panel || !messagesHost || !form || !input || !sendButton || !status || !endpoint) {
        return;
    }

    const historyStorageKey = `chatbot_history_${locale}`;
    const sessionStorageKey = `chatbot_session_${locale}`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    let isSubmitting = false;
    let sessionId = window.sessionStorage.getItem(sessionStorageKey);
    if (!sessionId) {
        sessionId = (window.crypto?.randomUUID?.() || `${Date.now()}_${Math.random().toString(16).slice(2)}`);
        window.sessionStorage.setItem(sessionStorageKey, sessionId);
    }

    function setPanelOpen(open) {
        root.classList.toggle('is-open', open);
        panel.setAttribute('aria-hidden', open ? 'false' : 'true');
        toggleButton.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) {
            input.focus({ preventScroll: true });
            scrollMessagesToBottom();
        }
    }

    function scrollMessagesToBottom() {
        messagesHost.scrollTop = messagesHost.scrollHeight;
    }

    function setStatusText(text) {
        status.textContent = text;
    }

    function setSubmitting(submitting) {
        isSubmitting = submitting;
        sendButton.disabled = submitting;
        input.disabled = submitting;
        if (submitting) {
            setStatusText('Assistant is typing...');
        } else if (status.textContent === 'Assistant is typing...') {
            setStatusText('');
        }
    }

    function createBubble(role, text) {
        const bubble = document.createElement('article');
        bubble.className = `chatbot-widget-bubble ${role === 'user' ? 'chatbot-widget-bubble-user' : 'chatbot-widget-bubble-bot'}`;
        bubble.textContent = text;
        return bubble;
    }

    function clearNonDefaultMessages() {
        const bubbles = messagesHost.querySelectorAll('.chatbot-widget-bubble');
        bubbles.forEach((bubble, index) => {
            if (index === 0) {
                return;
            }
            bubble.remove();
        });
    }

    function addMessage(role, text) {
        const bubble = createBubble(role, text);
        messagesHost.appendChild(bubble);
        scrollMessagesToBottom();
    }

    function persistHistory() {
        const bubbles = Array.from(messagesHost.querySelectorAll('.chatbot-widget-bubble'));
        const history = bubbles
            .slice(1)
            .map((bubble) => ({
                role: bubble.classList.contains('chatbot-widget-bubble-user') ? 'user' : 'bot',
                text: bubble.textContent || '',
            }))
            .filter((entry) => entry.text.trim() !== '')
            .slice(-24);
        window.sessionStorage.setItem(historyStorageKey, JSON.stringify(history));
    }

    function restoreHistory() {
        try {
            const raw = window.sessionStorage.getItem(historyStorageKey);
            if (!raw) {
                return;
            }
            const history = JSON.parse(raw);
            if (!Array.isArray(history) || history.length === 0) {
                return;
            }
            clearNonDefaultMessages();
            history.forEach((entry) => {
                if (!entry || typeof entry.text !== 'string') {
                    return;
                }
                const role = entry.role === 'user' ? 'user' : 'bot';
                addMessage(role, entry.text);
            });
        } catch (error) {
            window.sessionStorage.removeItem(historyStorageKey);
        }
    }

    async function submitMessage(message) {
        const response = await window.fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                message,
                session_id: sessionId,
                page_url: window.location.href,
            }),
        });

        let data = null;
        try {
            data = await response.json();
        } catch (error) {
            data = null;
        }

        if (!response.ok) {
            const fallbackError = 'The assistant is currently unavailable. Please try again.';
            const serverMessage = data && typeof data.reply === 'string' ? data.reply : fallbackError;
            throw new Error(serverMessage);
        }

        return data && typeof data.reply === 'string'
            ? data.reply
            : 'I received your message, but I could not generate a response yet.';
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (isSubmitting) {
            return;
        }

        const message = input.value.trim();
        if (!message) {
            return;
        }

        addMessage('user', message);
        input.value = '';
        setSubmitting(true);

        try {
            const reply = await submitMessage(message);
            addMessage('bot', reply);
            setStatusText('');
        } catch (error) {
            const fallback = 'The assistant is currently unavailable. Please try again.';
            const errorMessage = error instanceof Error && error.message ? error.message : fallback;
            addMessage('bot', errorMessage);
            setStatusText('Connection issue detected.');
        } finally {
            setSubmitting(false);
            persistHistory();
            input.focus({ preventScroll: true });
        }
    });

    toggleButton.addEventListener('click', () => {
        setPanelOpen(!root.classList.contains('is-open'));
    });

    if (closeButton) {
        closeButton.addEventListener('click', () => setPanelOpen(false));
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && root.classList.contains('is-open')) {
            setPanelOpen(false);
        }
    });

    restoreHistory();
    setPanelOpen(false);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initChatbotWidget);
} else {
    initChatbotWidget();
}
