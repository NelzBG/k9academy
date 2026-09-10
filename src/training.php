<?php
$pageKey = 'training';
$hasDogViewer = true;
require __DIR__ . '/site.php';
require __DIR__ . '/head.php';
require __DIR__ . '/header.php';

$copy = $lang === 'bg' ? [
    'kicker' => 'Как тренираме',
    'title' => 'Методът се променя.<br><em>Принципите остават.</em>',
    'lead' => 'Наблюдение, ясен план, точен тайминг и упражнения, които работят извън тренировъчната площадка.',
    'steps_kicker' => 'Процесът',
    'steps_title' => 'Пет стъпки от проблем до навик.',
    'viewer_kicker' => 'Движение и баланс',
    'viewer_title' => 'Разгледайте стойката в 3D.',
    'viewer_copy' => 'Завъртете германската овчарка и пуснете движенията. В реалната работа наблюдаваме същите детайли: център, импулс, концентрация и реакция.',
    'viewer_loading' => 'Подготвяме модела…',
    'faq_kicker' => 'Преди първата сесия',
    'faq_title' => 'Кратки, честни отговори.',
] : [
    'kicker' => 'How we train',
    'title' => 'The method adapts.<br><em>The principles remain.</em>',
    'lead' => 'Observation, a clear plan, precise timing and exercises that work beyond the training field.',
    'steps_kicker' => 'The process',
    'steps_title' => 'Five steps from problem to habit.',
    'viewer_kicker' => 'Movement and balance',
    'viewer_title' => 'Study the posture in 3D.',
    'viewer_copy' => 'Rotate the German Shepherd and trigger its movements. In real sessions we watch the same details: centre, impulse, concentration and response.',
    'viewer_loading' => 'Preparing the model…',
    'faq_kicker' => 'Before the first session',
    'faq_title' => 'Short, honest answers.',
];

$steps = $lang === 'bg' ? [
    ['01', 'Първоначална консултация', 'Изясняваме средата, историята, ежедневието и поведението, което искате да промените.'],
    ['02', 'Индивидуален план', 'Определяме реалистични цели, мотивация, правила и упражнения за конкретния екип.'],
    ['03', 'Ясна основа', 'Изграждаме разбираеми сигнали, контакт с водача и основни команди без излишен шум.'],
    ['04', 'Реални дразнители', 'Пренасяме уменията сред движение, хора, кучета, градски шум и различни дистанции.'],
    ['05', 'Навик за двама', 'Кучето затвърждава поведението, а водачът се научава да поддържа резултата.'],
] : [
    ['01', 'Initial consultation', 'We clarify environment, history, daily routine and the behaviour you want to change.'],
    ['02', 'Tailored plan', 'We set realistic goals, motivation, rules and exercises for the specific team.'],
    ['03', 'Clear foundations', 'We build understandable signals, handler engagement and core commands without unnecessary noise.'],
    ['04', 'Real distractions', 'We transfer the skills around movement, people, dogs, urban noise and changing distances.'],
    ['05', 'A habit for both', 'The dog reinforces the behaviour while the handler learns how to maintain the result.'],
];

