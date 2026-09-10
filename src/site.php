<?php
declare(strict_types=1);

$pageKey = $pageKey ?? 'home';
$requestedLanguage = strtolower((string) ($_GET['lang'] ?? 'bg'));
$lang = in_array($requestedLanguage, ['bg', 'en'], true) ? $requestedLanguage : 'bg';
$currentFile = basename((string) ($_SERVER['SCRIPT_NAME'] ?? 'index.php'));
$assetVersion = '1.3.0';
$production = json_decode(file_get_contents(__DIR__ . '/production.json'), true, 512, JSON_THROW_ON_ERROR);
$pageRoutes = json_decode(file_get_contents(__DIR__ . '/routes.json'), true, 512, JSON_THROW_ON_ERROR);
$isPreview = getenv('K9_PREVIEW') === '1';

$strings = [
    'bg' => [
        'brand_tagline' => 'Обучение на хора и кучета',
        'skip' => 'Към основното съдържание',
        'nav_home' => 'Начало',
        'nav_about' => 'За нас',
        'nav_services' => 'Услуги',
        'nav_shop' => 'K9 Shop',
        'nav_training' => 'Обучение',
        'nav_contact' => 'Контакти',
        'menu_open' => 'Отвори менюто',
        'menu_close' => 'Затвори менюто',
        'theme_toggle' => 'Смени светла и тъмна тема',
        'language' => 'Език',
        'contact_cta' => 'Започнете обучение',
        'quick_contact_eyebrow' => 'Първата команда е разговор',
        'quick_contact_title' => 'Разкажете ни за вашето куче.',
        'quick_contact_copy' => 'Свържете се с нас. Ще обсъдим темперамента, средата и целите ви, преди да предложим посока.',
        'form_name' => 'Вашето име',
        'form_phone' => 'Телефон',
        'form_email' => 'Имейл',
        'form_dog' => 'Име, порода и възраст на кучето',
        'form_service' => 'Интересува ме',
        'form_message' => 'Какво искате да промените?',
        'form_choose' => 'Изберете направление',
        'form_obedience' => 'Послушание',
        'form_social' => 'Социализация',
        'form_correction' => 'Корекция на поведение',
        'form_protection' => 'Защита и охрана',
        'form_consultation' => 'Консултация',
        'form_consent' => 'Съгласен/на съм данните ми да бъдат използвани за отговор на това запитване.',
        'form_submit' => 'Изпрати запитване',
        'form_note' => 'Използваме данните ви, за да отговорим на запитването.',
        'footer_explore' => 'Разгледайте',
        'footer_contact' => 'Контакт',
        'footer_language' => 'Език и изглед',
        'footer_rights' => 'Всички права запазени.',
        'terms' => 'Общи условия',
        'privacy' => 'Поверителност',
        'cookies' => 'Настройки за бисквитки',
        'cookie_title' => 'Вашият избор. Без скрити команди.',
        'cookie_copy' => 'Сайтът запазва локално предпочитанията ви за език, тема и контактни бутони. Няма рекламни или аналитични бисквитки.',
        'cookie_essential' => 'Само необходимите',
        'cookie_accept' => 'Приемам',
        'channel_pending' => 'Ще активираме канала, когато бъде добавен номер.',
        'drag_hint' => 'Два отделни бутона: плъзнете, за да ги преместите',
        'drag_instructions' => 'Плъзнете всеки бутон отделно. Използвайте стрелките за преместване или Delete, за да го скриете.',
        'drop_hide' => 'Пуснете, за да скриете',
        'drop_hide_note' => 'Стрелката отстрани го връща',
        'restore_channels' => 'Покажи скритите бутони',
        'channel_hidden' => 'Бутонът е скрит. Използвайте страничната стрелка, за да го върнете.',
        'channels_restored' => 'Скритите бутони са върнати.',
        'facebook' => 'K9 Academy във Facebook',
        'phone_label' => 'Телефон',

    ],
    'en' => [
        'brand_tagline' => 'Training people and dogs',
        'skip' => 'Skip to main content',
        'nav_home' => 'Home',
        'nav_about' => 'About us',
        'nav_services' => 'Services',
        'nav_shop' => 'K9 Shop',
        'nav_training' => 'Training',
        'nav_contact' => 'Contact',
        'menu_open' => 'Open menu',
        'menu_close' => 'Close menu',
        'theme_toggle' => 'Switch light and dark theme',
        'language' => 'Language',
        'contact_cta' => 'Start training',
        'quick_contact_eyebrow' => 'The first command is a conversation',
        'quick_contact_title' => 'Tell us about your dog.',
        'quick_contact_copy' => 'Get in touch. We will discuss temperament, environment and goals before suggesting a direction.',
        'form_name' => 'Your name',
        'form_phone' => 'Phone',
        'form_email' => 'Email',
        'form_dog' => 'Dog name, breed and age',
        'form_service' => 'I am interested in',
        'form_message' => 'What would you like to change?',
        'form_choose' => 'Choose a programme',
        'form_obedience' => 'Obedience',
        'form_social' => 'Socialisation',
        'form_correction' => 'Behaviour correction',
        'form_protection' => 'Protection and guarding',
        'form_consultation' => 'Consultation',
        'form_consent' => 'I agree that my details may be used to answer this enquiry.',
        'form_submit' => 'Send enquiry',
        'form_note' => 'We use your details to respond to your enquiry.',
        'footer_explore' => 'Explore',
        'footer_contact' => 'Contact',
        'footer_language' => 'Language and view',
        'footer_rights' => 'All rights reserved.',
        'terms' => 'Terms and conditions',
        'privacy' => 'Privacy',
        'cookies' => 'Cookie settings',
        'cookie_title' => 'Your choice. No hidden commands.',
        'cookie_copy' => 'The site saves your language, theme and contact-button preferences locally. There are no advertising or analytics cookies.',
        'cookie_essential' => 'Essential only',
        'cookie_accept' => 'Accept',
        'channel_pending' => 'We will activate this channel when a number is added.',
        'drag_hint' => 'Two separate buttons: drag either one to reposition it',
        'drag_instructions' => 'Drag each button separately. Use the arrow keys to reposition it or Delete to hide it.',
        'drop_hide' => 'Release to hide',
        'drop_hide_note' => 'The edge arrow brings it back',
        'restore_channels' => 'Show hidden contact buttons',
        'channel_hidden' => 'The button is hidden. Use the edge arrow to restore it.',
        'channels_restored' => 'The hidden contact buttons are restored.',
        'facebook' => 'K9 Academy on Facebook',
        'phone_label' => 'Phone',

    ],
];

