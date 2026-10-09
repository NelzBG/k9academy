<?php
$intro = $lang === 'bg' ? [
    'eyebrow' => 'K9 ACADEMY / БЪЛГАРИЯ',
    'first' => 'ЯСНИ СИГНАЛИ.',
    'second' => 'СИЛНА ВРЪЗКА.',
    'copy' => 'По-малко напрежение. Повече доверие. Обучение, което свързва кучето и човека — в реалния живот.',
    'cta' => 'Открийте своята програма',
    'secondary' => 'Нашият подход',
    'one' => 'Фокус', 'two' => 'Контрол', 'three' => 'Доверие',
    'team' => 'Куче + човек. Един екип.',
] : [
    'eyebrow' => 'K9 ACADEMY / BULGARIA',
    'first' => 'CLEAR SIGNALS.',
    'second' => 'STRONGER BONDS.',
    'copy' => 'Less friction. More trust. Training that brings dog and human together — for life beyond the training field.',
    'cta' => 'Find your programme',
    'secondary' => 'Our approach',
    'one' => 'Focus', 'two' => 'Control', 'three' => 'Trust',
    'team' => 'Dog + human. One team.',
];
?>
<section class="k9-hero k9-video-hero" aria-labelledby="k9-hero-title">
    <?php $youtubeId = '7LM1RGSomPU'; $youtubeStart = 69; $youtubeContext = 'home'; require __DIR__ . '/youtube.php'; ?>
    <div class="k9-hero-grid" aria-hidden="true"></div>
    <div class="site-container k9-hero-layout">
        <div class="k9-hero-copy">
            <p class="k9-overline"><i aria-hidden="true"></i><?= k9e($intro['eyebrow']) ?></p>
            <h1 id="k9-hero-title"><span><?= k9e($intro['first']) ?></span><em><?= k9e($intro['second']) ?></em></h1>
            <p class="k9-hero-description"><?= k9e($intro['copy']) ?></p>
            <div class="ui:flex ui:flex-wrap ui:gap-3 k9-hero-actions">
                <a class="k9-button k9-button-acid" href="<?= k9e(k9_url('services.php')) ?>"><?= k9e($intro['cta']) ?><?= k9_icon('arrow') ?></a>
                <a class="k9-button k9-button-outline" href="#programmes"><?= k9e($intro['secondary']) ?></a>
            </div>
        </div>
        <div class="k9-hero-bottom">
            <p><?= k9e($intro['team']) ?></p>
            <div class="ui:flex ui:flex-wrap ui:gap-5 k9-hero-principles"><span><b>01</b> <?= k9e($intro['one']) ?></span><span><b>02</b> <?= k9e($intro['two']) ?></span><span><b>03</b> <?= k9e($intro['three']) ?></span></div>
            <div class="k9-hero-tools">
                <?php require __DIR__ . '/youtube-controls.php'; ?>
            </div>
        </div>
    </div>
</section>
