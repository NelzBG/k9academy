<?php
$pageKey = 'blog';
require __DIR__ . '/site.php';
require __DIR__ . '/head.php';
require __DIR__ . '/header.php';
$copy = $editorial['pages']['blog'][$lang];
?>
<main id="main-content"><section class="editorial-hero k9-textured"><div class="site-container"><p class="eyebrow">K9 / FIELD NOTES</p><h1><?= k9e($copy['title']) ?></h1><p class="editorial-lead"><?= k9e($copy['intro']) ?></p></div></section>
<section class="k9-textured editorial-reading"><div class="site-container blog-grid"><?php foreach ($editorial['articles'] as $slug=>$post): ?><article class="blog-card"><a class="blog-image" href="<?= k9e(k9_url('article-'.$slug.'.php')) ?>"><img src="/assets/images/training-library/training-<?= str_pad((string)$post['photo'],3,'0',STR_PAD_LEFT) ?>-640.webp" width="640" height="426" alt="<?= k9e($post[$lang]['imageAlt']) ?>" loading="lazy" decoding="async"></a><div><p class="eyebrow"><?= k9e($post[$lang]['category']) ?> / <?= $lang==='bg'?'9 октомври 2026':'9 October 2026' ?></p><h2><a href="<?= k9e(k9_url('article-'.$slug.'.php')) ?>"><?= k9e($post[$lang]['title']) ?></a></h2><p><?= k9e($post[$lang]['intro']) ?></p><a class="editorial-link" href="<?= k9e(k9_url('article-'.$slug.'.php')) ?>"><?= $lang==='bg'?'Прочетете статията':'Read the guide' ?> <?= k9_icon('arrow') ?></a></div></article><?php endforeach; ?></div></section></main>
<?php require __DIR__ . '/footer.php'; ?>
