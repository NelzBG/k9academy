<?php
$pageKey = 'home';
$hasDogViewer = true;
require __DIR__ . '/site.php';
require __DIR__ . '/head.php';
require __DIR__ . '/header.php';

$home = $lang === 'bg' ? [
    'hero_kicker' => 'K9 Academy / България',
    'hero_title' => 'Ясни сигнали.<br><em>Спокойна връзка.</em>',
    'hero_copy' => 'Балансирано обучение за кучето и човека до него — с последователност, реални ситуации и уважение към индивидуалния темперамент.',
    'hero_primary' => 'Открийте правилната програма',
    'hero_secondary' => 'Вижте как тренираме',
    'hero_image_alt' => 'Треньор и куче по време на контролирана K9 тренировка за защита',
    'hero_label' => 'Фокус / Контрол / Доверие',
    'stat_one' => 'основни направления',
    'stat_two' => 'план за всяко куче',
    'stat_three' => 'екип: куче + водач',
    'program_kicker' => 'Обучението има посока',
    'program_title' => 'Четири нужди. Един ясен подход.',
    'program_copy' => 'Не тренираме команди във вакуум. Изграждаме поведение, което работи у дома, на улицата и под напрежение.',
    'more' => 'Разгледайте',
    'gallery_kicker' => 'В полето',
    'gallery_title' => 'Работата се вижда в движението.',
    'gallery_copy' => 'Плъзнете през реални моменти от тренировъчния процес.',
    'gallery_prev' => 'Предишна снимка',
    'gallery_next' => 'Следваща снимка',
    'dog_kicker' => 'Интерактивен K9',
    'dog_title' => 'Запознайте се с атлета.',
    'dog_copy' => 'Завъртете модела, приближете и пуснете различни движения. Използваме визуализацията, за да покажем баланс, стойка и контрол.',
    'dog_loading' => 'Подготвяме модела…',
    'dog_hint' => 'Плъзнете за завъртане · щипнете за приближаване',
    'philosophy_kicker' => 'Нашата философия',
    'philosophy_title' => 'Стабилното поведение започва с доверие.',
    'philosophy_copy' => 'Комбинираме положителна мотивация, ясни граници и последователност. Така кучето разбира какво очакваме, а водачът получава увереност да продължи правилно и след тренировката.',
    'philosophy_link' => 'Повече за K9 Academy',
] : [
    'hero_kicker' => 'K9 Academy / Bulgaria',
    'hero_title' => 'Clear signals.<br><em>A calmer bond.</em>',
    'hero_copy' => 'Balanced training for the dog and the person beside them — built on consistency, real-world situations and respect for individual temperament.',
    'hero_primary' => 'Find the right programme',
    'hero_secondary' => 'See how we train',
    'hero_image_alt' => 'Trainer and dog during a controlled K9 protection session',
    'hero_label' => 'Focus / Control / Trust',
    'stat_one' => 'core disciplines',
    'stat_two' => 'plan for every dog',
    'stat_three' => 'team: dog + handler',
    'program_kicker' => 'Training needs direction',
    'program_title' => 'Four needs. One clear approach.',
    'program_copy' => 'We do not train commands in a vacuum. We build behaviour that works at home, on the street and under pressure.',
    'more' => 'Explore',
    'gallery_kicker' => 'In the field',
    'gallery_title' => 'You can see the work in the movement.',
    'gallery_copy' => 'Swipe through real moments from the training process.',
    'gallery_prev' => 'Previous photo',
    'gallery_next' => 'Next photo',
    'dog_kicker' => 'Interactive K9',
    'dog_title' => 'Meet the athlete.',
    'dog_copy' => 'Rotate the model, zoom in and trigger different movements. The visualisation highlights balance, posture and control.',
    'dog_loading' => 'Preparing the model…',
    'dog_hint' => 'Drag to rotate · pinch to zoom',
    'philosophy_kicker' => 'Our philosophy',
    'philosophy_title' => 'Stable behaviour starts with trust.',
    'philosophy_copy' => 'We combine positive motivation, clear boundaries and consistency. The dog understands what is expected, while the handler gains the confidence to continue correctly after each session.',
    'philosophy_link' => 'More about K9 Academy',
];

