/* AIYRA — Admin panel interactions */
(function () {
    'use strict';
    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
    const admin = $('#admin');
    const csrf = $('meta[name="csrf-token"]')?.content;

    /* Sidebar collapse (desktop) / slide-in (mobile) */
    const toggle = $('#sidebarToggle');
    if (admin && localStorage.getItem('aiyra-sb') === '1' && window.innerWidth > 991) admin.classList.add('is-collapsed');
    toggle?.addEventListener('click', () => {
        if (window.innerWidth <= 991) {
            admin.classList.toggle('is-mobile-open');
        } else {
            admin.classList.toggle('is-collapsed');
            localStorage.setItem('aiyra-sb', admin.classList.contains('is-collapsed') ? '1' : '0');
        }
    });
    $('#sidebarBackdrop')?.addEventListener('click', () => admin.classList.remove('is-mobile-open'));

    /* User dropdown */
    const um = $('#userMenu');
    um?.querySelector('.user-menu__btn').addEventListener('click', e => { e.stopPropagation(); um.classList.toggle('is-open'); });
    document.addEventListener('click', () => um?.classList.remove('is-open'));

    /* Alerts */
    $$('.alert__close').forEach(b => b.addEventListener('click', () => hideAlert(b.closest('.alert'))));
    $$('[data-autohide]').forEach(a => setTimeout(() => hideAlert(a), 5000));
    function hideAlert(a) { if (!a) return; a.classList.add('is-hiding'); setTimeout(() => a.remove(), 400); }

    /* Animated counters */
    $$('[data-count]').forEach(el => {
        const end = parseInt(el.dataset.count, 10) || 0;
        if (!end) return;
        const start = performance.now(), dur = 1200;
        const tick = now => {
            const p = Math.min(1, (now - start) / dur);
            el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(tick);
        };
        el.textContent = '0';
        requestAnimationFrame(tick);
    });

    /* Confirm dialog */
    const dlg = $('#confirm');
    let pending = null;
    function ask(text, cb) {
        if (!dlg) { if (window.confirm(text)) cb(); return; }
        $('#confirmText').textContent = text || 'This action cannot be undone.';
        pending = cb;
        dlg.classList.add('is-open');
    }
    $('[data-confirm-no]', dlg || document)?.addEventListener('click', () => { dlg.classList.remove('is-open'); pending = null; });
    $('[data-confirm-yes]', dlg || document)?.addEventListener('click', () => { dlg.classList.remove('is-open'); if (pending) pending(); pending = null; });
    dlg?.addEventListener('click', e => { if (e.target === dlg) dlg.classList.remove('is-open'); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') dlg?.classList.remove('is-open'); });

    $$('form[data-confirm]').forEach(f => f.addEventListener('submit', e => {
        if (f.dataset.confirmed) return;
        e.preventDefault();
        ask(f.dataset.confirm, () => { f.dataset.confirmed = '1'; f.submit(); });
    }));
    $$('[data-confirm-btn]').forEach(b => b.addEventListener('click', e => {
        e.preventDefault();
        ask(b.dataset.confirmBtn, () => {
            const f = b.closest('form');
            const h = document.createElement('input');
            h.type = 'hidden'; h.name = b.name; h.value = b.value;
            f.appendChild(h); f.submit();
        });
    }));

    /* Loading state on submit */
    $$('form').forEach(f => f.addEventListener('submit', () => {
        if (f.dataset.confirm && !f.dataset.confirmed) return;
        const b = $('[data-loading-text]', f) || $('button[type=submit]', f);
        if (b && !b.closest('[data-confirm]')) b.classList.add('is-loading');
    }));

    /* Image preview for single pickers */
    function bindPreview(scope) {
        $$('input[type=file][data-preview]', scope).forEach(inp => {
            if (inp.dataset.bound) return;
            inp.dataset.bound = '1';
            inp.addEventListener('change', () => {
                const file = inp.files && inp.files[0];
                const wrap = inp.closest('.image-picker');
                if (!file || !wrap) return;
                const img = $('img', wrap);
                img.src = URL.createObjectURL(file);
                wrap.classList.add('has-image');
            });
        });
    }
    bindPreview(document);

    /* Dropzone multi-image preview */
    $$('[data-dropzone]').forEach(dz => {
        const input = $('input[type=file]', dz);
        const preview = dz.parentElement.querySelector('[data-dropzone-preview]');
        ['dragenter', 'dragover'].forEach(ev => dz.addEventListener(ev, e => { e.preventDefault(); dz.classList.add('is-over'); }));
        ['dragleave', 'drop'].forEach(ev => dz.addEventListener(ev, () => dz.classList.remove('is-over')));
        dz.addEventListener('drop', e => {
            e.preventDefault();
            if (e.dataTransfer?.files?.length) { input.files = e.dataTransfer.files; input.dispatchEvent(new Event('change')); }
        });
        input.addEventListener('change', () => {
            preview.innerHTML = '';
            Array.from(input.files).forEach((f, i) => {
                const d = document.createElement('div');
                d.className = 'gallery-item is-new';
                d.style.animationDelay = (i * 0.04) + 's';
                d.innerHTML = `<img src="${URL.createObjectURL(f)}" alt="">`;
                preview.appendChild(d);
            });
            const strong = $('strong', dz);
            strong.textContent = input.files.length ? input.files.length + ' image(s) selected' : 'Drop images here or browse';
        });
    });

    /* Delete existing gallery image (AJAX) */
    $$('[data-delete-image]').forEach(btn => btn.addEventListener('click', () => {
        ask('Remove this image from the gallery?', async () => {
            const item = btn.closest('.gallery-item');
            try {
                const r = await fetch(btn.dataset.deleteImage, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
                if (r.ok) { item.classList.add('is-removing'); setTimeout(() => item.remove(), 400); }
                else alert('Could not delete the image.');
            } catch (e) { alert('Network error.'); }
        });
    }));

    /* Toggle switches (AJAX) on project list */
    $$('input[data-toggle-url]').forEach(inp => inp.addEventListener('change', async () => {
        try {
            const r = await fetch(inp.dataset.toggleUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ field: inp.dataset.field })
            });
            const d = await r.json();
            if (!r.ok) throw 0;
            inp.checked = !!d.value;
        } catch (e) { inp.checked = !inp.checked; alert('Could not update. Please try again.'); }
    }));

    /* Repeaters */
    function renumber(rep) {
        $$('[data-row]', rep).forEach((row, i) => { const n = $('.repeater__num', row); if (n) n.textContent = String(i + 1).padStart(2, '0'); });
        const add = $('[data-add-row]', rep);
        if (add && add.dataset.max) add.disabled = $$('[data-row]', rep).length >= parseInt(add.dataset.max, 10);
        if (add) add.style.opacity = add.disabled ? .5 : 1;
    }
    $$('[data-repeater]').forEach(rep => {
        const items = $('.repeater__items', rep);
        const tpl = $('template', rep);
        renumber(rep);
        $('[data-add-row]', rep)?.addEventListener('click', () => {
            const idx = 'n' + Date.now();
            const html = tpl.innerHTML.replace(/__I__/g, idx);
            const wrap = document.createElement('div');
            wrap.innerHTML = html.trim();
            const row = wrap.firstElementChild;
            items.appendChild(row);
            bindPreview(row);
            bindIconSelect(row);
            renumber(rep);
            $('input:not([type=hidden]):not([type=file]), textarea', row)?.focus();
        });
        rep.addEventListener('click', e => {
            const del = e.target.closest('[data-del-row]');
            if (!del) return;
            const row = del.closest('[data-row]');
            row.classList.add('is-removing');
            setTimeout(() => { row.remove(); renumber(rep); }, 350);
        });
    });

    /* Icon select preview */
    function bindIconSelect(scope) {
        $$('[data-icon-select]', scope).forEach(sel => sel.addEventListener('change', () => {
            const i = sel.closest('.icon-select').querySelector('.icon-select__preview i');
            i.className = 'bi ' + sel.value;
        }));
    }
    bindIconSelect(document);

    /* Character counter */
    $$('textarea[data-counter]').forEach(t => {
        const c = document.createElement('small');
        c.className = 'char-count';
        t.after(c);
        const upd = () => { c.textContent = t.value.length + ' / ' + (t.maxLength > 0 ? t.maxLength : '∞'); };
        t.addEventListener('input', upd); upd();
    });

    /* Bulk select (enquiries) */
    const checkAll = $('#checkAll');
    const bulkBar = $('#bulkBar');
    function updBulk() {
        const n = $$('.row-check:checked').length;
        if (bulkBar) { bulkBar.classList.toggle('is-visible', n > 0); $('#bulkCount').textContent = n; }
    }
    checkAll?.addEventListener('change', () => { $$('.row-check').forEach(c => c.checked = checkAll.checked); updBulk(); });
    $$('.row-check').forEach(c => c.addEventListener('change', updBulk));

    /* Settings section nav — scroll spy */
    const nav = $('#settingsNav');
    if (nav) {
        const links = $$('a', nav);
        links.forEach(a => a.addEventListener('click', e => {
            e.preventDefault();
            const t = $(a.getAttribute('href'));
            if (t) window.scrollTo({ top: t.getBoundingClientRect().top + window.scrollY - 150, behavior: 'smooth' });
        }));
        const io = new IntersectionObserver(entries => entries.forEach(en => {
            if (en.isIntersecting) links.forEach(l => l.classList.toggle('is-active', l.getAttribute('href') === '#' + en.target.id));
        }), { rootMargin: '-40% 0px -55% 0px' });
        links.forEach(l => { const t = $(l.getAttribute('href')); if (t) io.observe(t); });
    }
})();
