(() => {
    'use strict';
    const root = document.documentElement;
    const body = document.body;
    const header = document.querySelector('[data-site-header]');
    const menu = document.querySelector('[data-mobile-nav]');
    const menuButton = document.querySelector('[data-menu-toggle]');
    const desktop = matchMedia('(min-width: 64rem)');
    const reduced = matchMedia('(prefers-reduced-motion: reduce)');
    const bg = root.lang === 'bg';
    const storage = {
        get(key) { try { return localStorage.getItem(key); } catch { return null; } },
        set(key,value) { try { localStorage.setItem(key,value); } catch { /* Private browsing can disable storage. */ } }
    };
    let menuOpen = false;
    let menuScroll = null;
    const inertBefore = new Map();
    const setMenu = (open, returnFocus = false) => {
        if (!menu || !menuButton) return;
        const nextOpen = open && !desktop.matches;
        if (nextOpen === menuOpen) return;
        if (nextOpen) {
            menuScroll = { x: window.scrollX, y: window.scrollY };
            root.style.setProperty('--k9-menu-scroll-y', -menuScroll.y + 'px');
            root.classList.add('k9-menu-open');
        }
        menuOpen = nextOpen;
        menu.hidden = !menuOpen;
        menuButton.setAttribute('aria-expanded', String(menuOpen));
        menuButton.setAttribute('aria-label', menuOpen ? menuButton.dataset.closeLabel : menuButton.dataset.openLabel);
        body.classList.toggle('menu-open',menuOpen);
        if (menuOpen) {
            menu.scrollTop = 0;
            [...body.children].filter(el => el !== header && !['SCRIPT','LINK','STYLE'].includes(el.tagName) && !el.classList.contains('skip-link')).forEach(el => {
                if (!inertBefore.has(el)) inertBefore.set(el,el.inert);
                el.inert = true;
            });
            requestAnimationFrame(() => menu.querySelector('a')?.focus({preventScroll:true}));
        } else {
            inertBefore.forEach((wasInert,el) => {el.inert = wasInert;});
            inertBefore.clear();
            root.classList.remove('k9-menu-open');
            root.style.removeProperty('--k9-menu-scroll-y');
            if (menuScroll) {
                window.scrollTo({ left: menuScroll.x, top: menuScroll.y, behavior: 'instant' });
                menuScroll = null;
            }
            if (returnFocus) menuButton.focus({preventScroll:true});
        }
    };
    menuButton?.addEventListener('click',() => setMenu(!menuOpen,true));
    menu?.addEventListener('click',event => {if(event.target.closest('a'))setMenu(false);});
    desktop.addEventListener('change',() => {if(desktop.matches)setMenu(false);});
    document.addEventListener('keydown',event => {
        if (!menuOpen) return;
        if (event.key === 'Escape') {event.preventDefault();setMenu(false,true);}
        if (event.key !== 'Tab') return;
        const items = [...header.querySelectorAll('a[href],button:not(:disabled)')].filter(el => el.getClientRects().length && !el.closest('[hidden]'));
        const first = items[0], last = items.at(-1);
        if(event.shiftKey && document.activeElement === first){event.preventDefault();last?.focus();}
        else if(!event.shiftKey && document.activeElement === last){event.preventDefault();first?.focus();}
    });
    const measureHeader = () => {if(header)root.style.setProperty('--k9-header',header.getBoundingClientRect().bottom+'px');};
    if (header && 'ResizeObserver' in window) new ResizeObserver(measureHeader).observe(header);
    measureHeader();

    const themeButtons = [...document.querySelectorAll('[data-theme-toggle]')];
    const systemTheme = matchMedia('(prefers-color-scheme: dark)');
    const updateTheme = () => {
        const dark = root.dataset.theme === 'dark';
        themeButtons.forEach(button => {
            button.setAttribute('aria-pressed',String(dark));
            button.setAttribute('aria-label',bg ? (dark ? 'Включи светла тема' : 'Включи тъмна тема') : (dark ? 'Switch to light theme' : 'Switch to dark theme'));
        });
    };
    themeButtons.forEach(button => button.addEventListener('click',() => {
        const next = root.dataset.theme === 'dark' ? 'light' : 'dark';
        root.dataset.theme = next;
        storage.set('k9-theme',next);
        updateTheme();
    }));
    systemTheme.addEventListener('change',event => {if(!storage.get('k9-theme')){root.dataset.theme=event.matches?'dark':'light';updateTheme();}});
    updateTheme();
    document.querySelectorAll('[data-language-link]').forEach(link => link.addEventListener('click',() => storage.set('k9-language',link.dataset.languageLink)));

    // Keep the bottom actions clear of the software keyboard on small screens.
    const checkKeyboard = () => {
        const editing = ['INPUT','TEXTAREA','SELECT'].includes(document.activeElement?.tagName);
        const covered = window.visualViewport && innerHeight - visualViewport.height > 120;
        body.classList.toggle('k9-keyboard-open',editing && Boolean(covered));
    };
    window.visualViewport?.addEventListener('resize',checkKeyboard);
    document.addEventListener('focusin',checkKeyboard);
    document.addEventListener('focusout',() => requestAnimationFrame(checkKeyboard));

    const signal = document.querySelector('.k9-signal');
    const effectsButton = document.querySelector('[data-effects-toggle]');
    let effectsPaused = false;
    let signalVisible = true;
    const effects = () => {
        signal?.querySelectorAll('.k9-signal-band').forEach(el => {el.style.animationPlayState=effectsPaused||!signalVisible||document.hidden?'paused':'running';});
        if(effectsButton){
            effectsButton.hidden = reduced.matches;
            effectsButton.textContent = bg ? (effectsPaused?'Пусни ефектите':'Спри ефектите') : (effectsPaused?'Play effects':'Pause effects');
            effectsButton.setAttribute('aria-pressed',String(effectsPaused));
        }
    };
    effectsButton?.addEventListener('click',() => {effectsPaused=!effectsPaused;effects();});
    if(signal && 'IntersectionObserver' in window)new IntersectionObserver(entries=>{signalVisible=entries[0].isIntersecting;effects();}).observe(signal);
    document.addEventListener('visibilitychange',effects);
    reduced.addEventListener('change',effects);
    effects();

    const feature = document.getElementById('k9-motion-feature');
    if(!feature)return;
    const video = feature.querySelector('video');
    const playButton = feature.querySelector('.k9h-toggle');
    let loaded=false,ready=false,inView=false,userPaused=false,failed=false;
    const label=()=>{
        playButton.querySelector('b').textContent=video.paused?playButton.dataset.play:playButton.dataset.pause;
        playButton.setAttribute('aria-label',video.paused?playButton.dataset.play:playButton.dataset.pause);
        feature.dataset.motionState=video.paused?'paused':'playing';
    };
    const still=()=>{video.pause();feature.classList.remove('has-video');playButton.hidden=true;feature.dataset.motionState='still';};
    const sync=()=>{
        if(!ready||reduced.matches){still();return;}
        feature.classList.add('has-video');playButton.hidden=false;
        if(userPaused||!inView||document.hidden)video.pause();
        else video.play().catch(()=>{userPaused=true;label();});
        label();
    };
    const load=()=>{
        if(loaded||failed||reduced.matches||navigator.connection?.saveData||!inView)return;
        if(!video.canPlayType('video/webm; codecs="vp9"'))return;
        loaded=true;video.muted=true;video.src=video.dataset.src;video.load();
    };
    video.addEventListener('loadeddata',()=>{
        try{
            const probe=document.createElement('canvas');probe.width=probe.height=1;
            const ctx=probe.getContext('2d',{willReadFrequently:true});
            ctx.drawImage(video,0,0,1,1,0,0,1,1);
            if(ctx.getImageData(0,0,1,1).data[3]>32){failed=true;still();return;}
            ready=true;sync();
        }catch{failed=true;still();}
    },{once:true});
    video.addEventListener('error',()=>{failed=true;ready=false;still();});
    video.addEventListener('play',label);video.addEventListener('pause',label);
    playButton.addEventListener('click',()=>{userPaused=!userPaused;sync();});
    document.addEventListener('visibilitychange',sync);
    reduced.addEventListener('change',()=>{load();sync();});
    if('IntersectionObserver' in window)new IntersectionObserver(entries=>{inView=entries[0].isIntersecting;load();sync();},{threshold:.05}).observe(feature.querySelector('.k9-motion-stage'));
    else{inView=true;load();}
})();