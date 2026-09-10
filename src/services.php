<?php
$pageKey = 'services';
require __DIR__ . '/site.php';
require __DIR__ . '/head.php';
require __DIR__ . '/header.php';

$copy = $lang === 'bg' ? [
    'kicker' => 'K9 програми',
    'title' => 'От първата команда<br><em>до стабилен контрол.</em>',
    'lead' => 'Избираме направление според възрастта, средата, поведението и целите на водача — не според готов шаблон.',
    'core_kicker' => 'Основни услуги',
    'core_title' => 'Работим там, където поведението има значение.',
    'extra_kicker' => 'Още направления',
    'extra_title' => 'Подготовка за различни етапи от живота.',
    'note' => 'Всички курсове се провеждат под наблюдение на треньор. Водачи под 18 години участват с родител.',
    'cta_title' => 'Не сте сигурни откъде да започнете?',
    'cta_copy' => 'Кратката първоначална консултация помага да определим най-подходящия формат.',
    'cta_button' => 'Опишете вашия случай',
] : [
    'kicker' => 'K9 programmes',
    'title' => 'From the first command<br><em>to stable control.</em>',
    'lead' => 'We choose the direction around age, environment, behaviour and handler goals — never from a generic template.',
    'core_kicker' => 'Core services',
    'core_title' => 'We work where behaviour matters.',
    'extra_kicker' => 'More disciplines',
    'extra_title' => 'Preparation for different stages of life.',
    'note' => 'All courses are trainer-supervised. Handlers under 18 participate with a parent.',
    'cta_title' => 'Not sure where to begin?',
    'cta_copy' => 'A short initial consultation helps us identify the most suitable format.',
    'cta_button' => 'Describe your case',
];

$core = $lang === 'bg' ? [
    ['01', 'Послушание', 'Седни, легни, тук, стой, повод, редом и фокус — от основата до изпълнение сред реални дразнители.', '3–12 месеца / индивидуално'],
    ['02', 'Социализация', 'Контролирани срещи с кучета, хора, деца, шум и градски ситуации за по-спокойна реакция.', 'Индивидуално или групово'],
    ['03', 'Корекция', 'Анализ и план при страх, реактивност, агресия, ресурсна защита и други нежелани поведения.', 'Над 12 месеца / индивидуално'],
    ['04', 'Защита и охрана', 'Дисциплинирано разпознаване на риск, контролирана реакция и стабилно прекратяване по команда.', 'След оценка / индивидуално'],
] : [
    ['01', 'Obedience', 'Sit, down, recall, stay, lead work, heel and focus — from foundations to reliable responses around distractions.', '3–12 months / one-to-one'],
    ['02', 'Socialisation', 'Controlled exposure to dogs, people, children, noise and urban situations for calmer responses.', 'Individual or group'],
    ['03', 'Behaviour correction', 'Assessment and planning for fear, reactivity, aggression, resource guarding and other unwanted behaviour.', 'Over 12 months / one-to-one'],
    ['04', 'Protection and guarding', 'Disciplined risk recognition, controlled response and reliable disengagement on command.', 'After assessment / one-to-one'],
];

$extra = $lang === 'bg' ? [
    ['Избор на порода', 'Консултация преди решението, съобразена с ритъма и средата на бъдещия водач.'],
    ['Хранене и отглеждане', 'Практични насоки за ежедневен режим, грижа и устойчиви навици.'],
    ['Изложбена подготовка', 'Подготовка на кучето за ринг и професионално хендлерство.'],
    ['Деца-водачи', 'Контролирано обучение за ясна, безопасна и уверена комуникация.'],
] : [
    ['Breed selection', 'A pre-decision consultation matched to the future handler’s lifestyle and environment.'],
    ['Nutrition and care', 'Practical guidance for daily routine, responsible care and sustainable habits.'],
    ['Show preparation', 'Ring preparation and professional handling support.'],
    ['Junior handlers', 'Supervised training for clear, safe and confident communication.'],
];
?>
<main id="main-content">
    <section class="page-hero page-hero-services">
        <div class="page-hero-bg" aria-hidden="true"></div>
        <div class="site-container page-hero-grid">
            <div class="page-hero-copy reveal is-visible"><p class="eyebrow eyebrow-acid"><?= k9e($copy['kicker']) ?></p><h1><?= $copy['title'] ?></h1><p><?= k9e($copy['lead']) ?></p></div>
            <div class="page-hero-media reveal is-visible"><img src="assets/images/training-focus.webp" srcset="<?= k9e(k9_image_srcset('training-focus.webp')) ?>" sizes="(min-width: 56rem) 42vw, calc(100vw - 2rem)" width="1600" height="1068" alt="<?= $lang === 'bg' ? 'Куче демонстрира фокус по време на K9 тренировка' : 'Dog demonstrating focus during a K9 training session' ?>" fetchpriority="high" decoding="async"><span>SERVICES / 02</span></div>
        </div>
    </section>

    <section class="content-section service-section">
        <div class="site-container">
            <div class="section-heading reveal"><div><p class="eyebrow"><?= k9e($copy['core_kicker']) ?></p><h2><?= k9e($copy['core_title']) ?></h2></div></div>
            <div class="service-stack">
                <?php foreach ($core as [$number, $title, $body, $meta]): ?>
                    <article class="service-row reveal"><span><?= k9e($number) ?></span><div><h3><?= k9e($title) ?></h3><p><?= k9e($body) ?></p></div><small><?= k9e($meta) ?></small><a href="<?= k9e(k9_url('contact.php')) ?>" aria-label="<?= k9e($title) ?>"><?= k9_icon('arrow','k9-icon-up') ?></a></article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="extras-section">
        <div class="site-container">
            <div class="section-heading reveal"><div><p class="eyebrow eyebrow-acid"><?= k9e($copy['extra_kicker']) ?></p><h2><?= k9e($copy['extra_title']) ?></h2></div><p><?= k9e($copy['note']) ?></p></div>
            <div class="extras-grid">
                <?php foreach ($extra as $index => [$title, $body]): ?><article class="extra-card reveal"><span>0<?= $index + 5 ?></span><h3><?= k9e($title) ?></h3><p><?= k9e($body) ?></p></article><?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="inline-cta"><div class="site-container inline-cta-grid reveal"><div><p class="eyebrow eyebrow-dark">K9 / Start</p><h2><?= k9e($copy['cta_title']) ?></h2><p><?= k9e($copy['cta_copy']) ?></p></div><a class="button button-ink" href="<?= k9e(k9_url('contact.php')) ?>"><?= k9e($copy['cta_button']) ?><span aria-hidden="true"><?= k9_icon('arrow','k9-icon-up') ?></span></a></div></section>
</main>
<?php require __DIR__ . '/footer.php'; ?>
