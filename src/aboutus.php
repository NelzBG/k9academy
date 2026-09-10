<?php
$pageKey = 'about';
require __DIR__ . '/site.php';
require __DIR__ . '/head.php';
require __DIR__ . '/header.php';

$copy = $lang === 'bg' ? [
    'kicker' => 'За K9 Academy',
    'title' => 'Не обучаваме само кучето.<br><em>Обучаваме връзката.</em>',
    'lead' => 'Устойчивият резултат идва, когато кучето разбира сигналите, а човекът разбира как да ги подава спокойно и последователно.',
    'image_alt' => 'Куче следва насоките на своя треньор на тренировъчно поле',
    'story_kicker' => 'Философия',
    'story_title' => 'Доверие преди техника.',
    'story_body' => 'Вярваме, че балансираното обучение е ключът към щастливото съжителство между човек и куче. Комбинираме положителна мотивация, ясни граници и практическа работа в реални среди. Всеки план се съобразява с темперамента, историята и ежедневието на конкретния екип.',
    'story_note' => 'Целта не е кучето да изпълнява механично. Целта е да взема по-добри решения и да се доверява на водача си.',
    'values_kicker' => 'Начинът на работа',
    'values_title' => 'Три принципа във всяка сесия.',
    'trainer_kicker' => 'Треньорът',
    'trainer_title' => 'Любо Пенов',
    'trainer_body' => 'Работата в K9 Academy е насочена едновременно към поведението на кучето и уменията на водача. Подходът започва с наблюдение, продължава с ясен план и се затвърждава чрез упражнения, които могат да бъдат повторени в ежедневието.',
    'trainer_link' => 'Обсъдете вашия случай',
    'closing' => 'Всяко куче има свой праг, ритъм и мотивация. Планът трябва да ги уважава.',
] : [
    'kicker' => 'About K9 Academy',
    'title' => 'We do not train only the dog.<br><em>We train the relationship.</em>',
    'lead' => 'Lasting results appear when the dog understands the signals and the person understands how to give them calmly and consistently.',
    'image_alt' => 'Dog following its trainer’s guidance on a training field',
    'story_kicker' => 'Philosophy',
    'story_title' => 'Trust before technique.',
    'story_body' => 'We believe balanced training is the key to a happy life together. Positive motivation, clear boundaries and practical real-world work form the core of our approach. Every plan reflects the temperament, history and daily routine of the specific dog-and-handler team.',
    'story_note' => 'The goal is not mechanical obedience. It is a dog that makes better decisions and trusts its handler.',
    'values_kicker' => 'How we work',
    'values_title' => 'Three principles in every session.',
    'trainer_kicker' => 'The trainer',
    'trainer_title' => 'Lyubo Penov',
    'trainer_body' => 'K9 Academy works on the dog’s behaviour and the handler’s skills at the same time. The process begins with observation, continues with a clear plan and is reinforced through exercises that can be repeated in everyday life.',
    'trainer_link' => 'Discuss your case',
    'closing' => 'Every dog has its own threshold, rhythm and motivation. The plan should respect them.',
];

$values = $lang === 'bg' ? [
    ['01', 'Яснота', 'Един сигнал, едно значение и точният момент за награда или корекция.'],
    ['02', 'Последователност', 'Кучето напредва, когато правилата остават разпознаваеми във всяка среда.'],
    ['03', 'Уважение', 'Темпераментът не е пречка. Той е отправната точка за правилния метод.'],
] : [
    ['01', 'Clarity', 'One signal, one meaning and the right moment for reward or correction.'],
    ['02', 'Consistency', 'A dog progresses when the rules remain recognisable in every environment.'],
    ['03', 'Respect', 'Temperament is not an obstacle. It is the starting point for the right method.'],
];
?>
<main id="main-content">
    <section class="page-hero page-hero-about">
        <div class="page-hero-bg" aria-hidden="true"></div>
        <div class="site-container page-hero-grid">
            <div class="page-hero-copy reveal is-visible">
                <p class="eyebrow eyebrow-acid"><?= k9e($copy['kicker']) ?></p>
                <h1><?= $copy['title'] ?></h1>
                <p><?= k9e($copy['lead']) ?></p>
            </div>
            <div class="page-hero-media reveal is-visible"><img src="assets/images/training-field.webp" srcset="<?= k9e(k9_image_srcset('training-field.webp')) ?>" sizes="(min-width: 56rem) 42vw, calc(100vw - 2rem)" width="1600" height="1068" alt="<?= k9e($copy['image_alt']) ?>" fetchpriority="high" decoding="async"><span>ABOUT / 01</span></div>
        </div>
    </section>

    <section class="content-section">
        <div class="site-container editorial-split">
            <div class="reveal"><p class="eyebrow"><?= k9e($copy['story_kicker']) ?></p><h2><?= k9e($copy['story_title']) ?></h2></div>
            <div class="editorial-copy reveal"><p><?= k9e($copy['story_body']) ?></p><blockquote><?= k9e($copy['story_note']) ?></blockquote></div>
        </div>
    </section>

    <section class="values-section">
        <div class="site-container">
            <div class="section-heading reveal"><div><p class="eyebrow eyebrow-acid"><?= k9e($copy['values_kicker']) ?></p><h2><?= k9e($copy['values_title']) ?></h2></div></div>
            <div class="values-grid">
                <?php foreach ($values as [$number, $title, $body]): ?>
                    <article class="value-card reveal"><span><?= k9e($number) ?></span><h3><?= k9e($title) ?></h3><p><?= k9e($body) ?></p></article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="trainer-section">
        <div class="site-container trainer-grid">
            <div class="trainer-image reveal"><img src="assets/images/training-portrait.webp" srcset="<?= k9e(k9_image_srcset('training-portrait.webp')) ?>" sizes="(min-width: 64rem) 42vw, calc(100vw - 2rem)" width="1200" height="1799" alt="<?= $lang === 'bg' ? 'Треньор по време на практическа K9 сесия' : 'Trainer during a practical K9 session' ?>" loading="lazy" decoding="async"><span>K9 FIELDWORK</span></div>
            <div class="trainer-copy reveal"><p class="eyebrow"><?= k9e($copy['trainer_kicker']) ?></p><h2><?= k9e($copy['trainer_title']) ?></h2><p><?= k9e($copy['trainer_body']) ?></p><a class="button button-ink" href="<?= k9e(k9_url('contact.php')) ?>"><?= k9e($copy['trainer_link']) ?><span aria-hidden="true"><?= k9_icon('arrow','k9-icon-up') ?></span></a></div>
        </div>
    </section>

    <section class="statement-band"><div class="site-container reveal"><span aria-hidden="true">“</span><p><?= k9e($copy['closing']) ?></p></div></section>
</main>
<?php require __DIR__ . '/footer.php'; ?>
