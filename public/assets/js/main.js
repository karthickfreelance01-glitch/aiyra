/* =====================================================================
   AIYRA CONSTRUCTIONS — Front-end interactions & animations
   GSAP + ScrollTrigger + Lenis (all loaded from CDN, with graceful fallback)
   ===================================================================== */
(function () {
    'use strict';

    const html = document.documentElement;
    const body = document.body;
    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
    const hasGSAP = typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined';
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isTouch = window.matchMedia('(hover: none)').matches;
    // Full preloader only on the first page of a visit; later pages open instantly
    let seenPreloader = false;
    try { seenPreloader = sessionStorage.getItem('aiyra-seen') === '1'; } catch (e) {}

    window.__aiyraReady = true;
    if (!hasGSAP || reduced) { html.classList.add('reduced'); }
    if (hasGSAP) gsap.registerPlugin(ScrollTrigger);

    /* ------------------------------------------------------------
       Smooth scroll (Lenis)
    ------------------------------------------------------------ */
    let lenis = null;
    if (hasGSAP && !reduced && typeof window.Lenis !== 'undefined') {
        lenis = new Lenis({ duration: 1.15, easing: t => Math.min(1, 1.001 - Math.pow(2, -10 * t)), smoothWheel: true });
        lenis.on('scroll', ScrollTrigger.update);
        gsap.ticker.add(t => lenis.raf(t * 1000));
        gsap.ticker.lagSmoothing(0);
    }
    const scrollTo = (target) => {
        if (lenis) lenis.scrollTo(target, { offset: -80 });
        else {
            const el = typeof target === 'string' ? $(target) : target;
            const y = typeof target === 'number' ? target : (el ? el.getBoundingClientRect().top + window.scrollY - 80 : 0);
            window.scrollTo({ top: y, behavior: 'smooth' });
        }
    };

    /* ------------------------------------------------------------
       Text splitting helpers
    ------------------------------------------------------------ */
    function splitWords(el) {
        if (el.dataset.split) return;
        el.dataset.split = '1';
        const words = el.textContent.trim().split(/\s+/);
        el.innerHTML = words.map(w => `<span class="w-wrap"><span class="w">${w}</span></span>`).join(' ');
    }
    function splitChars(el) {
        if (el.dataset.split) return;
        el.dataset.split = '1';
        el.innerHTML = el.textContent.split('').map(c => c === ' ' ? ' ' : `<span class="c">${c}</span>`).join('');
    }
    $$('.split-words').forEach(splitWords);
    $$('.split-chars').forEach(splitChars);

    /* ------------------------------------------------------------
       Preloader → intro
    ------------------------------------------------------------ */
    const preloader = $('#preloader');

    function intro() {
        if (!hasGSAP || reduced) return;
        const tl = gsap.timeline({ defaults: { ease: 'expo.out' } });
        const activeText = $('.hero__text.is-active');
        if (activeText) {
            tl.to($$('.line__inner', activeText), { y: 0, duration: 1.4, stagger: .12 }, 0);
            tl.from('.hero__grid-lines span', { scaleY: 0, duration: 1.6, stagger: .1, ease: 'power3.inOut' }, 0);
        }
        tl.to('.page-banner__title .line__inner', { y: 0, duration: 1.3 }, .05);
        tl.to('.hero-anim', { opacity: 1, y: 0, duration: 1.1, stagger: .12 }, .3);
        tl.from('.header__inner > *', { y: -30, opacity: 0, duration: 1, stagger: .08 }, .1);
        tl.from('.hero__bottom', { opacity: 0, y: 20, duration: 1 }, .7);
    }

    function finishLoading() {
        body.classList.remove('is-loading');
        if (!hasGSAP || reduced) {
            if (preloader) preloader.remove();
            return;
        }
        if (seenPreloader || !preloader) {
            if (preloader) preloader.remove();
            intro();
            return;
        }
        const tl = gsap.timeline({ onComplete: () => preloader && preloader.remove() });
        tl.to('.preloader__inner', { opacity: 0, y: -30, duration: .5, ease: 'power2.in' })
          .to('.preloader__panel--1', { yPercent: -100, duration: 1, ease: 'expo.inOut' }, '-=.1')
          .to('.preloader__panel--2', { yPercent: 100, duration: 1, ease: 'expo.inOut' }, '<')
          .add(intro, '-=.55');
    }

    if (preloader && hasGSAP && !reduced && !seenPreloader) {
        try { sessionStorage.setItem('aiyra-seen', '1'); } catch (e) {}
        body.classList.add('is-loading');
        const counter = { v: 0 };
        let loaded = document.readyState === 'complete';
        window.addEventListener('load', () => { loaded = true; });
        const countEl = $('#preCount');
        const bar = $('.preloader__bar span');
        gsap.to(counter, {
            v: 90, duration: 1.2, ease: 'power2.out',
            onUpdate: () => { if (countEl) countEl.textContent = Math.round(counter.v); bar.style.width = counter.v + '%'; },
            onComplete: function waitLoad() {
                const done = () => gsap.to(counter, {
                    v: 100, duration: .35,
                    onUpdate: () => { if (countEl) countEl.textContent = Math.round(counter.v); bar.style.width = counter.v + '%'; },
                    onComplete: finishLoading
                });
                if (loaded) done();
                else {
                    window.addEventListener('load', done, { once: true });
                    setTimeout(() => { if (!loaded) { loaded = true; done(); } }, 3500);
                }
            }
        });
    } else {
        finishLoading();
    }

    /* ------------------------------------------------------------
       Custom cursor
    ------------------------------------------------------------ */
    const cursor = $('#cursor'), dot = $('#cursorDot');
    if (cursor && hasGSAP && !isTouch && window.innerWidth > 991) {
        const label = $('.cursor__label', cursor);
        const xTo = gsap.quickTo(cursor, 'x', { duration: .5, ease: 'power3' });
        const yTo = gsap.quickTo(cursor, 'y', { duration: .5, ease: 'power3' });
        const dxTo = gsap.quickTo(dot, 'x', { duration: .1 });
        const dyTo = gsap.quickTo(dot, 'y', { duration: .1 });
        window.addEventListener('mousemove', e => {
            body.classList.add('has-cursor');
            xTo(e.clientX); yTo(e.clientY); dxTo(e.clientX); dyTo(e.clientY);
        });
        document.addEventListener('mouseleave', () => body.classList.remove('has-cursor'));
        document.addEventListener('mouseover', e => {
            const t = e.target.closest('[data-cursor], a, button, input, textarea, select, label');
            cursor.classList.remove('is-hover', 'is-view');
            if (!t) return;
            const type = t.dataset.cursor;
            if (type === 'view' || type === 'zoom') {
                cursor.classList.add('is-view');
                label.textContent = type === 'zoom' ? 'Zoom' : 'View';
            } else {
                cursor.classList.add('is-hover');
            }
        });
    }

    /* ------------------------------------------------------------
       Header: sticky + hide on scroll down
    ------------------------------------------------------------ */
    const header = $('#header');
    const toTop = $('#toTop');
    const toTopCircle = toTop ? $('circle', toTop) : null;
    let lastY = 0;
    function onScroll() {
        const y = window.scrollY;
        if (header) {
            header.classList.toggle('is-sticky', y > 120);
            header.classList.toggle('is-hidden', y > 500 && y > lastY && !body.classList.contains('menu-open'));
        }
        if (toTop) {
            toTop.classList.toggle('is-visible', y > 600);
            const max = document.documentElement.scrollHeight - window.innerHeight;
            const p = max > 0 ? y / max : 0;
            if (toTopCircle) toTopCircle.style.strokeDashoffset = 138.2 * (1 - p);
        }
        lastY = y;
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    if (toTop) toTop.addEventListener('click', () => scrollTo(0));
    $$('[data-scroll-to]').forEach(a => a.addEventListener('click', e => { e.preventDefault(); scrollTo(a.getAttribute('href')); }));

    /* Mobile menu */
    const burger = $('#burger');
    if (burger) {
        burger.addEventListener('click', () => {
            body.classList.toggle('menu-open');
            if (lenis) body.classList.contains('menu-open') ? lenis.stop() : lenis.start();
        });
    }

    /* ------------------------------------------------------------
       Hero slider
    ------------------------------------------------------------ */
    const hero = $('#hero');
    if (hero) {
        const slides = $$('.hero__slide', hero);
        const texts = $$('.hero__text', hero);
        const current = $('.hero__current', hero);
        const progress = $('.hero__progress span', hero);
        let index = 0, timer = null, busy = false;
        const DURATION = 7;

        function runProgress() {
            if (!progress) return;
            if (hasGSAP) {
                gsap.killTweensOf(progress);
                gsap.fromTo(progress, { width: '0%' }, { width: '100%', duration: DURATION, ease: 'none' });
            }
        }
        function go(next) {
            if (busy || slides.length < 2) return;
            next = (next + slides.length) % slides.length;
            if (next === index) return;
            busy = true;
            const oldText = texts[index], newText = texts[next];
            slides[index].classList.remove('is-active');
            slides[next].classList.add('is-active');
            // restart ken burns
            const img = $('.hero__img', slides[next]);
            img.style.animation = 'none'; void img.offsetWidth; img.style.animation = '';
            if (hasGSAP && !reduced) {
                gsap.timeline({ onComplete: () => { busy = false; } })
                    .to($$('.line__inner', oldText), { y: '-110%', duration: .7, stagger: .06, ease: 'expo.in' })
                    .add(() => { oldText.classList.remove('is-active'); newText.classList.add('is-active'); })
                    .fromTo($$('.line__inner', newText), { y: '110%' }, { y: 0, duration: 1.1, stagger: .1, ease: 'expo.out' });
            } else {
                oldText.classList.remove('is-active'); newText.classList.add('is-active'); busy = false;
            }
            index = next;
            if (current) current.textContent = String(index + 1).padStart(2, '0');
            restart();
        }
        function restart() {
            clearInterval(timer);
            runProgress();
            timer = setInterval(() => go(index + 1), DURATION * 1000);
        }
        $('[data-hero-next]', hero)?.addEventListener('click', () => go(index + 1));
        $('[data-hero-prev]', hero)?.addEventListener('click', () => go(index - 1));
        if (slides.length > 1) restart();

        // Mouse-move parallax on hero content
        if (hasGSAP && !isTouch && !reduced) {
            hero.addEventListener('mousemove', e => {
                const rx = (e.clientX / window.innerWidth - .5), ry = (e.clientY / window.innerHeight - .5);
                gsap.to('.hero__img', { x: rx * -30, y: ry * -20, duration: 1.2, ease: 'power2.out' });
            });
            gsap.to('.hero__content', { yPercent: 25, opacity: .2, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true } });
        }
    }

    /* ------------------------------------------------------------
       Scroll-driven animations
    ------------------------------------------------------------ */
    if (hasGSAP && !reduced) {
        // Generic reveal
        $$('[data-reveal]').forEach(el => {
            gsap.to(el, { opacity: 1, y: 0, duration: 1.1, ease: 'expo.out', scrollTrigger: { trigger: el, start: 'top 88%' } });
        });
        // Staggered children
        $$('[data-stagger]').forEach(el => {
            gsap.to(el.children, { opacity: 1, y: 0, duration: 1, stagger: .1, ease: 'expo.out', scrollTrigger: { trigger: el, start: 'top 85%' } });
        });
        // Word-split headings
        $$('.split-words').forEach(el => {
            gsap.from($$('.w', el), { yPercent: 110, rotate: 4, duration: 1.1, stagger: .035, ease: 'expo.out', scrollTrigger: { trigger: el, start: 'top 88%' } });
        });
        $$('.split-chars').forEach(el => {
            gsap.from($$('.c', el), { yPercent: 100, opacity: 0, duration: .9, stagger: .025, ease: 'expo.out', scrollTrigger: { trigger: el, start: 'top 90%' } });
        });
        // Image clip reveal
        $$('.img-reveal').forEach(el => {
            const img = $('img', el);
            const tl = gsap.timeline({ scrollTrigger: { trigger: el, start: 'top 85%' } });
            tl.fromTo(el, { clipPath: 'inset(0 100% 0 0)' }, { clipPath: 'inset(0 0% 0 0)', duration: 1.4, ease: 'expo.inOut' });
            if (img) tl.to(img, { scale: 1, duration: 1.8, ease: 'expo.out' }, '-=1');
        });
        // Parallax elements
        $$('[data-parallax]').forEach(el => {
            gsap.to(el, { yPercent: parseFloat(el.dataset.parallax) || 10, ease: 'none', scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true } });
        });
        $$('[data-parallax-bg]').forEach(el => {
            gsap.fromTo(el, { yPercent: -10 }, { yPercent: 10, ease: 'none', scrollTrigger: { trigger: el.parentElement, start: 'top bottom', end: 'bottom top', scrub: true } });
        });
        // Process line
        $$('.process').forEach(sec => {
            const fill = $('.process__fill', sec);
            if (fill) gsap.to(fill, { scaleX: 1, ease: 'none', scrollTrigger: { trigger: sec, start: 'top 70%', end: 'bottom 70%', scrub: 1 } });
        });
        // Stats band subtle scale
        $$('.stats').forEach(s => gsap.from(s, { clipPath: 'inset(8% 4% 8% 4% round 30px)', ease: 'none', scrollTrigger: { trigger: s, start: 'top bottom', end: 'top 50%', scrub: true } }));

        // Horizontal projects (desktop only)
        const hp = $('.h-projects');
        if (hp) {
            const mm = gsap.matchMedia();
            mm.add('(min-width: 992px)', () => {
                const track = $('.h-projects__track', hp);
                const bar = $('.h-projects__progress span', hp);
                const dist = () => Math.max(0, track.scrollWidth - window.innerWidth);
                const tween = gsap.to(track, {
                    x: () => -dist(), ease: 'none',
                    scrollTrigger: {
                        trigger: hp, start: 'top top', end: () => '+=' + dist(), pin: '.h-projects__pin',
                        scrub: 1, invalidateOnRefresh: true, anticipatePin: 1,
                        onUpdate: self => { if (bar) bar.style.width = (self.progress * 100) + '%'; }
                    }
                });
                // cards image counter-parallax
                $$('.h-card__img img', hp).forEach(img => {
                    gsap.fromTo(img, { scale: 1.25, xPercent: -6 }, { xPercent: 6, ease: 'none', scrollTrigger: { trigger: img, containerAnimation: tween, start: 'left right', end: 'right left', scrub: true } });
                });
            });
        }

        // Marquee — direction follows scroll
        const mTrack = $('.marquee__track');
        if (mTrack) {
            const loop = gsap.to(mTrack, { xPercent: -50, duration: 30, ease: 'none', repeat: -1 });
            ScrollTrigger.create({
                onUpdate: self => {
                    const v = self.getVelocity() / 300;
                    gsap.to(loop, { timeScale: (self.direction) * Math.max(1, Math.min(4, Math.abs(v))), duration: .3, overwrite: true });
                    gsap.to(loop, { timeScale: self.direction, duration: 1, delay: .3 });
                }
            });
        }
    } else {
        // Fallback: make everything visible
        $$('.hero__text.is-active .line__inner, .page-banner__title .line__inner').forEach(el => el.style.transform = 'none');
    }

    /* Counters */
    const counters = $$('[data-count]');
    const runCount = el => {
        const end = parseInt(el.dataset.count, 10) || 0;
        if (!hasGSAP || reduced) { el.textContent = end; return; }
        const o = { v: 0 };
        gsap.to(o, { v: end, duration: 2.2, ease: 'power3.out', onUpdate: () => { el.textContent = Math.round(o.v); } });
    };
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(entries => entries.forEach(en => { if (en.isIntersecting) { runCount(en.target); io.unobserve(en.target); } }), { threshold: .6 });
        counters.forEach(c => io.observe(c));
    } else counters.forEach(runCount);

    /* ------------------------------------------------------------
       Micro-interactions: magnetic, tilt, spotlight
    ------------------------------------------------------------ */
    if (hasGSAP && !isTouch && !reduced) {
        $$('.magnetic').forEach(el => {
            const strength = el.classList.contains('btn') ? .25 : .4;
            el.addEventListener('mousemove', e => {
                const r = el.getBoundingClientRect();
                gsap.to(el, { x: (e.clientX - r.left - r.width / 2) * strength, y: (e.clientY - r.top - r.height / 2) * strength, duration: .5, ease: 'power3.out' });
            });
            el.addEventListener('mouseleave', () => gsap.to(el, { x: 0, y: 0, duration: .8, ease: 'elastic.out(1, .4)' }));
        });
        $$('.tilt').forEach(el => {
            el.addEventListener('mousemove', e => {
                const r = el.getBoundingClientRect();
                const px = (e.clientX - r.left) / r.width - .5, py = (e.clientY - r.top) / r.height - .5;
                gsap.to(el, { rotateY: px * 10, rotateX: -py * 10, transformPerspective: 900, duration: .6, ease: 'power2.out' });
            });
            el.addEventListener('mouseleave', () => gsap.to(el, { rotateX: 0, rotateY: 0, duration: .8, ease: 'power3.out' }));
        });
    }
    $$('.spotlight').forEach(el => {
        el.addEventListener('mousemove', e => {
            const r = el.getBoundingClientRect();
            el.style.setProperty('--mx', (e.clientX - r.left) + 'px');
            el.style.setProperty('--my', (e.clientY - r.top) + 'px');
        });
    });

    /* ------------------------------------------------------------
       Testimonials
    ------------------------------------------------------------ */
    const tSlides = $$('.t-slide');
    if (tSlides.length) {
        const dots = $$('.t-dots span');
        let ti = 0, tTimer;
        const show = i => {
            ti = (i + tSlides.length) % tSlides.length;
            tSlides.forEach((s, k) => s.classList.toggle('is-active', k === ti));
            dots.forEach((d, k) => d.classList.toggle('is-active', k === ti));
            clearInterval(tTimer);
            tTimer = setInterval(() => show(ti + 1), 6000);
        };
        $('[data-t-next]')?.addEventListener('click', () => show(ti + 1));
        $('[data-t-prev]')?.addEventListener('click', () => show(ti - 1));
        dots.forEach((d, k) => d.addEventListener('click', () => show(k)));
        show(0);
    }

    /* ------------------------------------------------------------
       Project filters
    ------------------------------------------------------------ */
    const grid = $('#pGrid');
    if (grid) {
        const cards = $$('.p-card', grid);
        $$('.filter-btn').forEach(btn => btn.addEventListener('click', () => {
            $$('.filter-btn').forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');
            const f = btn.dataset.filter;
            const show = cards.filter(c => f === '*' || c.classList.contains(f));
            const hide = cards.filter(c => !show.includes(c));
            if (hasGSAP && !reduced) {
                gsap.to(cards, {
                    opacity: 0, scale: .94, y: 20, duration: .3, ease: 'power2.in', onComplete: () => {
                        hide.forEach(c => c.classList.add('is-hidden'));
                        show.forEach(c => c.classList.remove('is-hidden'));
                        gsap.fromTo(show, { opacity: 0, scale: .94, y: 30 }, { opacity: 1, scale: 1, y: 0, duration: .7, stagger: .06, ease: 'expo.out', clearProps: 'transform' });
                        ScrollTrigger.refresh();
                    }
                });
            } else {
                hide.forEach(c => c.classList.add('is-hidden'));
                show.forEach(c => c.classList.remove('is-hidden'));
            }
        }));
        if (hasGSAP && !reduced) gsap.from(cards, { opacity: 0, y: 60, duration: 1, stagger: .08, ease: 'expo.out', scrollTrigger: { trigger: grid, start: 'top 85%' } });
    }

    /* ------------------------------------------------------------
       Lightbox (project detail)
    ------------------------------------------------------------ */
    const lb = $('#lightbox');
    if (lb && Array.isArray(window.__gallery)) {
        const imgs = window.__gallery, lbImg = $('img', lb), count = $('.lightbox__count', lb);
        let li = 0;
        const open = i => {
            li = (i + imgs.length) % imgs.length;
            lbImg.src = imgs[li];
            count.textContent = (li + 1) + ' / ' + imgs.length;
            lb.classList.add('is-open');
            if (lenis) lenis.stop();
        };
        const close = () => { lb.classList.remove('is-open'); if (lenis) lenis.start(); };
        $$('[data-lightbox-index]').forEach(t => t.addEventListener('click', () => open(parseInt(t.dataset.lightboxIndex, 10))));
        $('.lightbox__close', lb).addEventListener('click', close);
        $('.lightbox__prev', lb).addEventListener('click', () => open(li - 1));
        $('.lightbox__next', lb).addEventListener('click', () => open(li + 1));
        lb.addEventListener('click', e => { if (e.target === lb) close(); });
        document.addEventListener('keydown', e => {
            if (!lb.classList.contains('is-open')) return;
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowRight') open(li + 1);
            if (e.key === 'ArrowLeft') open(li - 1);
        });
    }

    /* ------------------------------------------------------------
       Enquiry modal
    ------------------------------------------------------------ */
    const modal = $('#enquiryModal');
    function openModal(service) {
        if (!modal) return;
        if (service) {
            const sel = $('select[name="service"]', modal);
            if (sel) sel.value = service;
        }
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        if (lenis) lenis.stop();
        setTimeout(() => $('input[name="name"]', modal)?.focus(), 400);
        sessionStorage.setItem('aiyra-popup', '1');
    }
    function closeModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        if (lenis) lenis.start();
    }
    document.addEventListener('click', e => {
        const t = e.target.closest('[data-open-enquiry]');
        if (t) { e.preventDefault(); body.classList.remove('menu-open'); openModal(t.dataset.service); }
        if (e.target.closest('[data-close-modal]')) closeModal();
    });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    // Auto popup once per session after 25s
    if (modal && !sessionStorage.getItem('aiyra-popup')) {
        setTimeout(() => {
            if (!sessionStorage.getItem('aiyra-popup') && !$('.lightbox.is-open') && !body.classList.contains('menu-open')) openModal();
        }, 25000);
    }

    /* ------------------------------------------------------------
       Toast + AJAX enquiry forms
    ------------------------------------------------------------ */
    const toastEl = $('#toast');
    let toastTimer;
    function toast(msg, isError) {
        if (!toastEl) return alert(msg);
        toastEl.textContent = msg;
        toastEl.classList.toggle('is-error', !!isError);
        toastEl.classList.add('is-visible');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toastEl.classList.remove('is-visible'), 5000);
    }
    if (window.__flash) toast(window.__flash);

    $$('form.js-enquiry').forEach(form => {
        form.addEventListener('submit', async e => {
            e.preventDefault();
            const btn = $('button[type="submit"]', form);
            const errBox = $('.enq-form__error', form);
            $$('.field', form).forEach(f => f.classList.remove('has-error'));
            errBox.classList.remove('is-visible');

            // quick client validation
            const name = form.elements.name.value.trim();
            const phone = form.elements.phone.value.trim();
            const errors = [];
            if (!name) { errors.push('Please enter your name.'); form.elements.name.closest('.field').classList.add('has-error'); }
            if (!/^[0-9+\-\s()]{8,20}$/.test(phone)) { errors.push('Please enter a valid phone number.'); form.elements.phone.closest('.field').classList.add('has-error'); }
            if (errors.length) { errBox.textContent = errors.join(' '); errBox.classList.add('is-visible'); return; }

            btn.classList.add('is-loading');
            const icon = $('i', btn); const oldIcon = icon ? icon.className : '';
            if (icon) icon.className = 'bi bi-arrow-repeat';
            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form)
                });
                const data = await res.json().catch(() => ({}));
                if (res.ok) {
                    form.reset();
                    closeModal();
                    toast(data.message || 'Thank you! We will contact you soon.');
                } else if (res.status === 422 && data.errors) {
                    Object.keys(data.errors).forEach(k => form.elements[k]?.closest('.field')?.classList.add('has-error'));
                    errBox.textContent = Object.values(data.errors).flat().join(' ');
                    errBox.classList.add('is-visible');
                } else if (res.status === 419) {
                    toast('Session expired. Please refresh the page and try again.', true);
                } else if (res.status === 429) {
                    toast('Too many attempts. Please wait a minute and try again.', true);
                } else {
                    toast('Something went wrong. Please call us directly.', true);
                }
            } catch (err) {
                toast('Network error. Please check your connection.', true);
            } finally {
                btn.classList.remove('is-loading');
                if (icon) icon.className = oldIcon;
            }
        });
    });

    // Refresh ScrollTrigger once images & fonts are ready
    if (hasGSAP) {
        window.addEventListener('load', () => ScrollTrigger.refresh());
        if (document.fonts && document.fonts.ready) document.fonts.ready.then(() => ScrollTrigger.refresh());
    }
})();
