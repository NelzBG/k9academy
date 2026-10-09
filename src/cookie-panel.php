<?php /** Initial privacy choices render before the page content. */ ?>
<div class="cookie-wall" data-cookie-wall>
    <div class="cookie-backdrop"></div>
    <section class="cookie-panel" role="dialog" aria-modal="true" aria-labelledby="cookie-title" tabindex="-1" data-cookie-panel>
        <div class="cookie-kicker"><span aria-hidden="true">K9</span> Privacy first</div>
        <h2 id="cookie-title"><?= k9e(k9t('cookie_title')) ?></h2>
        <p><?= k9e(k9t('cookie_copy')) ?></p>
        <div class="cookie-links"><a href="<?= k9e(k9_url('privacy.php')) ?>"><?= k9e(k9t('privacy')) ?></a><a href="<?= k9e(k9_url('terms.php')) ?>"><?= k9e(k9t('terms')) ?></a></div>
        <div class="cookie-actions">
            <button class="button button-ghost" type="button" data-cookie-choice="essential"><?= k9e(k9t('cookie_essential')) ?></button>
            <button class="button button-acid" type="button" data-cookie-choice="accepted"><?= k9e(k9t('cookie_accept')) ?></button>
        </div>
    </section>
</div>
<noscript><style>.cookie-wall{display:none!important}</style></noscript>
