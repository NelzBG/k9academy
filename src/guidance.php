<?php
require __DIR__ . '/site.php';
$guide = $editorial['pages'][$pageKey][$lang];
require __DIR__ . '/head.php';
require __DIR__ . '/header.php';
?>
<main id="main-content">
<section class="editorial-hero k9-textured">
 <div class="site-container editorial-hero-grid">
  <div><p class="eyebrow">K9 / <?= $lang === 'bg' ? 'ДОБРО НАЧАЛО' : 'A BETTER START' ?></p><h1><?= k9e($guide['title']) ?></h1><p class="editorial-lead"><?= k9e($guide['intro']) ?></p><a class="button button-acid" href="<?= k9e(k9_url('contact.php',null,'#contact-form')) ?>"><?= $lang === 'bg' ? 'Обсъдете вашия план' : 'Discuss your plan' ?> <?= k9_icon('arrow') ?></a></div>
  <figure class="editorial-art"><img src="/assets/images/editorial/<?= $pageKey === 'puppy' ? 'puppy' : 'complete' ?>-cover.webp" width="640" height="960" alt="<?= $lang === 'bg' ? 'Абстрактна илюстрация на връзката между човек и куче' : 'Abstract illustration of the connection between people and dogs' ?>" fetchpriority="high" decoding="async"></figure>
 </div>
</section>
<section class="editorial-reading k9-textured"><div class="site-container reading-layout">
 <nav class="reading-index" aria-label="<?= $lang === 'bg' ? 'В тази страница' : 'On this page' ?>"><p class="eyebrow"><?= $lang === 'bg' ? 'СТЪПКА ПО СТЪПКА' : 'STEP BY STEP' ?></p><?php foreach ($guide['sections'] as $i=>$section): ?><a href="#guide-<?= $i ?>"><?= k9e($section['title']) ?></a><?php endforeach; ?></nav>
 <div class="reading-copy"><?php foreach ($guide['sections'] as $i=>$section): ?><section id="guide-<?= $i ?>"><span class="reading-number">0<?= $i+1 ?></span><h2><?= k9e($section['title']) ?></h2><?= k9_editorial_html($section['body']) ?></section><?php if ($i === 1) require __DIR__ . '/callback-section.php'; ?><?php endforeach; ?>
 <aside class="editorial-callout"><h2><?= $lang === 'bg' ? 'Нека намерим вашата посока.' : 'Let’s find your direction.' ?></h2><p><?= k9e($guide['cta']) ?></p><a class="button button-acid" href="<?= k9e(k9_url('contact.php',null,'#contact-form')) ?>"><?= $lang === 'bg' ? 'Свържете се с K9 Academy' : 'Contact K9 Academy' ?></a></aside></div>
</div></section>
</main>
<?php require __DIR__ . '/footer.php'; ?>
