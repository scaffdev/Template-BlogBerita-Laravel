import './bootstrap';

/* ==========================================================================
   BeritaKita — interaksi frontend (vanilla JS, tanpa dependensi eksternal)
   ========================================================================== */

const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
const prefersReduced = window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches ?? false;

/* ── Toast ───────────────────────────────────────────────────────────── */
window.bkToast = function bkToast(message, type = 'success') {
    let wrap = $('#toast-wrap');
    if (!wrap) {
        wrap = document.createElement('div');
        wrap.id = 'toast-wrap';
        wrap.className = 'fixed bottom-24 lg:bottom-6 left-1/2 -translate-x-1/2 z-[70] flex flex-col items-center gap-2 px-4 w-full max-w-md pointer-events-none';
        document.body.appendChild(wrap);
    }
    const el = document.createElement('div');
    el.className =
        'toast pointer-events-auto w-full rounded-xl px-4 py-3 text-sm font-semibold shadow-xl border ' +
        (type === 'error'
            ? 'bg-red-50 border-red-200 text-red-800'
            : 'bg-emerald-50 border-emerald-200 text-emerald-800');
    el.setAttribute('role', 'status');
    el.textContent = message;
    wrap.appendChild(el);
    setTimeout(() => el.remove(), 3200);
};

/* ── Mode gelap ──────────────────────────────────────────────────────── */
const DarkMode = {
    key: 'bk-dark',
    init() {
        const self = this;
        this.apply(this.preference());
        $$('[data-dark-toggle]').forEach((btn) => {
            btn.addEventListener('click', () => self.apply(!document.documentElement.classList.contains('dark'), true));
        });
        document.addEventListener('keydown', (e) => {
            if ((e.key === 'd' || e.key === 'D') && !/INPUT|TEXTAREA|SELECT/.test(document.activeElement?.tagName || '')) {
                self.apply(!document.documentElement.classList.contains('dark'), true);
            }
        });
        window.matchMedia?.('(prefers-color-scheme: dark)').addEventListener?.('change', (e) => {
            if (self.preference() === null) self.apply(e.matches);
        });
    },
    preference() {
        try {
            const v = localStorage.getItem(this.key);
            if (v === '1') return true;
            if (v === '0') return false;
        } catch (e) {}
        return null;
    },
    apply(dark, persist = false) {
        document.documentElement.classList.toggle('dark', !!dark);
        if (persist) {
            try {
                localStorage.setItem(this.key, dark ? '1' : '0');
            } catch (e) {}
        }
        $$('[data-dark-toggle]').forEach((btn) => {
            btn.setAttribute('aria-pressed', dark ? 'true' : 'false');
            btn.setAttribute('aria-label', dark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap');
            const sun = $('[data-icon-sun]', btn);
            const moon = $('[data-icon-moon]', btn);
            if (sun) sun.classList.toggle('hidden', !dark);
            if (moon) moon.classList.toggle('hidden', !!dark);
        });
    },
};

/* ── Ukuran teks baca ────────────────────────────────────────────────── */
const ReadSize = {
    key: 'bk-read-size',
    min: 0.95,
    max: 1.6,
    step: 0.125,
    current() {
        try {
            return parseFloat(localStorage.getItem(this.key)) || 1.125;
        } catch (e) {
            return 1.125;
        }
    },
    set(value) {
        const next = Math.min(this.max, Math.max(this.min, +value.toFixed(3)));
        document.documentElement.style.setProperty('--read-size', next + 'rem');
        try {
            localStorage.setItem(this.key, String(next));
        } catch (e) {}
        $$('[data-read-size-value]').forEach((el) => (el.textContent = Math.round((next / 1.125) * 100) + '%'));
    },
    init() {
        this.set(this.current());
        const self = this;
        $$('[data-font]').forEach((btn) => {
            btn.addEventListener('click', () => self.set(self.current() + (btn.dataset.font === 'up' ? self.step : -self.step)));
        });
        $$('[data-font-reset]').forEach((btn) => btn.addEventListener('click', () => self.set(1.125)));
    },
};
window.bkFont = (delta) => ReadSize.set(ReadSize.current() + delta);

/* ── Progress baca ───────────────────────────────────────────────────── */
function initProgress() {
    const bar = $('[data-progress-bar]');
    if (!bar) return;
    const update = () => {
        const h = document.documentElement;
        const total = h.scrollHeight - h.clientHeight;
        bar.style.width = (total > 0 ? (h.scrollTop / total) * 100 : 0) + '%';
    };
    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
}

/* ── Daftar isi otomatis ─────────────────────────────────────────────── */
function initToc() {
    const nav = $('[data-toc]');
    const body = $('#isi-artikel');
    if (!nav || !body) return;
    const headings = $$('#isi-artikel h2, #isi-artikel h3');
    if (headings.length < 2) {
        nav.remove();
        return;
    }
    const ol = document.createElement('ol');
    ol.className = 'space-y-1.5 list-decimal ml-5';
    headings.forEach((el, i) => {
        if (!el.id) el.id = 'bagian-' + (i + 1);
        el.classList.add('scroll-mt-28');
        const li = document.createElement('li');
        if (el.tagName === 'H3') li.className = 'ml-4';
        const a = document.createElement('a');
        a.href = '#' + el.id;
        a.className = 'font-medium hover:text-red-700';
        a.textContent = el.innerText.trim();
        li.appendChild(a);
        ol.appendChild(li);
    });
    const list = $('[data-toc-list]', nav);
    if (list) list.replaceWith(ol);
    const count = $('[data-toc-count]', nav);
    if (count) count.textContent = headings.length + ' bagian';
    nav.classList.remove('hidden');
}

/* ── Menu mobile ─────────────────────────────────────────────────────── */
function initMobileMenu() {
    const btn = $('[data-menu-btn]');
    const menu = $('[data-mobile-menu]');
    if (!btn || !menu) return;
    const setOpen = (open) => {
        menu.classList.toggle('hidden', !open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        $$('[data-menu-icon]', btn).forEach((i) => i.classList.toggle('hidden', i.dataset.menuIcon !== (open ? 'close' : 'open')));
        document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 1024);
    };
    btn.addEventListener('click', () => setOpen(menu.classList.contains('hidden')));
    $$('a', menu).forEach((a) => a.addEventListener('click', () => setOpen(false)));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') setOpen(false);
    });
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) setOpen(false);
    });
    setOpen(false);
}

