<script>
try {
    if (sessionStorage.getItem('gtcup26_splash')) {
        document.documentElement.classList.add('gtcup-splash-dismissed');
    }
} catch (e) {}
</script>
<style>
    #gtcup-splash-root .gtcup-sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    #gtcup-splash-root {
        --gtcup-red: #e60012;
        position: fixed;
        inset: 0;
        z-index: 200;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #000;
        overflow: hidden;
    }

    html.gtcup-splash-dismissed #gtcup-splash-root {
        display: none !important;
    }

    #gtcup-splash-root.gtcup-splash-leaving {
        pointer-events: none;
        animation: gtcup-splash-fadeout 0.55s ease forwards;
    }

    @keyframes gtcup-splash-fadeout {
        to {
            opacity: 0;
            visibility: hidden;
        }
    }

    .gtcup-splash-streaks {
        position: absolute;
        inset: -20%;
        pointer-events: none;
        opacity: 0.35;
    }

    .gtcup-splash-streaks span {
        position: absolute;
        left: -30%;
        width: 180%;
        height: 3px;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.08),
            rgba(230, 0, 18, 0.45),
            rgba(255, 255, 255, 0.2),
            transparent
        );
        transform: skewX(-28deg) translateX(-40%);
        animation: gtcup-streak 1.1s linear infinite;
    }

    .gtcup-splash-streaks span:nth-child(1) { top: 12%; animation-delay: 0s; animation-duration: 0.95s; }
    .gtcup-splash-streaks span:nth-child(2) { top: 28%; animation-delay: -0.2s; animation-duration: 1.05s; opacity: 0.8; }
    .gtcup-splash-streaks span:nth-child(3) { top: 44%; animation-delay: -0.45s; animation-duration: 0.88s; }
    .gtcup-splash-streaks span:nth-child(4) { top: 58%; animation-delay: -0.1s; animation-duration: 1.12s; opacity: 0.75; }
    .gtcup-splash-streaks span:nth-child(5) { top: 72%; animation-delay: -0.35s; animation-duration: 0.98s; }
    .gtcup-splash-streaks span:nth-child(6) { top: 86%; animation-delay: -0.55s; animation-duration: 1.02s; opacity: 0.65; }

    @keyframes gtcup-streak {
        to {
            transform: skewX(-28deg) translateX(35%);
        }
    }

    .gtcup-splash-logo-wrap {
        position: relative;
        z-index: 2;
        max-width: min(90vw, 520px);
        padding: 1rem;
        animation: gtcup-logo-in 1s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .gtcup-splash-logo-wrap img {
        display: block;
        width: 100%;
        height: auto;
        filter: drop-shadow(0 0 28px rgba(230, 0, 18, 0.35)) drop-shadow(0 0 60px rgba(230, 0, 18, 0.15));
        animation: gtcup-logo-pulse 2.2s ease-in-out infinite;
    }

    @keyframes gtcup-logo-in {
        from {
            opacity: 0;
            transform: scale(0.88) skewX(-4deg) translateX(-18px);
        }
        to {
            opacity: 1;
            transform: scale(1) skewX(0) translateX(0);
        }
    }

    @keyframes gtcup-logo-pulse {
        0%, 100% {
            filter: drop-shadow(0 0 24px rgba(230, 0, 18, 0.3)) drop-shadow(0 0 50px rgba(230, 0, 18, 0.12));
        }
        50% {
            filter: drop-shadow(0 0 36px rgba(230, 0, 18, 0.5)) drop-shadow(0 0 72px rgba(230, 0, 18, 0.22));
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .gtcup-splash-streaks span {
            animation: none;
            opacity: 0.2;
        }

        .gtcup-splash-logo-wrap {
            animation: none;
            opacity: 1;
            transform: none;
        }

        .gtcup-splash-logo-wrap img {
            animation: none;
        }
    }
</style>
