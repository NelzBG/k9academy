<?php
$pageKey = 'terms';
$showFooterContact = false;
require __DIR__ . '/site.php';
require __DIR__ . '/head.php';
require __DIR__ . '/header.php';
$sections = $lang === 'bg' ? [
    ['За сайта', 'Сайтът представя подхода и направленията за обучение на K9 Academy. За конкретни въпроси свържете се чрез Facebook или телефон +359 892 360 550.'],
    ['Обучителни услуги', 'Форматът, графикът, условията и цената на обучение се уточняват директно след обсъждане на случая. Съдържанието в сайта е обща информация и не гарантира конкретен резултат.'],
    ['Запитвания и записване', 'Контактът с нас не потвърждава автоматично записване. Часът и условията за обучение се потвърждават отделно с K9 Academy преди започване на услугата.'],
    ['K9 Shop', 'Страницата K9 Shop препраща към отделен външен уебсайт. Неговите условия, наличности, плащания и доставки се управляват отделно.'],
    ['Отговорно участие', 'Предоставете вярна информация за здравето и поведението на кучето и следвайте инструкциите на треньора за безопасност. При въпроси обсъдете подходящия формат преди посещението.']
] : [
    ['About this website', 'This website presents the approach and training programmes of K9 Academy. For specific questions, contact us through Facebook or call +359 892 360 550.'],
    ['Training services', 'The format, schedule, terms and price are agreed directly after discussing the case. Website content is general information and does not guarantee a particular result.'],
    ['Enquiries and booking', 'Contacting us does not automatically confirm a booking. Training appointments and service terms are confirmed separately with K9 Academy before the service begins.'],
    ['K9 Shop', 'The K9 Shop page links to a separate external website. Its terms, availability, payments and delivery are managed independently.'],
    ['Responsible participation', 'Provide accurate information about your dog’s health and behaviour and follow the trainer’s safety instructions. Discuss any questions and the appropriate training format before attending.']
];
$sections[] = $lang === 'bg'
    ? ['Дигитални ръководства', 'Покупката е еднократна и включва описаните PDF издания на български и английски за лично ползване. Обучителни сесии не са включени. Цената и общата сума са показани преди плащане. Stripe обработва плащането чрез търговския акаунт Hondpro. След потвърдено плащане файловете могат да се изтеглят чрез защитени линкове и се изпращат на имейла от покупката. Линковете са валидни 30 дни; изтеглените файлове могат да бъдат запазени. За проблем с доставка или въпрос относно покупка пишете на mail.k9shop@gmail.com. Приложимите законови права остават в сила.']
    : ['Digital guides', 'A one-time purchase includes the described Bulgarian and English PDF editions for personal use. Training sessions are not included. The price and total are shown before payment. Stripe processes payment through the Hondpro merchant account. After confirmed payment, files are available through protected download links and sent to the checkout email. Links are valid for 30 days; downloaded files can be kept. For delivery problems or purchase questions, contact mail.k9shop@gmail.com. Applicable statutory rights remain in effect.'];
$contextualLinks = [
    0 => ['lead' => ['bg' => 'За въпроси се свържете чрез', 'en' => 'For questions, contact us through'], 'items' => [['contact.php', 'страницата за контакт', 'the contact page']]],
    1 => ['lead' => ['bg' => 'Вижте', 'en' => 'See'], 'items' => [['services.php', 'програмите', 'the programmes'], ['training.php', 'подхода към обучението', 'the training approach']]],
    3 => ['lead' => ['bg' => 'Посетете', 'en' => 'Visit'], 'items' => [['webshop.php', 'страницата K9 Shop', 'the K9 Shop page']]],
    5 => ['lead' => ['bg' => 'Вижте', 'en' => 'See'], 'items' => [['ebooks.php', 'дигиталните ръководства', 'digital guides'], ['privacy.php', 'политиката за поверителност', 'the privacy policy']]],
];
$title = $lang === 'bg' ? 'Общи условия' : 'Terms and conditions';
$intro = $lang === 'bg' ? 'Информация за сайта, запитванията и записването за обучение.' : 'Information about this website, enquiries and arranging training.';
?>
<main id="main-content" class="legal-page">
    <header class="legal-hero"><div class="site-container"><p class="eyebrow eyebrow-acid">K9 / Legal</p><h1><?= k9e($title) ?></h1><p><?= k9e($intro) ?></p></div></header>
    <div class="site-container legal-layout">
        <nav class="legal-index" aria-label="<?= k9e($title) ?>"><?php foreach ($sections as $index => [$heading]): ?><a href="#term-<?= $index + 1 ?>"><span>0<?= $index + 1 ?></span><?= k9e($heading) ?></a><?php endforeach; ?></nav>
        <article class="legal-content"><?php foreach ($sections as $index => [$heading, $body]): ?><section id="term-<?= $index + 1 ?>"><span>0<?= $index + 1 ?></span><h2><?= k9e($heading) ?></h2><p><?= k9e($body) ?><?php if (isset($contextualLinks[$index])): $links = $contextualLinks[$index]; ?> <?= k9e($links['lead'][$lang]) ?><?php foreach ($links['items'] as $linkIndex => [$target, $bgLabel, $enLabel]): ?><?= $linkIndex > 0 ? ($lang === 'bg' ? ' и ' : ' and ') : ' ' ?><a href="<?= k9e(k9_url($target)) ?>"><?= k9e($lang === 'bg' ? $bgLabel : $enLabel) ?></a><?php endforeach; ?>.<?php endif; ?></p></section><?php if ($index === 1) require __DIR__ . '/callback-section.php'; ?><?php endforeach; ?></article>
    </div>

</main>
<?php require __DIR__ . '/footer.php'; ?>