$pageMeta = [
    'home' => [
        'bg' => ['K9 Academy — Обучение на хора и кучета', 'Балансирано обучение по послушание, социализация, корекция и защита.'],
        'en' => ['K9 Academy — Training people and dogs', 'Balanced obedience, socialisation, behaviour correction and protection training.'],
    ],
    'about' => [
        'bg' => ['За K9 Academy', 'Философия, подход и принципи за стабилна връзка между човек и куче.'],
        'en' => ['About K9 Academy', 'Philosophy, approach and principles for a stable relationship between people and dogs.'],
    ],
    'services' => [
        'bg' => ['Услуги — K9 Academy', 'Индивидуални и групови програми за обучение и поведение.'],
        'en' => ['Services — K9 Academy', 'Individual and group programmes for training and behaviour.'],
    ],
    'shop' => [
        'bg' => ['K9 Shop — K9 Academy', 'Специализирани продукти, храна, лакомства и помощни средства за обучение.'],
        'en' => ['K9 Shop — K9 Academy', 'Specialist food, rewards and practical training equipment.'],
    ],
    'training' => [
        'bg' => ['Как тренираме — K9 Academy', 'От първата консултация до уверено поведение в реална среда.'],
        'en' => ['How we train — K9 Academy', 'From the first consultation to confident behaviour in real-world environments.'],
    ],
    'contact' => [
        'bg' => ['Контакти — K9 Academy', 'Свържете се с K9 Academy и разкажете за вашето куче.'],
        'en' => ['Contact — K9 Academy', 'Contact K9 Academy and tell us about your dog.'],
    ],
    'terms' => [
        'bg' => ['Общи условия — K9 Academy', 'Информация за използването на сайта и услугите на K9 Academy.'],
        'en' => ['Terms and conditions — K9 Academy', 'Information about using the K9 Academy website and services.'],
    ],
    'privacy' => [
        'bg' => ['Поверителност — K9 Academy', 'Как K9 Academy обработва запитванията и настройките на сайта.'],
        'en' => ['Privacy — K9 Academy', 'How K9 Academy handles enquiries and website preferences.'],
    ],
];

$navItems = [
    'home' => ['index.php', 'nav_home'],
    'about' => ['aboutus.php', 'nav_about'],
    'services' => ['services.php', 'nav_services'],
    'shop' => ['webshop.php', 'nav_shop'],
    'training' => ['training.php', 'nav_training'],
    'contact' => ['contact.php', 'nav_contact'],
];

function k9e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function k9t(string $key): string
{
    global $strings, $lang;
    return $strings[$lang][$key] ?? $key;
}

function k9_url(string $file, ?string $targetLanguage = null, string $anchor = ''): string
{
    global $lang, $pageRoutes;
    $selectedLanguage = $targetLanguage ?? $lang;
    if (!array_key_exists($file, $pageRoutes)) throw new InvalidArgumentException('Unknown page: ' . $file);
    return '/' . ($selectedLanguage === 'en' ? 'en/' : '') . $pageRoutes[$file] . $anchor;
}

function k9_channel_url(string $channel): string
{
    global $production;
    $number = preg_replace('/\\D/', '', $production[$channel] ?? '');
    if ($number === '') return '';
    return $channel === 'whatsapp' ? 'https://wa.me/' . $number : 'viber://chat?number=%2B' . $number;
}

function k9_active(string $key): string
{
    global $pageKey;
    return $pageKey === $key ? 'true' : 'false';
}

function k9_image_srcset(string $filename): string
{
    $base = pathinfo($filename, PATHINFO_FILENAME);
    return implode(', ', array_map(
        static fn (int $width): string => 'assets/images/' . $base . '-' . $width . '.webp ' . $width . 'w',
        [480, 768, 1200]
    ));
}

$meta = $pageMeta[$pageKey][$lang] ?? $pageMeta['home'][$lang];
$pageTitle = $meta[0];
$pageDescription = $meta[1];
$showFooterContact = $showFooterContact ?? true;
$serverFormStatus = $serverFormStatus ?? '';