/* ── Pause ticker ────────────────────────────────────────────────────── */
function initTicker() {
    const wrap = $('[data-ticker]');
    const btn = $('[data-ticker-toggle]');
    if (!wrap || !btn) return;
    if (prefersReduced) {
        wrap.dataset.paused = 'true';
        btn.textContent = 'Putar';
    }
    btn.addEventListener('click', () => {
        const paused = wrap.dataset.paused === 'true';
        wrap.dataset.paused = paused ? 'false' : 'true';
        btn.textContent = paused ? 'Jeda' : 'Putar';
        btn.setAttribute('aria-pressed', paused ? 'false' : 'true');
    });
}

/* ── Kembali ke atas ─────────────────────────────────────────────────── */
function initToTop() {
    const btn = $('[data-to-top]');
    if (!btn) return;
    const update = () => btn.classList.toggle('hidden', window.scrollY < 600);
    update();
    window.addEventListener('scroll', update, { passive: true });
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: prefersReduced ? 'auto' : 'smooth' }));
}

/* ── Tab (Terpopuler / Terbaru) ──────────────────────────────────────── */
function initTabs() {
    $$('[data-tabs]').forEach((group) => {
        const tabs = $$('[data-tab]', group);
        const panels = $$('[data-panel]', group);
        const activate = (name) => {
            tabs.forEach((t) => {
                const on = t.dataset.tab === name;
                t.setAttribute('aria-selected', on ? 'true' : 'false');
                t.tabIndex = on ? 0 : -1;
                t.className = on
                    ? 'flex-1 py-2 rounded-lg bg-slate-900 text-white shadow transition'
                    : 'flex-1 py-2 rounded-lg text-slate-500 hover:text-slate-900 transition';
            });
            panels.forEach((p) => p.classList.toggle('hidden', p.dataset.panel !== name));
        };
        tabs.forEach((t) => {
            t.addEventListener('click', () => activate(t.dataset.tab));
            t.addEventListener('keydown', (e) => {
                const i = tabs.indexOf(t);
                if (e.key === 'ArrowRight') tabs[(i + 1) % tabs.length].focus();
                if (e.key === 'ArrowLeft') tabs[(i - 1 + tabs.length) % tabs.length].focus();
            });
        });
        activate(tabs[0]?.dataset.tab);
    });
}

/* ── Bagikan & salin ─────────────────────────────────────────────────── */
function initShare() {
    $$('[data-share-native]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const url = btn.dataset.url || location.href;
            const title = btn.dataset.title || document.title;
            if (navigator.share) {
                try {
                    await navigator.share({ title, url });
                } catch (e) {}
            } else {
                copy(url);
                window.bkToast('Tautan disalin ke papan klip.');
            }
        });
    });
    $$('[data-copy]').forEach((btn) => {
        btn.addEventListener('click', () => {
            copy(btn.dataset.copy);
            window.bkToast(btn.dataset.copyMessage || 'Tautan disalin.');
        });
    });
}

