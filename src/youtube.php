<?php $youtubeDomId = 'k9-youtube-' . $youtubeContext; ?>
<div id="<?= k9e($youtubeDomId) ?>" class="k9-youtube k9-youtube-background <?= $youtubeContext==='training'?'k9-youtube-training-background':'' ?>" data-youtube="<?= k9e($youtubeId) ?>" data-start="<?= (int)$youtubeStart ?>">
 <?php if ($youtubeContext==='training'): ?>
 <picture>
  <source type="image/avif" srcset="/assets/video/k9-training-poster-640.avif 640w, /assets/video/k9-training-poster-1280.avif 1280w, /assets/video/k9-training-poster-1920.avif 1920w" sizes="100vw">
  <?= str_replace('sizes="(min-width: 900px) 50vw, 100vw"', 'sizes="100vw"', k9_training_image(17, '', 'k9-video-poster', true)) ?>
 </picture>
 <?php endif; ?>
 <div class="k9-youtube-frame" aria-hidden="true"></div>
</div>
