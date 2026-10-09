<div class="k9-video-actions" data-video-controls="<?= k9e($youtubeDomId) ?>">
 <button type="button" class="button button-acid" data-watch-video aria-controls="<?= k9e($youtubeDomId) ?>"><?= $lang==='bg'?'Разреши YouTube и гледай':'Allow YouTube & watch' ?></button>
 <button type="button" class="button button-ink" data-pause-video aria-controls="<?= k9e($youtubeDomId) ?>" aria-pressed="false"><?= $lang==='bg'?'Разреши YouTube и пусни фона':'Allow YouTube & play background' ?></button>
 <a class="button button-ghost" data-youtube-link hidden href="https://www.youtube.com/watch?v=<?= k9e($youtubeId) ?>&amp;t=<?= (int)$youtubeStart ?>s" target="_blank" rel="noopener"><?= $lang==='bg'?'Отвори в YouTube':'Open in YouTube' ?></a>
 <p class="k9-video-status" data-video-status role="status" aria-live="polite"></p>
 <noscript><a class="button button-acid" href="https://www.youtube.com/watch?v=<?= k9e($youtubeId) ?>&amp;t=<?= (int)$youtubeStart ?>s" target="_blank" rel="noopener"><?= $lang==='bg'?'Гледайте тренировката в YouTube':'Watch the training on YouTube' ?></a></noscript>
</div>
