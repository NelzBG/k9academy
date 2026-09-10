<?php
$pageKey = 'terms';
$showFooterContact = false;
require __DIR__ . '/site.php';
require __DIR__ . '/head.php';
require __DIR__ . '/header.php';
$sections = $lang === 'bg' ? [
    ['За сайта', 'Сайтът представя подхода и направленията за обучение на K9 Academy. За конкретни въпроси използвайте контактната форма или телефон +359 892 360 550.'],
    ['Обучителни услуги', 'Форматът, графикът, условията и цената на обучение се уточняват директно след обсъждане на случая. Съдържанието в сайта е обща информация и не гарантира конкретен резултат.'],
    ['Запитвания и записване', 'Изпращането на формата е запитване. Часът и условията за обучение се потвърждават отделно с K9 Academy преди започване на услугата.'],
    ['K9 Shop', 'Страницата K9 Shop препраща към отделен външен уебсайт. Неговите условия, наличности, плащания и доставки се управляват отделно.'],
    ['Отговорно участие', 'Предоставете вярна информация за здравето и поведението на кучето и следвайте инструкциите на треньора за безопасност. При въпроси обсъдете подходящия формат преди посещението.']
] : [
    ['About this website', 'This website presents the approach and training programmes of K9 Academy. For specific questions, use the enquiry form or call +359 892 360 550.'],
    ['Training services', 'The format, schedule, terms and price are agreed directly after discussing the case. Website content is general information and does not guarantee a particular result.'],
    ['Enquiries and booking', 'Submitting the form sends an enquiry. Training appointments and service terms are confirmed separately with K9 Academy before the service begins.'],
    ['K9 Shop', 'The K9 Shop page links to a separate external website. Its terms, availability, payments and delivery are managed independently.'],
    ['Responsible participation', 'Provide accurate information about your dog’s health and behaviour and follow the trainer’s safety instructions. Discuss any questions and the appropriate training format before attending.']
];
$title = $lang === 'bg' ? 'Общи условия' : 'Terms and conditions';
$intro = $lang === 'bg' ? 'Информация за сайта, запитванията и записването за обучение.' : 'Information about this website, enquiries and arranging training.';
?>
<main id="main-content" class="legal-page">
    <header class="legal-hero"><div class="site-container"><p class="eyebrow eyebrow-acid">K9 / Legal</p><h1><?= k9e($title) ?></h1><p><?= k9e($intro) ?></p></div></header>
    <div class="site-container legal-layout">
        <nav class="legal-index" aria-label="<?= k9e($title) ?>"><?php foreach ($sections as $index => [$heading]): ?><a href="#term-<?= $index + 1 ?>"><span>0<?= $index + 1 ?></span><?= k9e($heading) ?></a><?php endforeach; ?></nav>
        <article class="legal-content"><?php foreach ($sections as $index => [$heading, $body]): ?><section id="term-<?= $index + 1 ?>"><span>0<?= $index + 1 ?></span><h2><?= k9e($heading) ?></h2><p><?= k9e($body) ?></p></section><?php endforeach; ?></article>
    </div>
</main>
<?php require __DIR__ . '/footer.php'; ?>
