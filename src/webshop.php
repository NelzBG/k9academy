<?php
$pageKey = 'shop';
require __DIR__ . '/site.php';
require __DIR__ . '/head.php';
require __DIR__ . '/header.php';

$copy = $lang === 'bg' ? [
    'kicker' => 'K9 Shop',
    'title' => 'Екипировката също<br><em>говори на кучето.</em>',
    'lead' => 'Подбрани храни, награди и тренировъчни средства за ежедневна грижа и по-ясна работа.',
    'maintenance' => 'Магазинът временно е в профилактика.',
    'maintenance_copy' => 'Можете да посетите свързания K9 Shop и да проверите кога каталогът отново ще бъде достъпен.',
    'visit' => 'Към k9shop.bg',
    'categories_kicker' => 'Подбрани категории',
    'categories_title' => 'Само неща с практична роля.',
    'ethos_title' => 'Наградата има момент. Екипировката има функция.',
    'ethos_copy' => 'Правилният продукт не заменя обучението. Той прави сигнала по-ясен, повторението по-точно и ежедневната грижа по-последователна.',
    'ask' => 'Попитайте какво е подходящо',
] : [
    'kicker' => 'K9 Shop',
    'title' => 'Equipment also<br><em>speaks to the dog.</em>',
    'lead' => 'Selected food, rewards and practical training tools for daily care and clearer work.',
    'maintenance' => 'The shop is temporarily closed for maintenance.',
    'maintenance_copy' => 'You can visit the associated K9 Shop and check when the catalogue becomes available again.',
    'visit' => 'Visit k9shop.bg',
    'categories_kicker' => 'Selected categories',
    'categories_title' => 'Only things with a practical role.',
    'ethos_title' => 'A reward has timing. Equipment has a function.',
    'ethos_copy' => 'The right product never replaces training. It makes the signal clearer, repetition more precise and everyday care more consistent.',
    'ask' => 'Ask what is suitable',
];

$categories = $lang === 'bg' ? [
    ['01', 'Храна', 'Включително продукти Farm Food® и решения за ежедневен хранителен режим.', 'bowl'],
    ['02', 'Награди', 'Малки, удобни наградки за точен тайминг и ясна положителна мотивация.', 'reward'],
    ['03', 'Тренировъчни средства', 'Практични помощни средства за фокус, движение и контролирано повторение.', 'lead'],
] : [
    ['01', 'Food', 'Including Farm Food® products and options for a consistent daily feeding routine.', 'bowl'],
    ['02', 'Rewards', 'Small, practical treats for precise timing and clear positive motivation.', 'reward'],
    ['03', 'Training tools', 'Practical equipment for focus, movement and controlled repetition.', 'lead'],
];
?>
<main id="main-content">
    <section class="shop-hero">
        <div class="site-container shop-hero-grid">
            <div class="shop-copy reveal is-visible"><p class="eyebrow eyebrow-acid"><?= k9e($copy['kicker']) ?></p><h1><?= $copy['title'] ?></h1><p><?= k9e($copy['lead']) ?></p></div>
            <div class="shop-object reveal is-visible" aria-hidden="true"><div class="shop-orbit"><span>K9</span><i></i><b></b></div><p>FOCUS / REWARD / REPEAT</p></div>
        </div>
    </section>

    <section class="maintenance-band"><div class="site-container maintenance-grid"><div><span aria-hidden="true">!</span><div><h2><?= k9e($copy['maintenance']) ?></h2><p><?= k9e($copy['maintenance_copy']) ?></p></div></div><a class="button button-ink" href="https://k9shop.bg/" target="_blank" rel="noopener"><?= k9e($copy['visit']) ?><span aria-hidden="true"><?= k9_icon('arrow','k9-icon-up') ?></span></a></div></section>

    <section class="content-section">
        <div class="site-container">
            <div class="section-heading reveal"><div><p class="eyebrow"><?= k9e($copy['categories_kicker']) ?></p><h2><?= k9e($copy['categories_title']) ?></h2></div></div>
            <div class="shop-category-grid">
                <?php foreach ($categories as [$number, $title, $body, $type]): ?>
                    <article class="shop-card reveal"><div class="product-symbol product-<?= k9e($type) ?>" aria-hidden="true"><span></span><i></i></div><div><span><?= k9e($number) ?></span><h3><?= k9e($title) ?></h3><p><?= k9e($body) ?></p></div></article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="shop-ethos"><div class="site-container editorial-split"><div class="reveal"><p class="eyebrow eyebrow-acid">K9 / Utility</p><h2><?= k9e($copy['ethos_title']) ?></h2></div><div class="editorial-copy reveal"><p><?= k9e($copy['ethos_copy']) ?></p><a class="button button-acid" href="<?= k9e(k9_url('contact.php')) ?>"><?= k9e($copy['ask']) ?><span aria-hidden="true"><?= k9_icon('arrow','k9-icon-up') ?></span></a></div></div></section>
</main>
<?php require __DIR__ . '/footer.php'; ?>