function copy(text) {
    if (navigator.clipboard?.writeText) {
        navigator.clipboard.writeText(text).catch(() => fallbackCopy(text));
    } else {
        fallbackCopy(text);
    }
}

function fallbackCopy(text) {
    const ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.select();
    try {
        document.execCommand('copy');
    } catch (e) {}
    ta.remove();
}

/* ── Autocomplete pencarian (aksesibel) ──────────────────────────────── */
function initSearchSuggest() {
    $$('[data-suggest-input]').forEach((input) => {
        const box = $('[data-suggest-box="' + input.id + '"]');
        if (!box) return;
        let timer;
        let items = [];
        let active = -1;

        const close = () => {
            box.classList.add('hidden');
            box.innerHTML = '';
            items = [];
            active = -1;
            input.setAttribute('aria-expanded', 'false');
        };

        const render = (data) => {
            items = data;
            if (!data.length) return close();
            box.innerHTML = data
                .map(
                    (x, i) =>
                        '<a role="option" id="' +
                        input.id +
                        '-opt-' +
                        i +
                        '" aria-selected="false" href="' +
                        x.url +
                        '"><span class="font-semibold line-clamp-1">' +
                        escapeHtml(x.title) +
                        '</span><span class="text-xs text-slate-400 shrink-0">' +
                        escapeHtml(x.category || '') +
                        '</span></a>'
                )
                .join('');
            box.classList.remove('hidden');
            input.setAttribute('aria-expanded', 'true');
            active = -1;
        };

        const setActive = (i) => {
            const opts = $$('[role="option"]', box);
            opts.forEach((o) => o.setAttribute('aria-selected', 'false'));
            if (i >= 0 && opts[i]) {
                opts[i].setAttribute('aria-selected', 'true');
                opts[i].scrollIntoView({ block: 'nearest' });
                active = i;
            }
        };

        input.addEventListener('input', () => {
            clearTimeout(timer);
            const q = input.value.trim();
            if (q.length < 2) return close();
            timer = setTimeout(async () => {
                try {
                    const r = await fetch('/cari/saran?q=' + encodeURIComponent(q), { headers: { Accept: 'application/json' } });
                    if (!r.ok) return close();
                    render(await r.json());
                } catch (e) {
                    close();
                }
            }, 220);
        });

        input.addEventListener('keydown', (e) => {
            if (box.classList.contains('hidden')) return;
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                setActive(Math.min(active + 1, items.length - 1));
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                setActive(Math.max(active - 1, 0));
            } else if (e.key === 'Enter' && active >= 0) {
                e.preventDefault();
                location.href = items[active].url;
            } else if (e.key === 'Escape') {
                close();
            }
        });

        document.addEventListener('click', (e) => {
            if (!box.contains(e.target) && e.target !== input) close();
        });
    });
}

function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

/* ── Shortcut pencarian: Ctrl/Cmd+K atau "/" ─────────────────────────── */
function initSearchShortcut() {
    document.addEventListener('keydown', (e) => {
        const typing = /INPUT|TEXTAREA|SELECT/.test(document.activeElement?.tagName || '') || document.activeElement?.isContentEditable;
        if (typing) return;
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            openSearch();
        } else if (e.key === '/') {
            e.preventDefault();
            openSearch();
        }
    });
    $$('[data-search-open]').forEach((btn) => btn.addEventListener('click', openSearch));
    function openSearch() {
        const overlay = $('[data-search-overlay]');
        const desktop = $('[data-search-input]:not([data-search-overlay] input)');
        if (window.innerWidth < 768 && overlay) return; // overlay dikelola tombol bottom-nav
        const input = (overlay && !overlay.classList.contains('hidden') ? $('input', overlay) : desktop) || $('[data-search-input]');
        if (!input) return;
        if (overlay && input.closest('[data-search-overlay]')) overlay.classList.remove('hidden');
        input.focus();
        input.select?.();
    }
}

/* ── Overlay pencarian mobile ────────────────────────────────────────── */
function initSearchOverlay() {
    const overlay = $('[data-search-overlay]');
    if (!overlay) return;
    const openers = $$('[data-search-overlay-open]');
    const close = () => {
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };
    openers.forEach((btn) =>
        btn.addEventListener('click', () => {
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => $('input', overlay)?.focus(), 50);
        })
    );
    $$('[data-search-overlay-close]').forEach((b) => b.addEventListener('click', close));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !overlay.classList.contains('hidden')) close();
    });
}

