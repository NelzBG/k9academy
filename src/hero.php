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
<section class="k9-hero" aria-labelledby="k9-hero-title">
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
        <div class="k9-signal" aria-hidden="true">
            <div class="k9-signal-orbit"></div>
            <div class="k9-signal-orbit k9-signal-orbit-two"></div>
            <div class="k9-signal-band k9-signal-band-one"></div>
            <div class="k9-signal-band k9-signal-band-two"></div>
            <div class="k9-signal-axis"></div>
            <div class="k9-signal-coordinate">K9 / CONNECTION</div>
            <div class="k9-signal-caption">INSTINCT<br><b>IN SYNC.</b></div>
        </div>
        <div class="k9-hero-bottom">
            <p><?= k9e($intro['team']) ?></p><button class="k9-effects-toggle" type="button" data-effects-toggle hidden><?= $lang === 'bg' ? 'Спри ефектите' : 'Pause effects' ?></button>
            <div class="ui:flex ui:flex-wrap ui:gap-5"><span><b>01</b> <?= k9e($intro['one']) ?></span><span><b>02</b> <?= k9e($intro['two']) ?></span><span><b>03</b> <?= k9e($intro['three']) ?></span></div>
        </div>
    </div>
</section>
