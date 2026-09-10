(() => {
    'use strict';

    const body = document.body;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const revealItems = [...document.querySelectorAll('.reveal:not(.is-visible)')];
    if (reducedMotion || !('IntersectionObserver' in window)) {
        revealItems.forEach((item) => item.classList.add('is-visible'));
    } else {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.13, rootMargin: '0px 0px -8% 0px' });
        revealItems.forEach((item) => observer.observe(item));
    }

    const gallery = document.querySelector('[data-gallery]');
    if (gallery) {
        const cards = [...gallery.querySelectorAll('[data-gallery-card]')];
        const previous = document.querySelector('[data-gallery-prev]');
        const next = document.querySelector('[data-gallery-next]');
        const progress = document.querySelector('[data-gallery-progress]');
        const step = () => (cards[0]?.getBoundingClientRect().width || gallery.clientWidth) + 16;
        previous?.addEventListener('click', () => gallery.scrollBy({ left: -step(), behavior: reducedMotion ? 'auto' : 'smooth' }));
        next?.addEventListener('click', () => gallery.scrollBy({ left: step(), behavior: reducedMotion ? 'auto' : 'smooth' }));
        const updateProgress = () => {
            if (!progress) return;
            const max = gallery.scrollWidth - gallery.clientWidth;
            const ratio = max > 0 ? gallery.scrollLeft / max : 0;
            progress.style.transform = `scaleX(${Math.max(.25, .25 + ratio * .75)})`;
        };
        gallery.addEventListener('scroll', updateProgress, { passive: true });
        updateProgress();
    }

    const cookieWall = document.querySelector('[data-cookie-wall]');
    const cookiePanel = document.querySelector('[data-cookie-panel]');
    const openCookieWall = () => {
        if (!cookieWall) return;
        cookieWall.hidden = false;
        body.classList.add('cookie-open');
        window.setTimeout(() => cookiePanel?.focus(), 20);
    };
    const closeCookieWall = (choice) => {
        if (!cookieWall) return;
        try { localStorage.setItem('k9-cookie-choice', choice); } catch {}
        cookieWall.hidden = true;
        body.classList.remove('cookie-open');
    };
    let cookieChoice = null;
    try { cookieChoice = localStorage.getItem('k9-cookie-choice'); } catch {}
    if (cookieWall && !cookieChoice) openCookieWall();
    document.querySelectorAll('[data-cookie-choice]').forEach((button) => button.addEventListener('click', () => closeCookieWall(button.dataset.cookieChoice || 'essential')));
    document.querySelectorAll('[data-cookie-settings]').forEach((button) => button.addEventListener('click', openCookieWall));

    document.querySelectorAll('[data-contact-form]').forEach((form) => {
        const bg = document.documentElement.lang === 'bg';
        const status = form.querySelector('[data-form-status]');
        const submit = form.querySelector('[type="submit"]');
        let sending = false;
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (sending) return;
            if (!form.checkValidity()) {
                form.reportValidity();
                if (status) status.textContent = bg ? 'Моля, попълнете задължителните полета.' : 'Please complete the required fields.';
                return;
            }
            const endpoint = form.dataset.endpoint;
            if (!endpoint) {
                if (status) status.textContent = bg
                    ? 'Онлайн изпращането все още не е активирано. Обадете се на +359 892 360 550.'
                    : 'Online delivery is not enabled yet. Please call +359 892 360 550.';
                return;
            }
            sending = true;
            submit.disabled = true;
            form.setAttribute('aria-busy', 'true');
            if (status) status.textContent = bg ? 'Изпращаме запитването…' : 'Sending your enquiry…';
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 15000);
            try {
                const response = await fetch(endpoint, {
                    method: 'POST', credentials: 'omit',
                    headers: { Accept: 'application/json' },
                    body: new URLSearchParams(new FormData(form)),
                    signal: controller.signal
                });
                const result = await response.json();
                if (!response.ok || result.ok !== true) throw new Error('Delivery was not accepted');
                form.reset();
                if (status) status.textContent = bg
                    ? 'Благодарим! Запитването е изпратено до K9 Academy. Ще се свържем с вас.'
                    : 'Thank you! Your enquiry has been sent to K9 Academy. We will contact you.';
            } catch {
                if (status) status.textContent = bg
                    ? 'Не успяхме да потвърдим изпращането. Данните ви са запазени във формата. Опитайте отново или се обадете на +359 892 360 550.'
                    : 'We could not confirm delivery. Your details are still in the form. Please retry or call +359 892 360 550.';
            } finally {
                clearTimeout(timeout);
                sending = false;
                submit.disabled = false;
                form.removeAttribute('aria-busy');
            }
        });
    });
})();
