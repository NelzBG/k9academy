<?php
$pageKey = 'privacy';
$showFooterContact = false;
require __DIR__ . '/site.php';
require __DIR__ . '/head.php';
require __DIR__ . '/header.php';
$privacyContact = $production['email'] ?: '+359 892 360 550';
$sections = $lang === 'bg' ? [
    ['Кой отговаря на запитванията', 'K9 Academy използва информацията, която изпращате, за да отговори на въпросите ви и да обсъди поисканото обучение. За въпроси относно вашите данни: ' . $privacyContact . '.'],
    ['Данни във формата', 'Формата изисква име, телефон, избрано направление, съобщение и съгласие за отговор. Имейлът е задължителен за потвърждение, а информацията за кучето е по избор. Не включвайте чувствителна информация, която не е необходима за запитването.'],
    ['Изпращане и обработка', 'Запитването се изпраща по защитена връзка до услугата за контакт и се препраща към mail.k9shop@gmail.com. Потвърждение се изпраща и на посочения от вас имейл. Данните се използват за отговор и последваща комуникация по заявката. Свържете се с нас, ако искате корекция или изтриване на изпратената информация.'],
    ['Локални настройки', 'Езикът, светлата или тъмната тема, изборът за бисквитки и позицията на контактните бутони се пазят в localStorage на устройството ви. Можете да ги премахнете чрез настройките на браузъра.'],
    ['Хостинг и външни услуги', 'Сайтът се хоства от GitHub Pages. Доставчиците на хостинг и услугата за контакт могат да обработват технически данни за обслужване на заявките и предотвратяване на злоупотреба. Сайтът не зарежда рекламни или аналитични скриптове.'],
    ['Външни връзки', 'Когато отворите Facebook Messenger, Viber, K9 Shop или страницата на 3D модела, се прилагат политиките на съответната услуга.']
] : [
    ['Who handles enquiries', 'K9 Academy uses the information you send to answer your questions and discuss the training you request. For questions about your data, contact ' . $privacyContact . '.'],
    ['Information in the form', 'The form requires your name, phone number, selected programme, message and permission to respond. Email is required for your confirmation; details about your dog are optional. Please avoid sensitive information that is unnecessary for your enquiry.'],
    ['Delivery and handling', 'Your enquiry is sent over an encrypted connection to the contact service and forwarded to mail.k9shop@gmail.com. A confirmation is also sent to the email address you provide. The details are used to respond and follow up on your request. Contact us to request correction or deletion of information you have submitted.'],
    ['Local preferences', 'Language, light or dark theme, cookie choice and contact-button positions are saved in localStorage on your device. You can remove them through your browser settings.'],
    ['Hosting and external services', 'GitHub Pages hosts this website. Hosting and contact-service providers may process technical data to serve requests and prevent abuse. This site does not load advertising or analytics scripts.'],
    ['External links', 'When you open Facebook Messenger, Viber, K9 Shop or the 3D model page, the respective service policies apply.']
];
if (($production['contactMode'] ?? 'online') === 'direct') {
    $sections[1] = $lang === 'bg'
        ? ['Контакт', 'Онлайн формата не е активна и сайтът не събира данни чрез нея. Можете да се свържете с K9 Academy по телефон или чрез Facebook.']
        : ['Contact', 'The online form is inactive and this website does not collect information through it. You can contact K9 Academy by phone or through Facebook.'];
    $sections[2] = $lang === 'bg'
        ? ['Обработка на запитвания', 'Информацията, която споделяте директно с нас, се използва за отговор на запитването. За корекция или изтриване се свържете с K9 Academy. За съобщения във Facebook се прилага и политиката на Facebook.']
        : ['Handling enquiries', 'Information you share directly with us is used to respond to your enquiry. Contact K9 Academy to request correction or deletion. Facebook messages are also subject to Facebook’s privacy policy.'];
    $sections[4] = $lang === 'bg'
        ? ['Хостинг', 'GitHub Pages хоства този сайт и може да обработва технически данни за обслужване на заявките. Сайтът не зарежда рекламни или аналитични скриптове.']
        : ['Hosting', 'GitHub Pages hosts this website and may process technical data to serve requests. This site does not load advertising or analytics scripts.'];
}
$sections[] = $lang === 'bg'
    ? ['Видео от YouTube', 'Външният видео плейър се зарежда след разрешение за видео или натискане на бутона за гледане. Тогава YouTube получава техническа информация за заявката, включително IP адрес. Използваме домейна youtube-nocookie.com. За отказ изберете „Само необходимите“ в настройките за бисквитки.']
    : ['YouTube video', 'The external video player loads after media permission or use of the watch button. YouTube then receives technical request information, including your IP address. We use youtube-nocookie.com. To decline background video, choose Essential only in cookie settings.'];
$sections[] = $lang === 'bg'
    ? ['Покупки и доставка на PDF', 'Stripe обработва платежните данни. Сайтът не получава номера на банковата ви карта. Услугата за доставка обработва имейла, избраното издание и референцията за потвърденото плащане, за да изпрати файловете и да защити изтеглянето. Референциите за поръчки и статусът на доставка се пазят в частно хранилище на доставчика на услугата. За въпроси относно данните от покупката пишете на mail.k9shop@gmail.com.']
    : ['Purchases and PDF delivery', 'Stripe handles payment details. This website does not receive your full card number. The delivery service processes the email, selected guide and verified payment reference to send the files and protect downloads. Order references and delivery status are kept in private storage on the service host. Contact mail.k9shop@gmail.com with questions about purchase data.'];
$sections[] = $lang === 'bg'
    ? ['Заявка за обратно обаждане', 'Кратката форма изисква само телефонен номер. Номерът, езикът и страницата, от която е изпратена заявката, се препращат по защитена връзка към същия адрес за контакт: mail.k9shop@gmail.com. Използваме номера, за да отговорим на поисканото обаждане. Не е необходим имейл и не се изпраща клиентско потвърждение по имейл.']
    : ['Callback requests', 'The short form requires only a phone number. The number, language and submitting page are sent over an encrypted connection to the same contact address: mail.k9shop@gmail.com. We use the number to respond to the call you requested. No email is required and no customer confirmation email is sent.'];
$title = $lang === 'bg' ? 'Поверителност' : 'Privacy';
$intro = $lang === 'bg' ? 'Как обработваме запитванията и предпочитанията ви в сайта.' : 'How we handle your enquiries and website preferences.';
?>
<main id="main-content" class="legal-page">
    <header class="legal-hero"><div class="site-container"><p class="eyebrow eyebrow-acid">K9 / Privacy</p><h1><?= k9e($title) ?></h1><p><?= k9e($intro) ?></p></div></header>
    <div class="site-container legal-layout">
        <nav class="legal-index" aria-label="<?= k9e($title) ?>"><?php foreach ($sections as $index => [$heading]): ?><a href="#privacy-<?= $index + 1 ?>"><span>0<?= $index + 1 ?></span><?= k9e($heading) ?></a><?php endforeach; ?></nav>
        <article class="legal-content"><?php foreach ($sections as $index => [$heading, $body]): ?><section id="privacy-<?= $index + 1 ?>"><span>0<?= $index + 1 ?></span><h2><?= k9e($heading) ?></h2><p><?= k9e($body) ?></p></section><?php if ($index === 1) require __DIR__ . '/callback-section.php'; ?><?php endforeach; ?></article>
    </div>

</main>
<?php require __DIR__ . '/footer.php'; ?>
