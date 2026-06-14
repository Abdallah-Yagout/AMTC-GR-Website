<div id="gtcup-splash-root" role="dialog" aria-modal="true" aria-labelledby="gtcup-splash-title">
    <span id="gtcup-splash-title" class="gtcup-sr-only">{{ __('GT Cup splash') }}</span>
    <div class="gtcup-splash-streaks" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>
    <div class="gtcup-splash-logo-wrap">
        <img src="{{ asset('img/gtcup-splash-logo.png') }}" alt="{{ __('Toyota Gazoo Racing GT Cup Yemen 2026') }}" width="520">
    </div>
</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var root = document.getElementById('gtcup-splash-root');
    if (!root || document.documentElement.classList.contains('gtcup-splash-dismissed')) {
        return;
    }

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    document.body.style.overflow = 'hidden';

    function finish() {
        try {
            sessionStorage.setItem('gtcup26_splash', '1');
        } catch (e) {}
        document.documentElement.classList.add('gtcup-splash-dismissed');
        document.body.style.overflow = '';
        root.remove();
    }

    function dismiss() {
        if (root.classList.contains('gtcup-splash-leaving')) {
            return;
        }
        root.classList.add('gtcup-splash-leaving');
        window.setTimeout(finish, reduceMotion ? 50 : 550);
    }

    var delayMs = reduceMotion ? 400 : 3200;
    window.setTimeout(dismiss, delayMs);
});
</script>
@endpush