$faqs = $lang === 'bg' ? [
    ['Подходящо ли е за всяка порода?', 'Планът започва от темперамента, възрастта, здравето и средата — не от етикета на породата. При специфичен случай първо правим оценка.'],
    ['Колко сесии са нужни?', 'Няма честен универсален брой. След първоначалната консултация може да се определи посока и подходящ ритъм.'],
    ['Работи ли и водачът?', 'Да. Устойчивата промяна изисква човекът да разбира сигналите, момента и правилата също толкова добре, колкото кучето.'],
    ['Може ли дете да участва?', 'Водачи под 18 години участват в присъствието на родител и под наблюдение на треньор.'],
] : [
    ['Is it suitable for every breed?', 'The plan begins with temperament, age, health and environment — not a breed label. Specific cases start with an assessment.'],
    ['How many sessions are needed?', 'There is no honest universal number. The initial consultation helps define the direction and a suitable pace.'],
    ['Does the handler work too?', 'Yes. Lasting change requires the person to understand signals, timing and rules just as well as the dog.'],
    ['Can a child participate?', 'Handlers under 18 participate with a parent and under trainer supervision.'],
];
?>
<main id="main-content">
    <section class="page-hero page-hero-training">
        <div class="page-hero-bg" aria-hidden="true"></div>
        <div class="site-container page-hero-grid">
            <div class="page-hero-copy reveal is-visible"><p class="eyebrow eyebrow-acid"><?= k9e($copy['kicker']) ?></p><h1><?= $copy['title'] ?></h1><p><?= k9e($copy['lead']) ?></p></div>
            <div class="page-hero-media reveal is-visible"><img src="assets/images/training-team.webp" srcset="<?= k9e(k9_image_srcset('training-team.webp')) ?>" sizes="(min-width: 56rem) 42vw, calc(100vw - 2rem)" width="1600" height="1068" alt="<?= $lang === 'bg' ? 'Треньор и куче работят върху K9 препятствие' : 'Trainer and dog working over a K9 obstacle' ?>" fetchpriority="high" decoding="async"><span>TRAINING / 03</span></div>
        </div>
    </section>

    <section class="content-section process-section">
        <div class="site-container">
            <div class="section-heading reveal"><div><p class="eyebrow"><?= k9e($copy['steps_kicker']) ?></p><h2><?= k9e($copy['steps_title']) ?></h2></div></div>
            <ol class="training-timeline">
                <?php foreach ($steps as [$number, $title, $body]): ?><li class="reveal"><span><?= k9e($number) ?></span><div><h3><?= k9e($title) ?></h3><p><?= k9e($body) ?></p></div></li><?php endforeach; ?>
            </ol>
        </div>
    </section>

    <section class="dog-lab training-dog-lab" aria-labelledby="training-dog-title">
        <div class="site-container dog-lab-grid">
            <div class="dog-lab-copy reveal"><p class="eyebrow eyebrow-acid"><?= k9e($copy['viewer_kicker']) ?></p><h2 id="training-dog-title"><?= k9e($copy['viewer_title']) ?></h2><p><?= k9e($copy['viewer_copy']) ?></p><div class="animation-controls"><button type="button" class="is-active" data-animation="idle"><?= $lang === 'bg' ? 'Покой' : 'Idle' ?></button><button type="button" data-animation="play"><?= $lang === 'bg' ? 'Игра' : 'Play' ?></button><button type="button" data-animation="walk"><?= $lang === 'bg' ? 'Ход' : 'Walk' ?></button><button type="button" data-animation="run"><?= $lang === 'bg' ? 'Бяг' : 'Run' ?></button></div></div>
            <div class="dog-stage reveal" data-model="assets/model/german-shepherd.glb" data-dog-stage><canvas aria-label="<?= $lang === 'bg' ? 'Интерактивна анимирана 3D германска овчарка' : 'Interactive animated 3D German Shepherd' ?>"></canvas><div class="dog-stage-grid" aria-hidden="true"></div><div class="model-loading" data-model-loading><span></span><p><?= k9e($copy['viewer_loading']) ?></p></div><a class="model-credit" href="https://retrostylegames.itch.io/german-shepherd-3d-dog-model-free" target="_blank" rel="noopener"><span>3D</span> German Shepherd / RetroStyle Games <?= k9_icon('arrow','k9-icon-up') ?></a></div>
        </div>
    </section>

    <section class="faq-section">
        <div class="site-container faq-grid">
            <div class="reveal"><p class="eyebrow"><?= k9e($copy['faq_kicker']) ?></p><h2><?= k9e($copy['faq_title']) ?></h2></div>
            <div class="faq-list">
                <?php foreach ($faqs as $index => [$question, $answer]): ?><details class="reveal" <?= $index === 0 ? 'open' : '' ?>><summary><?= k9e($question) ?><span aria-hidden="true">+</span></summary><p><?= k9e($answer) ?></p></details><?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/footer.php'; ?>
