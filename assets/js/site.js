(() => {
    'use strict';

    const root = document.documentElement;
    const body = document.body;
    const menuButton = document.querySelector('[data-menu-toggle]');
    const mobileNav = document.querySelector('[data-mobile-nav]');
    const themeButtons = [...document.querySelectorAll('[data-theme-toggle]')];
    const toast = document.querySelector('[data-toast]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let toastTimer = 0;

    const showToast = (message) => {
        if (!toast) return;
        window.clearTimeout(toastTimer);
        toast.textContent = message;
        toast.classList.add('is-visible');
        toastTimer = window.setTimeout(() => toast.classList.remove('is-visible'), 4200);
    };

    const closeMenu = () => {
        if (!menuButton || !mobileNav) return;
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.setAttribute('aria-label', menuButton.dataset.openLabel || menuButton.getAttribute('aria-label'));
        mobileNav.hidden = true;
        body.classList.remove('menu-open');
    };

    if (menuButton && mobileNav) {
        menuButton.dataset.openLabel = menuButton.getAttribute('aria-label') || '';
        menuButton.addEventListener('click', () => {
            const open = menuButton.getAttribute('aria-expanded') !== 'true';
            menuButton.setAttribute('aria-expanded', String(open));
            menuButton.setAttribute('aria-label', open ? (menuButton.dataset.closeLabel || menuButton.dataset.openLabel) : menuButton.dataset.openLabel);
            mobileNav.hidden = !open;
            body.classList.toggle('menu-open', open);
        });
        mobileNav.addEventListener('click', (event) => {
            if (event.target.closest('a')) closeMenu();
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) closeMenu();
        });
    }

    const updateThemeButton = () => {
        if (!themeButtons.length) return;
        const dark = root.dataset.theme === 'dark';
        themeButtons.forEach((button) => {
            const icon = button.querySelector('[data-theme-icon]');
            if (icon) icon.textContent = dark ? '☀' : '◐';
            button.setAttribute('aria-pressed', String(dark));
        });
    };

    if (themeButtons.length) {
        updateThemeButton();
        themeButtons.forEach((button) => button.addEventListener('click', () => {
            const next = root.dataset.theme === 'dark' ? 'light' : 'dark';
            root.dataset.theme = next;
            localStorage.setItem('k9-theme', next);
            updateThemeButton();
        }));
    }

    document.querySelectorAll('[data-language-link]').forEach((link) => {
        link.addEventListener('click', () => localStorage.setItem('k9-language', link.dataset.languageLink || 'bg'));
    });

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
        localStorage.setItem('k9-cookie-choice', choice);
        cookieWall.hidden = true;
        body.classList.remove('cookie-open');
    };
    if (cookieWall && !localStorage.getItem('k9-cookie-choice')) openCookieWall();
    document.querySelectorAll('[data-cookie-choice]').forEach((button) => button.addEventListener('click', () => closeCookieWall(button.dataset.cookieChoice || 'essential')));
    document.querySelectorAll('[data-cookie-settings]').forEach((button) => button.addEventListener('click', openCookieWall));

    const pendingMessage = document.documentElement.lang === 'bg'
        ? 'Този канал ще бъде активиран, когато бъде добавен номер.'
        : 'This channel will be activated when a number is added.';

    const floatingLayer = document.querySelector('[data-floating-layer]');
    const floatingItems = [...document.querySelectorAll('[data-floating-cta]')];
    const removeTarget = document.querySelector('[data-floating-remove]');
    const restoreButton = document.querySelector('[data-floating-restore]');
    const floatingStatus = document.querySelector('[data-floating-status]');

    if (floatingLayer && floatingItems.length) {
        const storageKey = 'k9-floating-ctas-v3';
        const edgeMargin = 12;
        const defaults = {
            whatsapp: { side: 'right', yRatio: .91, hidden: false },
            viber: { side: 'right', yRatio: 1, hidden: false },
        };
        const clamp = (value, minimum, maximum) => Math.min(Math.max(value, minimum), maximum);
        const getItemName = (item) => item.dataset.floatingCta || '';
        const getButton = (item) => item.querySelector('[data-channel]');

        const readState = () => {
            let saved = {};
            try {
                const parsed = JSON.parse(localStorage.getItem(storageKey) || '{}');
                if (parsed && typeof parsed === 'object') saved = parsed;
            } catch (_) {
                saved = {};
            }

            return Object.fromEntries(Object.entries(defaults).map(([name, fallback]) => {
                const candidate = saved[name] && typeof saved[name] === 'object' ? saved[name] : {};
                return [name, {
                    side: candidate.side === 'left' ? 'left' : fallback.side,
                    yRatio: Number.isFinite(candidate.yRatio) ? clamp(candidate.yRatio, 0, 1) : fallback.yRatio,
                    hidden: candidate.hidden === true,
                }];
            }));
        };

        const state = readState();
        const saveState = () => {
            try {
                localStorage.setItem(storageKey, JSON.stringify(state));
                localStorage.removeItem('k9-dock-position');
            } catch (_) {
                // Storage can be unavailable in privacy-restricted contexts.
            }
        };

        const availableTopRange = (item) => Math.max(0, window.innerHeight - item.offsetHeight - (edgeMargin * 2));
        const setItemPosition = (item) => {
            const itemState = state[getItemName(item)];
            if (!itemState || itemState.hidden) return;
            const width = item.offsetWidth;
            const left = itemState.side === 'left' ? edgeMargin : Math.max(edgeMargin, window.innerWidth - width - edgeMargin);
            const top = edgeMargin + (availableTopRange(item) * clamp(itemState.yRatio, 0, 1));
            item.dataset.side = itemState.side;
            item.style.left = `${Math.round(left)}px`;
            item.style.top = `${Math.round(top)}px`;
        };

        const updateRestoreButton = () => {
            if (!restoreButton) return;
            restoreButton.hidden = !Object.values(state).some((itemState) => itemState.hidden);
        };

        const applyItemState = (item) => {
            const itemState = state[getItemName(item)];
            const button = getButton(item);
            if (!itemState || !button) return;
            item.classList.toggle('is-hidden', itemState.hidden);
            item.setAttribute('aria-hidden', String(itemState.hidden));
            button.tabIndex = itemState.hidden ? -1 : 0;
            if (!itemState.hidden) setItemPosition(item);
        };

        const applyAllStates = () => {
            floatingItems.forEach(applyItemState);
            updateRestoreButton();
        };

        const announce = (message) => {
            if (!floatingStatus) return;
            floatingStatus.textContent = '';
            window.requestAnimationFrame(() => { floatingStatus.textContent = message; });
        };

        const hideItem = (item) => {
            const itemState = state[getItemName(item)];
            if (!itemState) return;
            itemState.hidden = true;
            applyItemState(item);
            updateRestoreButton();
            saveState();
            announce(floatingStatus?.dataset.hiddenMessage || 'Contact button hidden.');
        };

        const restoreAll = () => {
            const hiddenItems = floatingItems.filter((item) => state[getItemName(item)]?.hidden);
            if (!hiddenItems.length) return;
            hiddenItems.forEach((item) => { state[getItemName(item)].hidden = false; });
            applyAllStates();
            saveState();
            announce(floatingStatus?.dataset.restoredMessage || 'Contact buttons restored.');
            window.setTimeout(() => getButton(hiddenItems[0])?.focus(), 30);
        };

        let activeDrag = null;
        const setRemoveTarget = (visible, armed = false) => {
            if (!removeTarget) return;
            removeTarget.classList.toggle('is-visible', visible);
            removeTarget.classList.toggle('is-armed', visible && armed);
            removeTarget.setAttribute('aria-hidden', String(!visible));
        };
        const isInsideDropTarget = (clientX, clientY, item) => {
            if (!removeTarget) return false;
            const targetBox = removeTarget.getBoundingClientRect();
            const itemBox = item.getBoundingClientRect();
            const padding = 24;
            const contains = (x, y) => x >= targetBox.left - padding && x <= targetBox.right + padding && y >= targetBox.top - padding && y <= targetBox.bottom + padding;
            return contains(clientX, clientY) || contains(itemBox.left + (itemBox.width / 2), itemBox.top + (itemBox.height / 2));
        };

        floatingItems.forEach((item) => {
            const button = getButton(item);
            if (!button) return;
            item.dataset.dragged = 'false';

            button.addEventListener('click', (event) => {
                if (item.dataset.dragged === 'true') {
                    event.preventDefault();
                    return;
                }
                showToast(pendingMessage);
            });

            button.addEventListener('keydown', (event) => {
                const itemState = state[getItemName(item)];
                if (!itemState) return;
                if (event.key === 'Delete' || event.key === 'Backspace') {
                    event.preventDefault();
                    hideItem(item);
                    restoreButton?.focus();
                    return;
                }
                if (!['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(event.key)) return;
                event.preventDefault();
                if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
                    itemState.side = event.key === 'ArrowLeft' ? 'left' : 'right';
                } else {
                    const currentTop = item.getBoundingClientRect().top;
                    const nextTop = currentTop + (event.key === 'ArrowUp' ? -24 : 24);
                    const available = availableTopRange(item);
                    itemState.yRatio = available > 0 ? clamp((nextTop - edgeMargin) / available, 0, 1) : 0;
                }
                setItemPosition(item);
                saveState();
            });

            if (!('PointerEvent' in window)) return;
            item.addEventListener('pointerdown', (event) => {
                if (event.button !== 0 || activeDrag) return;
                item.classList.add('is-dragging');
                const box = item.getBoundingClientRect();
                activeDrag = {
                    pointerId: event.pointerId,
                    item,
                    startX: event.clientX,
                    startY: event.clientY,
                    originX: box.left,
                    originY: box.top,
                    moved: false,
                    armed: false,
                };
                item.dataset.dragged = 'false';
            });
        });

        const moveFloatingItem = (event) => {
            if (!activeDrag || event.pointerId !== activeDrag.pointerId) return;
            const dx = event.clientX - activeDrag.startX;
            const dy = event.clientY - activeDrag.startY;
            if (!activeDrag.moved && Math.hypot(dx, dy) > 6) {
                activeDrag.moved = true;
                activeDrag.item.dataset.dragged = 'true';
                setRemoveTarget(true);
            }
            if (!activeDrag.moved) return;
            const item = activeDrag.item;
            const left = clamp(activeDrag.originX + dx, edgeMargin, Math.max(edgeMargin, window.innerWidth - item.offsetWidth - edgeMargin));
            const top = clamp(activeDrag.originY + dy, edgeMargin, Math.max(edgeMargin, window.innerHeight - item.offsetHeight - edgeMargin));
            item.style.left = `${Math.round(left)}px`;
            item.style.top = `${Math.round(top)}px`;
            activeDrag.armed = isInsideDropTarget(event.clientX, event.clientY, item);
            setRemoveTarget(true, activeDrag.armed);
            event.preventDefault();
        };

        const finishFloatingDrag = (event) => {
            if (!activeDrag || event.pointerId !== activeDrag.pointerId) return;
            const { item, moved, armed } = activeDrag;
            const releaseBox = item.getBoundingClientRect();
            item.classList.remove('is-dragging');
            setRemoveTarget(false);
            if (moved && armed) {
                hideItem(item);
            } else if (moved) {
                const itemState = state[getItemName(item)];
                itemState.side = releaseBox.left + (releaseBox.width / 2) < window.innerWidth / 2 ? 'left' : 'right';
                const available = availableTopRange(item);
                itemState.yRatio = available > 0 ? clamp((releaseBox.top - edgeMargin) / available, 0, 1) : 0;
                setItemPosition(item);
                saveState();
            }
            activeDrag = null;
            if (moved) window.setTimeout(() => { item.dataset.dragged = 'false'; }, 160);
        };

        window.addEventListener('pointermove', moveFloatingItem, { passive: false });
        window.addEventListener('pointerup', finishFloatingDrag);
        window.addEventListener('pointercancel', finishFloatingDrag);
        restoreButton?.addEventListener('click', restoreAll);
        let resizeFrame = 0;
        const reflowFloatingItems = () => {
            window.cancelAnimationFrame(resizeFrame);
            resizeFrame = window.requestAnimationFrame(applyAllStates);
        };
        window.addEventListener('resize', reflowFloatingItems, { passive: true });
        window.addEventListener('orientationchange', reflowFloatingItems, { passive: true });
        applyAllStates();
        saveState();
    } else {
        document.querySelectorAll('[data-channel]').forEach((button) => button.addEventListener('click', () => showToast(pendingMessage)));
    }

    document.querySelectorAll('[data-contact-form]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const status = form.querySelector('[data-form-status]');
            if (!form.checkValidity()) {
                form.reportValidity();
                if (status) status.textContent = document.documentElement.lang === 'bg' ? 'Моля, попълнете задължителните полета.' : 'Please complete the required fields.';
                return;
            }
            if (status) status.textContent = document.documentElement.lang === 'bg'
                ? 'Демо запитването е валидирано. Добавете получател, за да активирате изпращането.'
                : 'The demo enquiry is validated. Add a recipient to activate delivery.';
            form.reset();
        });
    });
})();
