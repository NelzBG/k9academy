<?php
$hasTrainingGallery = true;
$photoGroups = [
 'home' => [17,7,21,28,1,8,16,24,10,25],
 'about' => [4,3,7,1,15,16,20,30],
 'services' => [13,11,12,16,25,26,27,28,8,24],
 'training' => [17,1,3,4,7,8,10,11,12,13,15,16,20,21,24,25,26,27,28,30],
 'contact' => [7,10,21,3,28,30]
];
$galleryKey = $galleryKey ?? 'training';
$galleryIds = $photoGroups[$galleryKey];
$photoData = json_decode(file_get_contents(__DIR__ . '/training-photos.json'), true);
$photoMap = array_column($photoData, null, 'id');
$labels = [
1=>['Ясен сигнал','A clear signal'],3=>['Заедно на терена','Together on the field'],
4=>['Спокойна връзка','A calm connection'],7=>['Куче и човек','Dog and handler'],
8=>['Спокойствие на открито','Settling outdoors'],10=>['Внимание отблизо','Attention up close'],
11=>['Подготовка за упражнение','Preparing an exercise'],12=>['До водача','Beside the handler'],
13=>['Поглед към водача','Eyes on the handler'],15=>['Работа в движение','Working in motion'],
16=>['Обща тренировка','Training together'],17=>['Движение на терена','Moving across the field'],
20=>['Следващият сигнал','The next signal'],21=>['Внимание и интерес','Attention and curiosity'],
24=>['Пауза между упражненията','Between exercises'],25=>['Крачка по крачка','Step by step'],
26=>['Покой сред групата','Settling with the group'],27=>['Различна перспектива','Another perspective'],
28=>['Малки кучета, голям характер','Small dogs, big character'],30=>['Една група, много характери','One group, many characters']
];
?>
<section class="training-gallery" id="training-gallery-<?= k9e($galleryKey) ?>" data-training-gallery aria-labelledby="training-gallery-title-<?= k9e($galleryKey) ?>">
 <div class="site-container training-gallery-heading">
  <div><p class="eyebrow eyebrow-acid">K9 / <?= $lang === 'bg' ? 'НА ТЕРЕНА' : 'FIELD NOTES' ?></p><h2 id="training-gallery-title-<?= k9e($galleryKey) ?>"><?= $lang === 'bg' ? 'Истински моменти.<br><em>Истинска връзка.</em>' : 'Real moments.<br><em>Real connection.</em>' ?></h2></div>
  <div class="training-gallery-intro"><p><?= $lang === 'bg' ? 'От първия контакт до общия ритъм. Разгледайте моменти от тренировката — плъзнете и докоснете снимка, за да я отворите.' : 'From the first connection to finding a rhythm together. Swipe through the session and tap a photograph to see it in full.' ?></p>
   <div class="training-gallery-controls">
    <button type="button" data-training-prev aria-label="<?= $lang === 'bg' ? 'Предишни снимки' : 'Previous training photos' ?>"><?= k9_icon('arrow','k9-icon-back') ?></button>
    <span data-training-count aria-live="polite">01 / <?= count($galleryIds) ?></span>
    <button type="button" data-training-next aria-label="<?= $lang === 'bg' ? 'Следващи снимки' : 'Next training photos' ?>"><?= k9_icon('arrow') ?></button>
   </div>
  </div>
 </div>
 <div class="training-photo-rail" data-training-rail tabindex="0" role="region" aria-label="<?= $lang === 'bg' ? 'Снимки от тренировката — плъзнете за още' : 'Training photographs — swipe to explore' ?>">
 <?php foreach ($galleryIds as $i => $id): $p=$photoMap[$id]; $base='assets/images/training-library/training-'.str_pad((string)$id,3,'0',STR_PAD_LEFT); $caption=$labels[$id][$lang === 'bg' ? 0 : 1]; ?>
  <figure class="training-photo-card <?= $p['height'] > $p['width'] ? 'is-portrait' : '' ?>">
   <a href="<?= k9e($base) ?>-1920.webp" data-training-photo data-caption="<?= k9e($caption) ?>" aria-label="<?= k9e(($lang === 'bg' ? 'Отворете снимката: ' : 'Open photograph: ').$caption) ?>">
    <img src="<?= k9e($base) ?>-1280.webp" srcset="<?= k9e($base) ?>-640.webp 640w, <?= k9e($base) ?>-1280.webp 1280w, <?= k9e($base) ?>-1920.webp 1920w" sizes="(min-width: 900px) 580px, 82vw" width="<?= $p['width'] ?>" height="<?= $p['height'] ?>" loading="lazy" decoding="async" alt="<?= k9e($caption) ?>">
    <span class="training-photo-open" aria-hidden="true"><?= k9_icon('arrow','k9-icon-up') ?></span>
   </a>
   <figcaption><span><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></span><strong><?= k9e($caption) ?></strong></figcaption>
  </figure>
 <?php endforeach; ?>
 </div>
 <div class="site-container training-gallery-bottom"><span><?= $lang === 'bg' ? 'ПЛЪЗНЕТЕ · РАЗГЛЕДАЙТЕ · ОТКРИЙТЕ' : 'SWIPE · EXPLORE · CONNECT' ?></span><span><?= count($galleryIds) ?> <?= $lang === 'bg' ? 'моменти от терена' : 'moments from the field' ?></span></div>
 <dialog class="training-lightbox" aria-label="<?= $lang === 'bg' ? 'Снимки от тренировката' : 'Training photo viewer' ?>">
  <form method="dialog"><button class="training-lightbox-close" aria-label="<?= $lang === 'bg' ? 'Затвори снимката' : 'Close photograph' ?>"><?= k9_icon('close') ?></button></form>
  <img data-training-large alt="">
  <div class="training-lightbox-bar"><button type="button" data-lightbox-prev aria-label="<?= $lang === 'bg' ? 'Предишна снимка' : 'Previous photograph' ?>"><?= k9_icon('arrow','k9-icon-back') ?></button><p data-lightbox-caption aria-live="polite"></p><button type="button" data-lightbox-next aria-label="<?= $lang === 'bg' ? 'Следваща снимка' : 'Next photograph' ?>"><?= k9_icon('arrow') ?></button></div>
 </dialog>
</section>