/* ── Riwayat baca + Lanjutkan membaca ────────────────────────────────── */
const History = {
    key: 'bk-history',
    read() {
        try {
            return JSON.parse(localStorage.getItem(this.key) || '[]');
        } catch (e) {
            return [];
        }
    },
    track() {
        const m = document.querySelector('[data-post-slug]');
        if (!m) return;
        const item = { slug: m.dataset.postSlug, title: m.dataset.postTitle || document.title, category: m.dataset.postCategory || '', t: Date.now() };
        const h = [item, ...this.read().filter((x) => x.slug !== item.slug)].slice(0, 12);
        try {
            localStorage.setItem(this.key, JSON.stringify(h));
        } catch (e) {}
    },
    renderSidebar() {
        const box = $('#riwayat-baca');
        if (!box) return;
        const h = this.read().slice(0, 5);
        if (!h.length) return;
        box.innerHTML =
            '<p class="font-extrabold font-headline text-lg mb-3">Terakhir dibaca</p>' +
            h.map(
                (x) =>
                    '<a href="/artikel/' +
                    encodeURIComponent(x.slug) +
                    '" class="flex gap-2 items-start text-sm font-semibold hover:text-red-700 py-2 border-b border-slate-100 last:border-0"><span class="line-clamp-2">' +
                    escapeHtml(String(x.title).slice(0, 90)) +
                    '</span></a>'
            ).join('');
        box.classList.remove('hidden');
    },
    renderHome() {
        const box = $('[data-continue-reading]');
        if (!box) return;
        const h = this.read().slice(0, 4);
        if (!h.length) return;
        box.innerHTML = h
            .map(
                (x) =>
                    '<a href="/artikel/' +
                    encodeURIComponent(x.slug) +
                    '" class="group flex gap-3 items-center bg-white hover:bg-red-50 border border-slate-200 hover:border-red-200 rounded-xl p-2.5 transition"><span class="w-12 h-12 rounded-lg bg-slate-900 text-white grid place-items-center font-extrabold shrink-0">' +
                    escapeHtml(String(x.title || 'A').slice(0, 1).toUpperCase()) +
                    '</span><span class="min-w-0"><span class="block kicker text-red-700">' +
                    escapeHtml(x.category || 'Terakhir dibaca') +
                    '</span><span class="block text-[13px] font-bold leading-snug line-clamp-2 group-hover:text-red-800">' +
                    escapeHtml(String(x.title).slice(0, 100)) +
                    '</span></span></a>'
            )
            .join('');
        const wrap = box.closest('[data-continue-reading-wrap]');
        if (wrap) wrap.classList.remove('hidden');
    },
};

/* ── Mode fokus ──────────────────────────────────────────────────────── */
function initFocusMode() {
    const btn = $('[data-focus-toggle]');
    if (!btn) return;
    const apply = (on) => {
        document.body.classList.toggle('focus-mode', on);
        btn.setAttribute('aria-pressed', on ? 'true' : 'false');
        btn.classList.toggle('bg-slate-900', on);
        btn.classList.toggle('text-white', on);
        const label = $('[data-focus-label]', btn);
        if (label) label.textContent = on ? 'Keluar mode fokus' : 'Mode fokus';
    };
    let stored = false;
    try {
        stored = localStorage.getItem('bk-focus') === '1';
    } catch (e) {}
    apply(stored);
    btn.addEventListener('click', () => {
        const on = !document.body.classList.contains('focus-mode');
        try {
            localStorage.setItem('bk-focus', on ? '1' : '0');
        } catch (e) {}
        apply(on);
    });
}

/* ── Dengarkan artikel (Text-to-Speech) ──────────────────────────────── */
function initListen() {
    const btn = $('[data-tts]');
    const body = $('#isi-artikel');
    if (!btn || !body || !('speechSynthesis' in window)) {
        btn?.remove();
        return;
    }
    const label = $('[data-tts-label]', btn);
    const iconPlay = $('[data-tts-play]', btn);
    const iconPause = $('[data-tts-pause]', btn);
    let state = 'idle';

    const render = () => {
        if (label) label.textContent = state === 'playing' ? 'Jeda' : state === 'paused' ? 'Lanjut' : 'Dengarkan';
        iconPlay?.classList.toggle('hidden', state === 'playing');
        iconPause?.classList.toggle('hidden', state !== 'playing');
        btn.setAttribute('aria-pressed', state === 'playing' ? 'true' : 'false');
    };

    btn.addEventListener('click', () => {
        if (state === 'playing') {
            speechSynthesis.pause();
            state = 'paused';
            return render();
        }
        if (state === 'paused') {
            speechSynthesis.resume();
            state = 'playing';
            return render();
        }
        const text = body.innerText.replace(/\s+/g, ' ').trim().slice(0, 100000);
        if (!text) return;
        speechSynthesis.cancel();
        const u = new SpeechSynthesisUtterance(text);
        u.lang = document.documentElement.lang || 'id-ID';
        u.rate = 1;
        u.onend = () => {
            state = 'idle';
            render();
        };
        u.onerror = () => {
            state = 'idle';
            render();
        };
        speechSynthesis.speak(u);
        state = 'playing';
        render();
    });
    window.addEventListener('pagehide', () => speechSynthesis.cancel());
    render();
}

