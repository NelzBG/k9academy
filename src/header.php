<?php /** Shared mobile-first navigation. */ ?>
<a class="skip-link" href="#main-content"><?= k9e(k9t('skip')) ?></a>
<header class="site-header k9-site-header" data-site-header>
    <div class="k9-header-glass">

    <div class="site-container k9-header-row">
        <a class="brand k9-brand" href="<?= k9e(k9_url('index.php')) ?>" aria-label="K9 Academy — <?= k9e(k9t('nav_home')) ?>">
            <?= k9_icon('logo','k9-logo') ?>
            <span class="brand-type"><strong>ACADEMY</strong></span>
        </a>
        <nav class="desktop-nav" aria-label="<?= k9e(k9t('footer_explore')) ?>">
            <?php foreach ($navItems as $key => [$file, $labelKey]): ?><a href="<?= k9e(k9_url($file)) ?>" <?= k9_active($key) === 'true' ? 'aria-current="page"' : '' ?>><?= k9e(k9t($labelKey)) ?></a><?php endforeach; ?>
        </nav>
        <div class="header-actions">
            <div class="language-switch" aria-label="<?= k9e(k9t('language')) ?>">
                <a href="<?= k9e(k9_url($currentFile,'bg')) ?>" lang="bg" hreflang="bg" aria-label="Български" aria-current="<?= $lang === 'bg' ? 'true' : 'false' ?>" data-language-link="bg">BG</a>
                <a href="<?= k9e(k9_url($currentFile,'en')) ?>" lang="en" hreflang="en" aria-label="English" aria-current="<?= $lang === 'en' ? 'true' : 'false' ?>" data-language-link="en">EN</a>
            </div>
            <button class="theme-toggle k9-icon-button" type="button" aria-label="<?= k9e(k9t('theme_toggle')) ?>" data-theme-toggle><?= k9_icon('sun','k9-theme-sun') ?><?= k9_icon('moon','k9-theme-moon') ?></button>
            <a class="k9-button k9-button-acid k9-header-cta" href="<?= k9e(k9_url('contact.php')) ?>"><?= k9e(k9t('contact_cta')) ?><?= k9_icon('arrow') ?></a>
            <button class="menu-toggle k9-menu-toggle k9-icon-button" type="button" aria-expanded="false" aria-controls="mobile-navigation" aria-label="<?= k9e(k9t('menu_open')) ?>" data-open-label="<?= k9e(k9t('menu_open')) ?>" data-close-label="<?= k9e(k9t('menu_close')) ?>" data-menu-toggle><?= k9_icon('menu','k9-menu-image') ?><?= k9_icon('close','k9-close-image') ?></button>
        </div>
    </div>
    </div>
    <div class="mobile-navigation k9-mobile-navigation" id="mobile-navigation" hidden data-mobile-nav>
        <div class="site-container k9-mobile-menu-inner">
            <p class="k9-overline"><?= $lang === 'bg' ? 'ВАШАТА СЛЕДВАЩА СТЪПКА' : 'YOUR NEXT MOVE' ?></p>
            <nav aria-label="<?= $lang === 'bg' ? 'Мобилна навигация' : 'Mobile navigation' ?>">
                <?php foreach ($navItems as $key => [$file,$labelKey]): ?><a href="<?= k9e(k9_url($file)) ?>" <?= k9_active($key) === 'true' ? 'aria-current="page"' : '' ?>><span>0<?= array_search($key,array_keys($navItems),true)+1 ?></span><b><?= k9e(k9t($labelKey)) ?></b><?= k9_icon('arrow') ?></a><?php endforeach; ?>
            </nav>
            <a class="k9-button k9-button-acid" href="<?= k9e(k9_url('contact.php')) ?>"><?= k9e(k9t('contact_cta')) ?><?= k9_icon('arrow') ?></a>
        </div>
    </div>
</header>
