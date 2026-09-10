<?php
require_once __DIR__ . '/ui.php';
/** @var string $lang */
/** @var string $pageTitle */
/** @var string $pageDescription */
/** @var string $assetVersion */
?>
<!doctype html>
<html lang="<?= k9e($lang) ?>" class="scheme-light dark:scheme-dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#11120f">
    <meta name="robots" content="<?= $isPreview ? 'noindex, nofollow' : 'index, follow, max-image-preview:large' ?>">
    <link rel="canonical" href="<?= k9e($production['origin'] . k9_url($currentFile)) ?>">
    <link rel="alternate" hreflang="bg" href="<?= k9e($production['origin'] . k9_url($currentFile, 'bg')) ?>">
    <link rel="alternate" hreflang="en" href="<?= k9e($production['origin'] . k9_url($currentFile, 'en')) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= k9e($production['origin'] . k9_url($currentFile, 'bg')) ?>">
    <meta name="description" content="<?= k9e($pageDescription) ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= k9e($pageTitle) ?>">
    <meta property="og:description" content="<?= k9e($pageDescription) ?>">
    <meta property="og:site_name" content="K9 Academy">
    <meta property="og:url" content="<?= k9e($production['origin'] . k9_url($currentFile)) ?>">
    <meta property="og:image" content="<?= k9e($production['origin']) ?>/assets/video/k9-hero-poster-20260905.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:locale" content="<?= $lang === 'bg' ? 'bg_BG' : 'en_GB' ?>">
    <title><?= k9e($pageTitle) ?></title>
    <script type="application/ld+json"><?= json_encode(['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => 'K9 Academy', 'url' => $production['origin'] . '/', 'logo' => $production['origin'] . '/assets/images/brand-20260905/logo.webp', 'telephone' => '+359892360550', 'sameAs' => ['https://www.facebook.com/k9academybg/']], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>
    <script>
        (() => {
            let stored = null;
            try { stored = localStorage.getItem('k9-theme'); } catch {}
            const dark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.dataset.theme = dark ? 'dark' : 'light';
        })();
    </script>
    <link rel="preload" href="assets/css/site.css?v=<?= k9e($assetVersion) ?>" as="style">
    <link rel="stylesheet" href="assets/css/site.css?v=<?= k9e($assetVersion) ?>">
    <link rel="stylesheet" href="assets/css/interface-20260906-glass.css">
    <link rel="icon" type="image/webp" href="assets/images/brand-20260905/logo.webp">
    <script type="importmap">
    {
        "imports": {
            "three": "./assets/js/vendor/three/build/three.module.min.js",
            "three/addons/": "./assets/js/vendor/three/examples/jsm/"
        }
    }
    </script>
</head>
<body class="bg-stone-50 text-k9-ink antialiased dark:bg-k9-ink dark:text-stone-100">