/* ── Komentar: buka/tutup balasan ────────────────────────────────────── */
function initThreads() {
    $$('[data-thread-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.threadToggle);
            if (!target) return;
            const hidden = target.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', hidden ? 'false' : 'true');
            const count = btn.querySelector('[data-thread-count]')?.textContent || '';
            btn.querySelector('[data-thread-text]').textContent = (hidden ? 'Lihat balasan' : 'Sembunyikan balasan') + (count ? ' (' + count + ')' : '');
        });
    });
}

/* ── Dialog lapor komentar ───────────────────────────────────────────── */
function initReportDialog() {
    const dialog = $('[data-report-dialog]');
    if (!dialog || typeof dialog.showModal !== 'function') return;
    $$('[data-report-open]').forEach((btn) =>
        btn.addEventListener('click', () => {
            $('[data-report-action]', dialog).action = btn.dataset.reportOpen;
            dialog.showModal();
        })
    );
    $$('[data-report-close]', dialog).forEach((b) =>
        b.addEventListener('click', (e) => {
            e.preventDefault();
            dialog.close();
        })
    );
}

/* ── Toggle simpan/suka tanpa reload (AJAX) ──────────────────────────── */
function initToggleActions() {
    $$('form[data-ajax-toggle]').forEach((form) => {
        const button = $('button', form);
        if (!button) return;
        const isSaved = form.dataset.toggleKey === 'saved';

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (button.disabled) return;
            button.disabled = true;
            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form),
                });

                if (res.status === 401 || res.status === 419) {
                    location.href = '/login?redirect=' + encodeURIComponent(location.pathname + location.search);
                    return;
                }
                if (!res.ok) throw new Error('gagal');

                const data = await res.json();
                const on = isSaved ? data.saved : data.liked;

                button.setAttribute('aria-pressed', on ? 'true' : 'false');
                button.classList.toggle('btn-primary', on);
                button.classList.toggle('btn-outline', !on);
                const label = $('[data-state-label]', button);
                if (label) label.textContent = on ? button.dataset.stateOn : button.dataset.stateOff;
                const count = $('[data-count]', button);
                if (count && typeof data.count === 'number') count.textContent = '(' + data.count.toLocaleString('id-ID') + ')';

                window.bkToast(
                    isSaved
                        ? on
                            ? 'Artikel disimpan ke koleksi Anda.'
                            : 'Artikel dihapus dari simpanan.'
                        : on
                          ? 'Terima kasih atas apresiasinya!'
                          : 'Suka dibatalkan.'
                );
            } catch (err) {
                window.bkToast('Gagal memproses. Coba lagi.', 'error');
            } finally {
                button.disabled = false;
            }
        });
    });
}

/* ── Pesan flash auto-dismiss ────────────────────────────────────────── */
function initFlash() {
    $$('[data-auto-dismiss]').forEach((el) => setTimeout(() => el.classList.add('hidden'), 5000));
}

/* ── Menu pengguna (details) tutup saat klik di luar ─────────────────── */
function initUserMenu() {
    $$('details[data-user-menu]').forEach((d) => {
        document.addEventListener('click', (e) => {
            if (d.open && !d.contains(e.target)) d.open = false;
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') d.open = false;
        });
    });
}

/* ── Boot ────────────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    DarkMode.init();
    ReadSize.init();
    initProgress();
    initToc();
    initMobileMenu();
    initTicker();
    initToTop();
    initTabs();
    initShare();
    initSearchSuggest();
    initSearchShortcut();
    initSearchOverlay();
    History.track();
    History.renderSidebar();
    History.renderHome();
    initFocusMode();
    initListen();
    initThreads();
    initReportDialog();
    initToggleActions();
    initFlash();
    initUserMenu();
});
