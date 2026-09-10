// Original floating contact controls, isolated from shared navigation.
(() => {
    'use strict';
    const toast = document.querySelector('[data-toast]');
    let toastTimer = 0;

    const showToast = (message) => {
        if (!toast) return;
        window.clearTimeout(toastTimer);
        toast.textContent = message;
        toast.classList.add('is-visible');
        toastTimer = window.setTimeout(() => toast.classList.remove('is-visible'), 4200);
    };

    const pendingMessage = document.documentElement.lang === 'bg'
        ? 'Този канал ще бъде активиран, когато бъде добавен номер.'
        : 'This channel will be activated when a number is added.';

    const openChannel = (button) => {
        const url = button.dataset.channelUrl;
        if (!url) { showToast(pendingMessage); return; }
        if (url.startsWith('https://wa.me/')) window.open(url, '_blank', 'noopener,noreferrer');
        else if (url.startsWith('viber://chat?number=')) window.location.assign(url);
    };

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
            const left = itemState.side === 'left' ? edgeMargin : Math.max(edgeMargin, floatingLayer.clientWidth - width - edgeMargin);
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
                openChannel(button);
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
            const left = clamp(activeDrag.originX + dx, edgeMargin, Math.max(edgeMargin, floatingLayer.clientWidth - item.offsetWidth - edgeMargin));
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
                itemState.side = releaseBox.left + (releaseBox.width / 2) < floatingLayer.clientWidth / 2 ? 'left' : 'right';
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
        document.querySelectorAll('[data-channel]').forEach((button) => button.addEventListener('click', () => openChannel(button)));
    }

})();
