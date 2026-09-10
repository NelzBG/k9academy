<?php
$pageKey = 'contact';
require __DIR__ . '/site.php';

require __DIR__ . '/head.php';
require __DIR__ . '/header.php';

$copy = $lang === 'bg' ? [
    'kicker' => 'Свържете се с нас',
    'title' => 'Разкажете ни<br><em>как живее кучето ви.</em>',
    'lead' => 'Проблемът не започва и не свършва с една команда. Опишете ежедневието, реакциите и целите, за да насочим разговора правилно.',
    'phone' => 'Директен телефон',
    'facebook' => 'Последвайте работата ни',
    'response' => 'Какво следва',
    'response_copy' => 'След запитването обсъждаме случая, преценяваме дали е нужна първоначална оценка и предлагаме подходящ формат.',
    'no_address' => 'Локацията и часът за тренировка се уточняват директно според избраната програма.',
] : [
    'kicker' => 'Contact us',
    'title' => 'Tell us<br><em>how your dog lives.</em>',
    'lead' => 'A behaviour problem does not begin or end with one command. Describe the daily routine, reactions and goals so we can guide the conversation properly.',
    'phone' => 'Direct phone',
    'facebook' => 'Follow our work',
    'response' => 'What happens next',
    'response_copy' => 'After the enquiry we discuss the case, decide whether an initial assessment is needed and suggest a suitable format.',
    'no_address' => 'Training location and time are agreed directly according to the selected programme.',
];
?>
<main id="main-content">
    <section class="contact-hero">
        <div class="site-container contact-hero-grid">
            <div class="contact-page-copy reveal is-visible"><p class="eyebrow eyebrow-acid"><?= k9e($copy['kicker']) ?></p><h1><?= $copy['title'] ?></h1><p><?= k9e($copy['lead']) ?></p></div>
            <div class="contact-photo reveal is-visible"><img src="assets/images/training-field.webp" srcset="<?= k9e(k9_image_srcset('training-field.webp')) ?>" sizes="(min-width: 56rem) 42vw, calc(100vw - 2rem)" width="1600" height="1068" alt="<?= $lang === 'bg' ? 'Треньор и куче по време на K9 полева работа' : 'Trainer and dog during K9 fieldwork' ?>" fetchpriority="high" decoding="async"></div>
        </div>
    </section>

    <section class="contact-info-section">
        <div class="site-container contact-info-grid">
            <a class="contact-info-card reveal" href="tel:+359892360550"><span>01</span><small><?= k9e($copy['phone']) ?></small><strong>+359 892 360 550</strong><b aria-hidden="true"><?= k9_icon('arrow','k9-icon-up') ?></b></a>
            <a class="contact-info-card reveal" href="https://www.facebook.com/k9academybg/" target="_blank" rel="noopener"><span>02</span><small><?= k9e($copy['facebook']) ?></small><strong>K9AcademyBG</strong><b aria-hidden="true"><?= k9_icon('arrow','k9-icon-up') ?></b></a>
            <article class="contact-info-card reveal"><span>03</span><small><?= k9e($copy['response']) ?></small><p><?= k9e($copy['response_copy']) ?></p></article>
        </div>
        <div class="site-container location-note reveal"><span aria-hidden="true">◎</span><p><?= k9e($copy['no_address']) ?></p></div>
    </section>
</main>
<?php require __DIR__ . '/footer.php'; ?>
