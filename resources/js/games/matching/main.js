/**
 * Image matching (memory) — server-verified session; records flip sequence for replay validation.
 */
function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

function jsonHeaders() {
    return {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken(),
        'X-Requested-With': 'XMLHttpRequest',
    };
}

function formatHms(totalSeconds) {
    const s = Math.max(0, Math.floor(totalSeconds));
    const h = Math.floor(s / 3600);
    const m = Math.floor((s % 3600) / 60);
    const r = s % 60;
    return [h, m, r].map((n) => String(n).padStart(2, '0')).join(':');
}

function initMatchingGame(root) {
    const startUrl = root.dataset.startUrl;
    const completeUrl = root.dataset.completeUrl;

    let faceUrls = [];
    try {
        faceUrls = JSON.parse(root.dataset.faceUrls || '[]');
    } catch {
        faceUrls = [];
    }
    const coverUrl = root.dataset.coverUrl ?? '';
    if (coverUrl) {
        root.style.setProperty('--matching-cover-url', `url(${JSON.stringify(coverUrl)})`);
    }

    const labels = {
        loading: root.dataset.msgLoading ?? 'Loading…',
        start: root.dataset.msgStart ?? 'New game',
        won: root.dataset.msgWon ?? 'Matched!',
        err: root.dataset.msgErr ?? 'Something went wrong.',
        cooldown: root.dataset.msgCooldown ?? 'You cleared the board! Points are on cooldown — play again for practice.',
        points: root.dataset.msgPoints ?? 'You earned :pts points!',
        needLogin: root.dataset.msgNeedLogin ?? 'Sign in to play and earn points.',
        cooldownLabel: root.dataset.msgCooldownLabel ?? 'Next points reward',
    };

    const statusEl = root.querySelector('[data-matching-status]');
    const gridEl = root.querySelector('[data-matching-grid]');
    const overlayEl = root.querySelector('[data-matching-overlay]');
    const overlayTitle = root.querySelector('[data-matching-overlay-title]');
    const overlayBody = root.querySelector('[data-matching-overlay-body]');
    const newBtn = root.querySelector('[data-matching-new]');
    const overlayClose = root.querySelector('[data-matching-overlay-close]');
    const cooldownRoot = root.querySelector('[data-matching-cooldown]');
    const cooldownLabelEl = root.querySelector('[data-matching-cooldown-label]');
    const cooldownTimerEl = root.querySelector('[data-matching-cooldown-timer]');
    const cooldownTrack = root.querySelector('[data-matching-cooldown-track]');
    const cooldownFill = root.querySelector('[data-matching-cooldown-fill]');

    if (overlayClose) {
        overlayClose.addEventListener('click', () => hideOverlay());
    }

    let sessionId = null;
    let deck = [];
    let flipSequence = [];
    const matched = new Set();
    let revealed = [];
    let lockBoard = false;
    let startedAt = null;

    let cooldownState = {
        pointsPerWin: 0,
        cooldownHours: 24,
        nextPointsAt: null,
        canEarnPoints: true,
    };
    let cooldownTickId = null;
    let restartAfterWinId = null;

    function clearCooldownTick() {
        if (cooldownTickId !== null) {
            window.clearInterval(cooldownTickId);
            cooldownTickId = null;
        }
    }

    function clearRestartTimer() {
        if (restartAfterWinId !== null) {
            window.clearTimeout(restartAfterWinId);
            restartAfterWinId = null;
        }
    }

    function applyCooldownFromApi(data) {
        const ppwRaw = Number(data.points_per_win ?? data.points_per_completion ?? 0);
        const ppw = ppwRaw > 0 ? ppwRaw : cooldownState.pointsPerWin;
        cooldownState.pointsPerWin = ppw;
        cooldownState.cooldownHours = Math.max(1, Number(data.points_cooldown_hours ?? data.cooldown_hours ?? 24));
        cooldownState.nextPointsAt = data.next_points_at ?? null;
        if (cooldownState.nextPointsAt) {
            cooldownState.canEarnPoints = false;
        } else {
            cooldownState.canEarnPoints = data.can_earn_points !== false;
        }

        if (!cooldownRoot || ppw <= 0) {
            if (cooldownRoot) {
                cooldownRoot.hidden = true;
            }
            clearCooldownTick();
            return;
        }

        cooldownRoot.hidden = false;
        if (cooldownLabelEl) {
            cooldownLabelEl.textContent = labels.cooldownLabel;
        }
        renderCooldownHud();
        clearCooldownTick();
        cooldownTickId = window.setInterval(renderCooldownHud, 1000);
    }

    function renderCooldownHud() {
        if (!cooldownTimerEl || !cooldownFill || !cooldownTrack) {
            return;
        }

        const hours = cooldownState.cooldownHours;
        const periodMs = hours * 3600000;

        if (cooldownState.canEarnPoints || !cooldownState.nextPointsAt) {
            cooldownTimerEl.textContent = 'READY';
            cooldownFill.style.width = '100%';
            cooldownTrack.setAttribute('aria-valuenow', '100');
            cooldownRoot?.classList.remove('is-waiting');
            cooldownRoot?.classList.add('is-ready');
            return;
        }

        const end = Date.parse(cooldownState.nextPointsAt);
        if (Number.isNaN(end)) {
            cooldownTimerEl.textContent = '--:--:--';
            return;
        }

        const now = Date.now();
        const remainingSec = Math.ceil((end - now) / 1000);
        const start = end - periodMs;
        const elapsed = Math.max(0, now - start);
        let pct = periodMs > 0 ? (elapsed / periodMs) * 100 : 100;
        pct = Math.max(0, Math.min(100, pct));

        cooldownTimerEl.textContent = formatHms(remainingSec);
        cooldownFill.style.width = `${pct}%`;
        cooldownTrack.setAttribute('aria-valuenow', String(Math.round(pct)));

        if (remainingSec <= 0) {
            cooldownState.canEarnPoints = true;
            cooldownState.nextPointsAt = null;
            cooldownTimerEl.textContent = 'READY';
            cooldownFill.style.width = '100%';
            cooldownTrack.setAttribute('aria-valuenow', '100');
            cooldownRoot?.classList.remove('is-waiting');
            cooldownRoot?.classList.add('is-ready');
            return;
        }

        cooldownRoot?.classList.add('is-waiting');
        cooldownRoot?.classList.remove('is-ready');
    }

    function setStatus(text) {
        if (statusEl) {
            statusEl.textContent = text;
        }
    }

    function renderGrid() {
        if (!gridEl) {
            return;
        }
        gridEl.innerHTML = '';
        const n = deck.length;
        const narrow = typeof window !== 'undefined' && window.matchMedia('(max-width: 420px)').matches;
        const useThreeCols = narrow && n > 0 && n % 3 === 0;
        let cols;
        if (useThreeCols) {
            cols = 3;
        } else if (n <= 12) {
            cols = 4;
        } else if (n <= 16) {
            cols = 4;
        } else {
            cols = 6;
        }
        gridEl.style.setProperty('--matching-cols', String(cols));

        deck.forEach((symbolId, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'game-matching-card';
            btn.dataset.index = String(idx);
            btn.setAttribute('aria-label', `Card ${idx + 1}`);

            const inner = document.createElement('span');
            inner.className = 'game-matching-card-inner';
            const back = document.createElement('span');
            back.className = 'game-matching-card-back';
            const front = document.createElement('span');
            front.className = 'game-matching-card-front';
            const sid = Number(symbolId);
            const src = faceUrls[sid];
            if (src) {
                const img = document.createElement('img');
                img.src = src;
                img.alt = '';
                img.loading = 'lazy';
                img.className = 'game-matching-card-img';
                img.decoding = 'async';
                front.appendChild(img);
            } else {
                front.textContent = String(symbolId);
            }
            inner.append(back, front);
            btn.appendChild(inner);
            btn.addEventListener('click', () => onCardClick(idx, btn));
            gridEl.appendChild(btn);
        });
        updateFaces();
    }

    function updateFaces() {
        if (!gridEl) {
            return;
        }
        const buttons = gridEl.querySelectorAll('.game-matching-card');
        buttons.forEach((btn) => {
            const i = Number(btn.dataset.index);
            const show =
                matched.has(i) || revealed.includes(i);
            btn.classList.toggle('is-flipped', show);
            btn.classList.toggle('is-matched', matched.has(i));
            btn.disabled = matched.has(i) || lockBoard;
        });
    }

    function onCardClick(idx, btn) {
        if (lockBoard || matched.has(idx)) {
            return;
        }
        if (revealed.length === 1 && revealed[0] === idx) {
            return;
        }

        flipSequence.push(idx);

        if (revealed.length === 0) {
            revealed = [idx];
            updateFaces();
            return;
        }

        const a = revealed[0];
        const b = idx;
        revealed = [a, b];
        updateFaces();

        if (deck[a] === deck[b]) {
            matched.add(a);
            matched.add(b);
            revealed = [];
            updateFaces();
            if (matched.size === deck.length) {
                void onWin();
            }
        } else {
            lockBoard = true;
            updateFaces();
            window.setTimeout(() => {
                revealed = [];
                lockBoard = false;
                updateFaces();
            }, 720);
        }
    }

    function showOverlay(title, body) {
        if (overlayEl && overlayTitle && overlayBody) {
            overlayTitle.textContent = title;
            overlayBody.textContent = body;
            overlayEl.hidden = false;
        }
    }

    function hideOverlay() {
        if (overlayEl) {
            overlayEl.hidden = true;
        }
    }

    function scheduleRestartAfterWin() {
        clearRestartTimer();
        const delayMs = 1400;
        restartAfterWinId = window.setTimeout(() => {
            restartAfterWinId = null;
            hideOverlay();
            void startGame();
        }, delayMs);
    }

    async function startGame() {
        clearRestartTimer();
        hideOverlay();
        setStatus(labels.loading);
        sessionId = null;
        deck = [];
        flipSequence = [];
        matched.clear();
        revealed = [];
        lockBoard = false;
        startedAt = null;
        if (gridEl) {
            gridEl.innerHTML = '';
        }

        try {
            const res = await fetch(startUrl, {
                method: 'POST',
                headers: jsonHeaders(),
            });
            const data = await res.json();
            if (!data.ok) {
                setStatus(labels.err);
                return;
            }
            sessionId = data.session_id;
            deck = data.deck;
            startedAt = Date.now();
            applyCooldownFromApi(data);
            setStatus(
                data.points_per_win
                    ? `${labels.start} · +${data.points_per_win} pts / ${data.cooldown_hours}h`
                    : labels.start,
            );
            renderGrid();
        } catch {
            setStatus(labels.err);
        }
    }

    async function onWin() {
        lockBoard = true;
        const durationMs = Math.max(0, Date.now() - (startedAt ?? Date.now()));

        showOverlay(labels.won, '…');

        try {
            const res = await fetch(completeUrl, {
                method: 'POST',
                headers: jsonHeaders(),
                body: JSON.stringify({
                    session_id: sessionId,
                    flip_sequence: flipSequence,
                    duration_ms: durationMs,
                }),
            });
            const data = await res.json();

            if (!data.ok) {
                showOverlay(labels.err, data.error ?? '');
                lockBoard = false;
                return;
            }

            applyCooldownFromApi({
                points_per_win: cooldownState.pointsPerWin,
                points_cooldown_hours: data.points_cooldown_hours ?? cooldownState.cooldownHours,
                cooldown_hours: data.points_cooldown_hours ?? cooldownState.cooldownHours,
                next_points_at: data.next_points_at,
                can_earn_points: data.can_earn_points,
            });

            let body = '';
            if (data.message === 'cooldown_active') {
                body = labels.cooldown;
            } else if (data.points_awarded > 0) {
                body = labels.points.replace(':pts', String(data.points_awarded));
            } else {
                body = labels.cooldown;
            }
            showOverlay(labels.won, body);
            scheduleRestartAfterWin();
        } catch {
            showOverlay(labels.err, '');
        }
        lockBoard = false;
    }

    if (newBtn) {
        newBtn.addEventListener('click', () => void startGame());
    }

    if (root.dataset.authed === '1') {
        void startGame();
    } else {
        setStatus(labels.needLogin);
    }
}

const root = document.getElementById('matching-game-root');
if (root) {
    initMatchingGame(root);
}