$programmes = $lang === 'bg' ? [
    ['01', 'Послушание', 'От базови команди и повод до фокус, редом и надеждна реакция в различни среди.'],
    ['02', 'Социализация', 'Спокойно поведение сред кучета, хора, деца, шум и ритъма на градската среда.'],
    ['03', 'Корекция', 'Индивидуална работа при страх, реактивност, агресия и други нежелани модели.'],
    ['04', 'Защита', 'Контролирана подготовка за охрана, разпознаване на риск и дисциплинирана реакция.'],
] : [
    ['01', 'Obedience', 'From core commands and lead work to focus, heel and reliable responses in varied environments.'],
    ['02', 'Socialisation', 'Calm behaviour around dogs, people, children, noise and the pace of an urban environment.'],
    ['03', 'Correction', 'Individual work for fear, reactivity, aggression and other unwanted behaviour patterns.'],
    ['04', 'Protection', 'Controlled preparation for guarding, risk awareness and disciplined response.'],
];

$gallery = $lang === 'bg' ? [
    ['training-portrait.webp', 'Работа под напрежение', 'Контролът остава ясен дори при висока енергия.', 'Треньор с предпазен ръкав и куче по време на тренировка'],
    ['training-field.webp', 'Реална среда', 'Учим кучето да чува водача, а не шума около него.', 'Куче и треньори по време на полева K9 тренировка'],
    ['training-focus.webp', 'Точен фокус', 'Малките решения изграждат надеждно поведение.', 'Фокусирано куче държи тренировъчен ръкав'],
    ['training-team.webp', 'Един екип', 'Треньор, водач и куче работят към една цел.', 'Куче и треньор работят върху препятствие'],
] : [
    ['training-portrait.webp', 'Work under pressure', 'Control stays clear even when energy runs high.', 'Trainer with a protective sleeve and dog during a session'],
    ['training-field.webp', 'Real environments', 'The dog learns to hear the handler, not the noise around them.', 'Dog and trainers during an outdoor K9 session'],
    ['training-focus.webp', 'Precise focus', 'Small decisions build reliable behaviour.', 'Focused dog holding a training sleeve'],
    ['training-team.webp', 'One team', 'Trainer, handler and dog work towards one goal.', 'Dog and trainer working over an obstacle'],
];
?>
<main id="main-content">
    <?php require __DIR__ . '/hero.php'; ?>
    <?php require __DIR__ . '/hero-motion.php'; ?>
    <?php require __DIR__ . '/breed-carousel.php'; ?>

    <section class="section-shell" id="programmes">
        <div class="site-container">
            <div class="section-heading reveal">
                <div><p class="eyebrow"><?= k9e($home['program_kicker']) ?></p><h2><?= k9e($home['program_title']) ?></h2></div>
                <p><?= k9e($home['program_copy']) ?></p>
            </div>
            <div class="programme-grid">
                <?php foreach ($programmes as [$number, $title, $copy]): ?>
                    <article class="programme-card reveal">
                        <span><?= k9e($number) ?></span>
                        <div><h3><?= k9e($title) ?></h3><p><?= k9e($copy) ?></p></div>
                        <a href="<?= k9e(k9_url('services.php')) ?>" aria-label="<?= k9e($home['more'] . ': ' . $title) ?>"><?= k9_icon('arrow','k9-icon-up') ?></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="gallery-section" aria-labelledby="gallery-title">
        <div class="site-container gallery-heading reveal">
            <div><p class="eyebrow eyebrow-acid"><?= k9e($home['gallery_kicker']) ?></p><h2 id="gallery-title"><?= k9e($home['gallery_title']) ?></h2></div>
            <div class="gallery-intro"><p><?= k9e($home['gallery_copy']) ?></p><div class="gallery-controls"><button type="button" data-gallery-prev aria-label="<?= k9e($home['gallery_prev']) ?>"><?= k9_icon('arrow','k9-icon-back') ?></button><button type="button" data-gallery-next aria-label="<?= k9e($home['gallery_next']) ?>"><?= k9_icon('arrow') ?></button></div></div>
        </div>
        <div class="gallery-rail" data-gallery>
            <?php foreach ($gallery as $index => [$image, $title, $copy, $alt]): ?>
                <figure class="gallery-card" data-gallery-card>
                    <img src="assets/images/<?= k9e($image) ?>" srcset="<?= k9e(k9_image_srcset($image)) ?>" sizes="(min-width: 40rem) min(67vw, 42rem), 86vw" width="1600" height="1068" alt="<?= k9e($alt) ?>" loading="lazy" decoding="async">
                    <figcaption><span>0<?= $index + 1 ?></span><div><h3><?= k9e($title) ?></h3><p><?= k9e($copy) ?></p></div></figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
        <div class="site-container gallery-progress" aria-hidden="true"><span data-gallery-progress></span></div>
    </section>

    <section class="dog-lab" id="dog-lab" aria-labelledby="dog-lab-title">
        <div class="site-container dog-lab-grid">
            <div class="dog-lab-copy reveal">
                <p class="eyebrow eyebrow-acid"><?= k9e($home['dog_kicker']) ?></p>
                <h2 id="dog-lab-title"><?= k9e($home['dog_title']) ?></h2>
                <p><?= k9e($home['dog_copy']) ?></p>
                <div class="animation-controls" aria-label="Dog animations">
                    <button type="button" class="is-active" data-animation="idle"><?= $lang === 'bg' ? 'Покой' : 'Idle' ?></button>
                    <button type="button" data-animation="play"><?= $lang === 'bg' ? 'Игра' : 'Play' ?></button>
                    <button type="button" data-animation="walk"><?= $lang === 'bg' ? 'Ход' : 'Walk' ?></button>
                    <button type="button" data-animation="run"><?= $lang === 'bg' ? 'Бяг' : 'Run' ?></button>
                </div>
                <p class="viewer-hint"><?= k9e($home['dog_hint']) ?></p>
            </div>
            <div class="dog-stage reveal" id="dog-stage" data-model="assets/model/german-shepherd-polished-20260906.glb" data-dog-stage>
                <canvas aria-label="<?= $lang === 'bg' ? 'Интерактивна анимирана 3D германска овчарка' : 'Interactive animated 3D German Shepherd' ?>"></canvas>
                <div class="dog-stage-grid" aria-hidden="true"></div>
                <div class="model-loading" data-model-loading><span></span><p><?= k9e($home['dog_loading']) ?></p></div>
                <a class="model-credit" href="https://retrostylegames.itch.io/german-shepherd-3d-dog-model-free" target="_blank" rel="noopener"><span>3D</span> German Shepherd / RetroStyle Games <?= k9_icon('arrow','k9-icon-up') ?></a>
            </div>
        </div>
    </section>

    <section class="philosophy-section">
        <div class="site-container philosophy-grid">
            <div class="philosophy-image reveal"><img src="assets/images/training-focus.webp" srcset="<?= k9e(k9_image_srcset('training-focus.webp')) ?>" sizes="(min-width: 64rem) 52vw, calc(100vw - 2rem)" width="1600" height="1068" alt="<?= $lang === 'bg' ? 'Фокусирано куче по време на контролирана тренировка' : 'Focused dog during a controlled training session' ?>" loading="lazy" decoding="async"><span aria-hidden="true">K9 / 04</span></div>
            <div class="philosophy-copy reveal">
                <p class="eyebrow"><?= k9e($home['philosophy_kicker']) ?></p>
                <h2><?= k9e($home['philosophy_title']) ?></h2>
                <p><?= k9e($home['philosophy_copy']) ?></p>
                <a class="text-link" href="<?= k9e(k9_url('aboutus.php')) ?>"><?= k9e($home['philosophy_link']) ?><span aria-hidden="true"><?= k9_icon('arrow') ?></span></a>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/footer.php'; ?>
