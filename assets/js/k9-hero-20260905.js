(() => {
  'use strict';
  const hero = document.getElementById('k9-evolution-hero');
  if (!hero) return;
  const video = hero.querySelector('video');
  const button = hero.querySelector('.k9h-toggle');
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
  let ready = false, started = false, inView = true, userPaused = false, failed = false;
  const label = () => {
    button.querySelector('b').textContent = video.paused ? button.dataset.play : button.dataset.pause;
    button.querySelector('span').textContent = video.paused ? '▷' : 'Ⅱ';
    button.setAttribute('aria-label', video.paused ? button.dataset.play : button.dataset.pause);
    hero.dataset.motionState = video.paused ? 'paused' : 'playing';
  };
  const still = () => {
    video.pause();
    hero.classList.remove('has-video');
    button.hidden = true;
    hero.dataset.motionState = 'still';
  };
  const sync = () => {
    if (!ready || reduced.matches) { still(); return; }
    hero.classList.add('has-video');
    button.hidden = false;
    if (userPaused || !inView || document.hidden) video.pause();
    else video.play().catch(() => { userPaused = true; label(); });
    label();
  };
  const load = () => {
    if (started || failed || reduced.matches || navigator.connection?.saveData) return;
    if (!video.canPlayType('video/webm; codecs="vp9"')) return;
    started = true;
    video.muted = true;
    video.src = video.dataset.src;
    video.load();
  };
  video.addEventListener('loadeddata', () => {
    // Confirm decoded transparency before displaying the cutout.
    try {
      const probe = document.createElement('canvas');
      probe.width = probe.height = 1;
      const context = probe.getContext('2d', {willReadFrequently:true});
      context.drawImage(video, 0, 0, 1, 1, 0, 0, 1, 1);
      if (context.getImageData(0, 0, 1, 1).data[3] > 32) { failed = true; still(); return; }
      ready = true;
      sync();
    } catch { failed = true; still(); }
  }, {once:true});
  video.addEventListener('error', () => { failed = true; ready = false; still(); });
  video.addEventListener('play', label);
  video.addEventListener('pause', label);
  button.addEventListener('click', () => { userPaused = !userPaused; sync(); });
  document.addEventListener('visibilitychange', sync);
  reduced.addEventListener('change', () => { load(); sync(); });
  if ('IntersectionObserver' in window) new IntersectionObserver(entries => {
    inView = entries[0].isIntersecting;
    if (inView) load();
    sync();
  }, {threshold:0.05}).observe(hero);
  load();
})();