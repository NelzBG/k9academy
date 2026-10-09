(() => {
 'use strict';
 const bg = document.documentElement.lang === 'bg';
 const reduced = matchMedia('(prefers-reduced-motion: reduce)');
 const containers = [...document.querySelectorAll('[data-youtube]')];
 if (!containers.length) return;
 const accepted = () => { try { return localStorage.getItem('k9-cookie-choice') === 'accepted'; } catch { return false; } };
 let mediaConsent = false, apiPromise = null, activeDialog = null;
 const allowed = () => accepted() || mediaConsent;
 const text = {
  watch: bg ? 'Гледайте видеото' : 'Watch video',
  allowWatch: bg ? 'Разреши YouTube и гледай' : 'Allow YouTube & watch',
  play: bg ? 'Пусни фона' : 'Play background',
  allowPlay: bg ? 'Разреши YouTube и пусни фона' : 'Allow YouTube & play background',
  pause: bg ? 'Спри фона' : 'Pause background',
  starting: bg ? 'Зареждане…' : 'Starting…',
  blocked: bg ? 'Натиснете „Пусни фона“, за да стартирате видеото.' : 'Press Play background to start the video.',
  failed: bg ? 'Видеото не е достъпно в момента. Опитайте отново или го отворете в YouTube.' : 'Video is unavailable right now. Try again or open it in YouTube.'
 };
 const states = containers.map(container => {
  const controls = document.querySelector('[data-video-controls="' + container.id + '"]');
  return {container, controls, frame: container.querySelector('.k9-youtube-frame'), poster: container.querySelector('.k9-video-poster'),
   play: controls.querySelector('[data-pause-video]'), watch: controls.querySelector('[data-watch-video]'),
   status: controls.querySelector('[data-video-status]'), external: controls.querySelector('[data-youtube-link]'),
   api: null, ready: false, loading: false, generation: 0, inView: false, paused: false, deferred: null,
   blocked: false, explicit: false, playback: -1};
 });
 function label(state) {
  state.watch.textContent = allowed() ? text.watch : text.allowWatch;
  state.play.textContent = state.loading ? text.starting : state.playback === 1 ? text.pause : allowed() ? text.play : text.allowPlay;
  state.play.disabled = state.loading && !state.deferred;
  state.play.setAttribute('aria-pressed', String(state.playback === 1));
 }
 function iframe(state, foreground) {
  const frame = document.createElement('iframe');
  const args = new URLSearchParams({autoplay: '1', mute: foreground ? '0' : '1', playsinline: '1',
   controls: foreground ? '1' : '0', rel: '0', loop: foreground ? '0' : '1',
   playlist: state.container.dataset.youtube, start: state.container.dataset.start,
   enablejsapi: '1', origin: location.origin});
  frame.src = 'https://www.youtube-nocookie.com/embed/' + state.container.dataset.youtube + '?' + args;
  frame.title = bg ? 'K9 Academy тренировка' : 'K9 Academy training';
  frame.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
  frame.referrerPolicy = 'strict-origin-when-cross-origin';
  frame.allowFullscreen = true;
  frame.loading = foreground ? 'eager' : 'lazy';
  if (!foreground) { frame.id = state.container.id + '-player'; frame.tabIndex = -1; frame.setAttribute('aria-hidden', 'true'); }
  return frame;
 }
 function loadApi() {
  if (window.YT?.Player) return Promise.resolve(window.YT);
  if (apiPromise) return apiPromise;
  apiPromise = new Promise((resolve, reject) => {
   const script = document.createElement('script');
   const previous = window.onYouTubeIframeAPIReady;
   const timeout = setTimeout(() => { script.remove(); apiPromise = null; reject(Error('YouTube did not become ready')); }, 20000);
   window.onYouTubeIframeAPIReady = () => {
    clearTimeout(timeout);
    if (typeof previous === 'function') previous();
    resolve(window.YT);
   };
   script.src = 'https://www.youtube.com/iframe_api'; script.async = true;
   script.addEventListener('error', () => { clearTimeout(timeout); script.remove(); apiPromise = null; reject(Error('YouTube is unavailable')); }, {once: true});
   document.head.append(script);
  });
  return apiPromise;
 }
 function canPlay(state) {
  return allowed() && state.inView && !state.paused && !state.blocked && !document.hidden && !activeDialog &&
   !document.body.matches('.cookie-open,.menu-open') && (state.explicit || (!reduced.matches && !navigator.connection?.saveData));
 }
 function cancelDeferred(state) {
  const task = state.deferred;
  if (!task) return;
  state.deferred = null;
  if (task.kind === 'idle') window.cancelIdleCallback?.(task.id);
  else clearTimeout(task.id);
 }
 function destroy(state) {
  state.generation++;
  cancelDeferred(state);
  state.api?.destroy(); state.api = null; state.ready = false; state.loading = false; state.playback = -1;
  state.frame.replaceChildren(); state.container.classList.remove('is-video-ready'); state.container.classList.remove('is-video-requested');
  if (state.poster) state.poster.hidden = false;
  delete state.container.dataset.videoState;
 }
 function failed(state) {
  destroy(state); state.blocked = true; state.status.textContent = text.failed; state.external.hidden = false; label(state);
 }
 async function start(state, immediate = false) {
  if (state.api || !canPlay(state)) return;
  if (state.deferred) {
   if (!immediate) return;
   state.generation++;
   cancelDeferred(state);
   state.loading = false;
  }
  if (state.loading) return;
  const generation = ++state.generation;
  state.loading = true; state.status.textContent = ''; state.external.hidden = true; label(state);
  const mount = async () => {
   if (generation !== state.generation) return;
   state.deferred = null;
   if (!canPlay(state)) { state.loading = false; label(state); return; }
   const frame = iframe(state, false);
   state.frame.append(frame);
   try {
    const yt = await loadApi();
    if (generation !== state.generation || !allowed()) return;
    state.api = new yt.Player(frame.id, {events: {
     onReady: event => {
      if (generation !== state.generation) return;
      state.api = event.target; state.ready = true; state.loading = false;
      event.target.mute(); label(state); sync(state);
     },
     onStateChange: event => {
      if (generation !== state.generation) return;
      state.playback = event.data;
      if (event.data === 1) {
       state.blocked = false;
       if (!canPlay(state)) { event.target.pauseVideo(); return; }
       if (state.poster) state.poster.hidden = true;
       state.container.classList.add('is-video-ready'); state.container.dataset.videoState = 'playing';
       state.status.textContent = ''; state.external.hidden = true;
      } else if (event.data === 2) state.container.dataset.videoState = 'paused';
      else if (event.data === 0 && canPlay(state)) { event.target.seekTo(Number(state.container.dataset.start) || 0, true); event.target.playVideo(); }
      label(state);
     },
     onAutoplayBlocked: () => {
      if (generation !== state.generation) return;
      state.blocked = true; state.playback = -1; state.status.textContent = text.blocked; label(state);
     },
     onError: () => { if (generation === state.generation) failed(state); }
    }});
   } catch { if (generation === state.generation) failed(state); }
  };
  if (!immediate && !state.explicit) {
   if (typeof window.requestIdleCallback === 'function') {
    const id = window.requestIdleCallback(() => { void mount(); }, {timeout: 1200});
    state.deferred = {kind: 'idle', id};
   } else {
    const id = window.setTimeout(() => { void mount(); }, 350);
    state.deferred = {kind: 'timeout', id};
   }
   label(state);
   return;
  }
  await mount();
 }
 function sync(state) {
  if (!canPlay(state)) {
   if (state.deferred) {
    state.generation++;
    cancelDeferred(state);
    state.loading = false; label(state);
   }
   if (state.ready) state.api.pauseVideo();
   return;
  }
  if (state.ready) { state.api.mute(); state.api.playVideo(); }
  else start(state, state.explicit);
 }
 const syncAll = () => states.forEach(sync);
 function stopAll() { states.forEach(state => { if (state.ready) state.api.pauseVideo(); }); }
 const observer = 'IntersectionObserver' in window ? new IntersectionObserver(entries => {
  entries.forEach(entry => { const state = states.find(item => item.container === entry.target); state.inView = entry.isIntersecting; sync(state); });
 }, {threshold: .1}) : null;
 states.forEach(state => {
  label(state);
  if (observer) observer.observe(state.container); else { state.inView = true; sync(state); }
  state.play.addEventListener('click', () => {
   mediaConsent = true; state.explicit = true; state.blocked = false; state.container.classList.add('is-video-requested');
   state.paused = state.playback === 1;
   state.status.textContent = ''; states.forEach(label); sync(state);
  });
  state.watch.addEventListener('click', () => {
   mediaConsent = true; states.forEach(label);
   const dialog = document.createElement('dialog'); dialog.className = 'k9-video-dialog'; activeDialog = dialog; stopAll();
   dialog.setAttribute('aria-label', bg ? 'Гледайте K9 Academy' : 'Watch K9 Academy');
   const close = document.createElement('button'); close.type = 'button'; close.className = 'button button-acid';
   close.textContent = bg ? 'Затвори видеото' : 'Close video';
   const link = state.external.cloneNode(true); link.hidden = false; link.removeAttribute('data-youtube-link');
   dialog.append(close, iframe(state, true), link); document.body.append(dialog); dialog.showModal();
   close.addEventListener('click', () => dialog.close());
   dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
   dialog.addEventListener('close', () => { dialog.remove(); activeDialog = null; state.watch.focus({preventScroll: true}); syncAll(); });
  });
 });
 document.querySelectorAll('[data-cookie-choice]').forEach(button => button.addEventListener('click', () => queueMicrotask(() => {
  if (!accepted()) {
   mediaConsent = false; activeDialog?.close();
   states.forEach(state => { destroy(state); state.explicit = false; state.paused = false; state.blocked = false; state.status.textContent = ''; state.external.hidden = true; });
  }
  states.forEach(label); syncAll();
 })));
 reduced.addEventListener('change', () => { states.forEach(state => { state.explicit = false; if (reduced.matches) destroy(state); label(state); }); syncAll(); });
 navigator.connection?.addEventListener?.('change', syncAll);
 document.addEventListener('visibilitychange', syncAll);
 new MutationObserver(syncAll).observe(document.body, {attributes: true, attributeFilter: ['class']});
})();
