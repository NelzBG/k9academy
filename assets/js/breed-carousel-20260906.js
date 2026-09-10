(() => {
    'use strict';
    const reduced = matchMedia('(prefers-reduced-motion: reduce)');
    const bg = document.documentElement.lang === 'bg';
    document.querySelectorAll('[data-breed-carousel]').forEach(carousel => {
        const track = carousel.querySelector('.k9-breed-track');
        const cards = [...track.querySelectorAll('.k9-breed-card')];
        if (cards.length < 2) return;
        const toggle = carousel.querySelector('[data-breed-toggle]');
        const status = carousel.querySelector('[data-breed-status]');
        const copies = () => cards.map(card => {
            const copy = card.cloneNode(true);
            copy.setAttribute('aria-hidden','true');
            copy.dataset.breedCopy = '';
            copy.querySelectorAll('img').forEach(img => { img.alt = ''; img.loading = 'eager'; });
            return copy;
        });
        const before = copies();
        track.prepend(...before);
        track.append(...copies());
        track.classList.add('is-looping');
        let cycle = 0, step = 0, offset = 0, writtenScroll = 0;
        let frame = 0, lastTime = 0, animation = null, resumeTimer = 0, resumeAt = 0;
        let visible = false, hovered = false, touching = false, pointerHeld = false, paused = reduced.matches;
        const wrap = value => ((value % cycle) + cycle) % cycle;
        const updateStatus = () => {
            const index = Math.floor((offset + .5) / step) % cards.length;
            const text = String(index + 1).padStart(2,'0') + ' / ' + String(cards.length).padStart(2,'0');
            if (status.textContent !== text) status.textContent = text;
        };
        const place = value => {
            if (!cycle) return;
            offset = wrap(value);
            track.scrollLeft = cycle + offset;
            writtenScroll = track.scrollLeft;
            updateStatus();
        };
        const canPlay = () => visible && !paused && !hovered && !touching && !pointerHeld && !document.hidden
            && !track.matches(':focus-within') && !document.body.matches('.menu-open,.cookie-open')
            && performance.now() >= resumeAt;
        const tick = time => {
            frame = 0;
            if (animation) {
                const progress = Math.min(1,(time - animation.start) / 420);
                place(animation.from + animation.distance * (1 - Math.pow(1 - progress,3)));
                if (progress === 1) animation = null;
            } else if (canPlay()) {
                place(offset + Math.min((time - (lastTime || time)) / 1000,.06) * 32);
            }
            lastTime = time;
            if (animation || canPlay()) frame = requestAnimationFrame(tick);
        };
        const wake = () => {
            if (!frame && cycle && (animation || canPlay())) {
                lastTime = 0;
                frame = requestAnimationFrame(tick);
            }
        };
        const hold = () => {
            resumeAt = performance.now() + 2600;
            clearTimeout(resumeTimer);
            resumeTimer = setTimeout(wake,2650);
        };
        const measure = () => {
            const nextCycle = cards[0].getBoundingClientRect().left - before[0].getBoundingClientRect().left;
            if (Math.abs(nextCycle - cycle) < .1) return;
            const phase = cycle ? offset / cycle : 0;
            cycle = nextCycle;
            step = cycle / cards.length;
            animation = null;
            place(phase * cycle);
            wake();
        };
        const move = distance => {
            hold();
            if (reduced.matches) place(offset + distance);
            else animation = { from: offset, distance, start: performance.now() };
            wake();
        };
        carousel.querySelector('[data-breed-prev]').addEventListener('click',() => move(-step));
        carousel.querySelector('[data-breed-next]').addEventListener('click',() => move(step));
        track.addEventListener('keydown',event => {
            const key = event.key;
            if (!['ArrowLeft','ArrowRight','Home','End'].includes(key)) return;
            event.preventDefault();
            move(key === 'Home' ? -offset : key === 'End' ? step * (cards.length - 1) - offset : key === 'ArrowLeft' ? -step : step);
        });
        track.addEventListener('scroll',() => {
            // Preserve fractional animation progress; only adopt native touch/wheel movement.
            if (Math.abs(track.scrollLeft - writtenScroll) < .75) return;
            animation = null;
            hold();
            offset = wrap(track.scrollLeft - cycle);
            if (track.scrollLeft < cycle || track.scrollLeft >= cycle * 2) place(offset);
            else { writtenScroll = track.scrollLeft; updateStatus(); }
        },{passive:true});
        track.addEventListener('pointerdown',event => {
            if (event.pointerType !== 'touch') pointerHeld = true;
            animation = null;
            hold();
        },{passive:true});
        const release = () => { if (!pointerHeld) return; pointerHeld = false; hold(); };
        // Native panning cancels pointer events; touch events keep autoplay paused until release.
        track.addEventListener('touchstart',() => { touching = true; animation = null; hold(); },{passive:true});
        const releaseTouch = event => {
            if (!touching) return;
            touching = event.touches.length > 0;
            hold();
        };
        window.addEventListener('touchend',releaseTouch,{passive:true});
        window.addEventListener('touchcancel',releaseTouch,{passive:true});
        window.addEventListener('pointerup',release,{passive:true});
        window.addEventListener('pointercancel',release,{passive:true});
        track.addEventListener('wheel',hold,{passive:true});
        track.addEventListener('pointerenter',event => { if (event.pointerType === 'mouse') hovered = true; });
        track.addEventListener('pointerleave',() => { hovered = false; wake(); });
        track.addEventListener('focusout',() => requestAnimationFrame(wake));
        const updateToggle = () => {
            toggle.hidden = false;
            toggle.textContent = bg ? (paused ? 'Пусни движението' : 'Спри движението') : (paused ? 'Play movement' : 'Pause movement');
            toggle.setAttribute('aria-pressed',String(paused));
        };
        toggle.addEventListener('click',() => { paused = !paused; updateToggle(); wake(); });
        reduced.addEventListener('change',() => { if (reduced.matches) paused = true; animation = null; updateToggle(); wake(); });
        document.addEventListener('visibilitychange',wake);
        new MutationObserver(wake).observe(document.body,{attributes:true,attributeFilter:['class']});
        if ('ResizeObserver' in window) new ResizeObserver(measure).observe(track);
        else window.addEventListener('resize',measure,{passive:true});
        if ('IntersectionObserver' in window) new IntersectionObserver(entries => { visible = entries[0].isIntersecting; wake(); },{threshold:.1}).observe(track);
        else visible = true;
        updateToggle();
        measure();
    });
})();
