<?php
$pageKey = 'privacy';
$showFooterContact = false;
require __DIR__ . '/site.php';
require __DIR__ . '/head.php';
require __DIR__ . '/header.php';
$privacyContact = $production['email'] ?: '+359 892 360 550';
$sections = $lang === 'bg' ? [
    ['Кой отговаря на запитванията', 'K9 Academy използва информацията, която изпращате, за да отговори на въпросите ви и да обсъди поисканото обучение. За въпроси относно вашите данни: ' . $privacyContact . '.'],
    ['Данни във формата', 'Формата изисква име, телефон, избрано направление, съобщение и съгласие за отговор. Имейлът и информацията за кучето са по избор. Не включвайте чувствителна информация, която не е необходима за запитването.'],
    ['Изпращане и обработка', 'Запитването се изпраща по защитена връзка до услугата за контакт и се препраща към K9 Academy. Данните се използват за отговор и последваща комуникация по заявката. Свържете се с нас, ако искате корекция или изтриване на изпратената информация.'],
    ['Локални настройки', 'Езикът, светлата или тъмната тема, изборът за бисквитки и позицията на контактните бутони се пазят в localStorage на устройството ви. Можете да ги премахнете чрез настройките на браузъра.'],
    ['Хостинг и външни услуги', 'Сайтът се хоства от GitHub Pages. Доставчиците на хостинг и услугата за контакт могат да обработват технически данни за обслужване на заявките и предотвратяване на злоупотреба. Сайтът не зарежда рекламни или аналитични скриптове.'],
    ['Външни връзки', 'Когато отворите Facebook, WhatsApp, Viber, K9 Shop или страницата на 3D модела, се прилагат политиките на съответната услуга.']
] : [
    ['Who handles enquiries', 'K9 Academy uses the information you send to answer your questions and discuss the training you request. For questions about your data, contact ' . $privacyContact . '.'],
    ['Information in the form', 'The form requires your name, phone number, selected programme, message and permission to respond. Email and details about your dog are optional. Please avoid sensitive information that is unnecessary for your enquiry.'],
    ['Delivery and handling', 'Your enquiry is sent over an encrypted connection to the contact service and forwarded to K9 Academy. The details are used to respond and follow up on your request. Contact us to request correction or deletion of information you have submitted.'],
    ['Local preferences', 'Language, light or dark theme, cookie choice and contact-button positions are saved in localStorage on your device. You can remove them through your browser settings.'],
    ['Hosting and external services', 'GitHub Pages hosts this website. Hosting and contact-service providers may process technical data to serve requests and prevent abuse. This site does not load advertising or analytics scripts.'],
    ['External links', 'When you open Facebook, WhatsApp, Viber, K9 Shop or the 3D model page, the respective service policies apply.']
];
$title = $lang === 'bg' ? 'Поверителност' : 'Privacy';
$intro = $lang === 'bg' ? 'Как обработваме запитванията и предпочитанията ви в сайта.' : 'How we handle your enquiries and website preferences.';
?>
<main id="main-content" class="legal-page">
    <header class="legal-hero"><div class="site-container"><p class="eyebrow eyebrow-acid">K9 / Privacy</p><h1><?= k9e($title) ?></h1><p><?= k9e($intro) ?></p></div></header>
    <div class="site-container legal-layout">
        <nav class="legal-index" aria-label="<?= k9e($title) ?>"><?php foreach ($sections as $index => [$heading]): ?><a href="#privacy-<?= $index + 1 ?>"><span>0<?= $index + 1 ?></span><?= k9e($heading) ?></a><?php endforeach; ?></nav>
        <article class="legal-content"><?php foreach ($sections as $index => [$heading, $body]): ?><section id="privacy-<?= $index + 1 ?>"><span>0<?= $index + 1 ?></span><h2><?= k9e($heading) ?></h2><p><?= k9e($body) ?></p></section><?php endforeach; ?></article>
    </div>
</main>
<?php require __DIR__ . '/footer.php'; ?>
