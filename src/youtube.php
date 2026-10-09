<?php $youtubeDomId = !empty($youtubeHero)?'k9-youtube-home':'k9-youtube-training'; ?>
<div id="<?= k9e($youtubeDomId) ?>" class="k9-youtube <?= !empty($youtubeHero)?'k9-youtube-background':'k9-youtube-feature' ?>" data-youtube="<?= k9e($youtubeId) ?>" data-start="<?= (int)$youtubeStart ?>">
 <?php if (empty($youtubeHero)): ?><img class="k9-video-poster" src="/assets/images/training-library/training-001-1280.webp" srcset="/assets/images/training-library/training-001-640.webp 640w, /assets/images/training-library/training-001-1280.webp 1280w" sizes="100vw" width="1280" height="720" alt="" fetchpriority="high" decoding="async"><div class="k9-video-caption"><p class="eyebrow">K9 / <?= $lang==='bg'?'НА ТЕРЕНА':'ON THE FIELD' ?></p><h2><?= $lang==='bg'?'Вижте как тренираме.':'See how we train.' ?></h2></div><?php endif; ?>
 <div class="k9-youtube-frame" aria-hidden="true"></div>
 <?php if (empty($youtubeHero)) require __DIR__ . '/youtube-controls.php'; ?>
</div>
