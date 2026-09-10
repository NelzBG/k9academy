<?php
/** @var string $lang */
/** @var string $currentFile */
/** @var string $assetVersion */
/** @var bool $showFooterContact */
?>
<?php if ($showFooterContact): ?>
<section class="contact-deck" id="quick-contact" aria-labelledby="quick-contact-title">
    <div class="site-container contact-deck-grid">
        <div class="contact-deck-copy reveal">
            <p class="eyebrow eyebrow-dark"><?= k9e(k9t('quick_contact_eyebrow')) ?></p>
            <h2 id="quick-contact-title"><?= k9e(k9t('quick_contact_title')) ?></h2>
            <p><?= k9e(k9t('quick_contact_copy')) ?></p>
            <div class="contact-direct">
                <a href="tel:+359892360550"><small><?= k9e(k9t('phone_label')) ?></small><strong>+359 892 360 550</strong></a>
                <a href="https://www.facebook.com/k9academybg/" target="_blank" rel="noopener"><small>Facebook</small><strong>K9AcademyBG <?= k9_icon('arrow','k9-icon-up') ?></strong></a>
            </div>
        </div>
        <?php if (($production['contactMode'] ?? 'online') === 'direct'): ?>
        <div class="contact-form reveal" id="contact-form" data-direct-contact>
            <p class="eyebrow eyebrow-dark">K9 / <?= $lang === 'bg' ? 'Контакт' : 'Contact' ?></p>
            <h3><?= $lang === 'bg' ? 'Да поговорим за вашето куче.' : 'Let’s talk about your dog.' ?></h3>
            <p><?= $lang === 'bg' ? 'Обадете ни се или изпратете съобщение във Facebook, за да обсъдим подходящото обучение.' : 'Call us or send a Facebook message to discuss the right training.' ?></p>
            <div class="form-submit-row">
                <a class="button button-acid" href="tel:+359892360550"><?= $lang === 'bg' ? 'Обадете се' : 'Call K9 Academy' ?> <?= k9_icon('arrow','k9-icon-up') ?></a>
                <a class="button button-ink" href="https://www.facebook.com/k9academybg/" target="_blank" rel="noopener">Facebook <?= k9_icon('arrow','k9-icon-up') ?></a>
            </div>
            <p class="form-note"><?= $lang === 'bg' ? 'Изпращането чрез онлайн форма в момента не е налично.' : 'Online form delivery is currently unavailable.' ?></p>
        </div>
        <?php else: ?>
        <form class="contact-form reveal" id="contact-form" action="<?= k9e($production['enquiryEndpoint'] ?: k9_url('contact.php', $lang, '#contact-form')) ?>" method="post" data-contact-form data-endpoint="<?= k9e($production['enquiryEndpoint']) ?>" novalidate>
            <input type="hidden" name="language" value="<?= k9e($lang) ?>">
            <div hidden aria-hidden="true"><label>Website<input name="website" type="text" tabindex="-1" autocomplete="off"></label></div>
            <div class="form-row">
                <label><span><?= k9e(k9t('form_name')) ?></span><input type="text" name="name" autocomplete="name" maxlength="100" required></label>
                <label><span><?= k9e(k9t('form_phone')) ?></span><input type="tel" name="phone" autocomplete="tel" maxlength="40" required></label>
            </div>
            <div class="form-row">
                <label><span><?= k9e(k9t('form_email')) ?></span><input type="email" name="email" autocomplete="email" maxlength="254"></label>
                <label><span><?= k9e(k9t('form_dog')) ?></span><input type="text" name="dog" autocomplete="off" maxlength="180"></label>
            </div>
            <label>
                <span><?= k9e(k9t('form_service')) ?></span>
                <select name="service" required>
                    <option value=""><?= k9e(k9t('form_choose')) ?></option>
                    <option value="obedience"><?= k9e(k9t('form_obedience')) ?></option>
                    <option value="socialisation"><?= k9e(k9t('form_social')) ?></option>
                    <option value="correction"><?= k9e(k9t('form_correction')) ?></option>
                    <option value="protection"><?= k9e(k9t('form_protection')) ?></option>
                    <option value="consultation"><?= k9e(k9t('form_consultation')) ?></option>
                </select>
            </label>
            <label><span><?= k9e(k9t('form_message')) ?></span><textarea name="message" rows="3" maxlength="3000" required></textarea></label>
            <label class="consent-row"><input type="checkbox" name="consent" value="yes" required><span><?= k9e(k9t('form_consent')) ?></span></label>
            <div class="form-submit-row">
                <button class="button button-acid" type="submit"><?= k9e(k9t('form_submit')) ?><span aria-hidden="true"><?= k9_icon('arrow','k9-icon-up') ?></span></button>
                <p class="form-note"><?= k9e(k9t('form_note')) ?></p>
                <noscript><p><?= $lang === 'bg' ? 'За онлайн изпращане включете JavaScript или се обадете на' : 'Enable JavaScript to send online, or call' ?> <a href="tel:+359892360550">+359 892 360 550</a>.</p></noscript>
            </div>
            <p class="form-status" role="status" aria-live="polite" data-form-status><?= k9e($serverFormStatus ?? '') ?></p>
        </form>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<footer class="site-footer">
    <div class="site-container footer-grid">
        <div>
            <a class="brand brand-footer k9-brand" aria-label="K9 Academy" href="<?= k9e(k9_url('index.php')) ?>">
                <?= k9_icon('logo','k9-logo') ?>
                <span class="brand-type"><strong>ACADEMY</strong><small><?= k9e(k9t('brand_tagline')) ?></small></span>
            </a>
            <p class="footer-statement"><?= $lang === 'bg' ? 'Ясна комуникация. Стабилно поведение. По-силна връзка.' : 'Clear communication. Stable behaviour. A stronger bond.' ?></p>
        </div>
        <div>
            <h2><?= k9e(k9t('footer_explore')) ?></h2>
            <nav class="footer-links">
                <?php foreach ($navItems as [$file, $labelKey]): ?><a href="<?= k9e(k9_url($file)) ?>"><?= k9e(k9t($labelKey)) ?></a><?php endforeach; ?>
            </nav>
        </div>
        <div>
            <h2><?= k9e(k9t('footer_contact')) ?></h2>
            <div class="footer-links">
                <a href="tel:+359892360550">+359 892 360 550</a>
                <a href="https://www.facebook.com/k9academybg/" target="_blank" rel="noopener">Facebook <?= k9_icon('arrow','k9-icon-up') ?></a>
                <a href="<?= k9e(k9_url('contact.php')) ?>"><?= k9e(k9t('nav_contact')) ?> <?= k9_icon('arrow','k9-icon-up') ?></a>
            </div>
        </div>
        <div>
            <h2><?= k9e(k9t('footer_language')) ?></h2>
            <div class="footer-language language-switch" aria-label="<?= k9e(k9t('language')) ?>">
                <a href="<?= k9e(k9_url($currentFile, 'bg')) ?>" lang="bg" aria-label="Български" aria-current="<?= $lang === 'bg' ? 'true' : 'false' ?>" data-language-link="bg"><span>Български</span></a>
                <a href="<?= k9e(k9_url($currentFile, 'en')) ?>" lang="en" aria-label="English" aria-current="<?= $lang === 'en' ? 'true' : 'false' ?>" data-language-link="en"><span>English</span></a>
            </div>
            <button class="footer-theme-toggle" type="button" aria-label="<?= k9e(k9t('theme_toggle')) ?>" data-theme-toggle><?= k9_icon('sun','k9-theme-sun') ?><?= k9_icon('moon','k9-theme-moon') ?><b><?= $lang === 'bg' ? 'Светла / тъмна тема' : 'Light / dark theme' ?></b></button>
        </div>
    </div>
    <div class="site-container footer-bottom">
        <p>© <?= date('Y') ?> K9 Academy. <?= k9e(k9t('footer_rights')) ?></p>
        <nav>
            <a href="<?= k9e(k9_url('terms.php')) ?>"><?= k9e(k9t('terms')) ?></a>
            <a href="<?= k9e(k9_url('privacy.php')) ?>"><?= k9e(k9t('privacy')) ?></a>
            <button type="button" data-cookie-settings><?= k9e(k9t('cookies')) ?></button>
        </nav>
    </div>
</footer>

<div class="floating-cta-layer" data-floating-layer role="group" aria-label="<?= k9e(k9t('drag_hint')) ?>">
    <p class="sr-only" id="floating-cta-help"><?= k9e(k9t('drag_instructions')) ?></p>

    <div class="floating-cta floating-cta-whatsapp" data-floating-cta="whatsapp" data-side="right">
        <button type="button" class="floating-channel whatsapp" data-channel="whatsapp" data-channel-url="<?= k9e(k9_channel_url('whatsapp')) ?>" aria-label="WhatsApp — <?= k9_channel_url('whatsapp') ? 'K9 Academy' : ($lang === 'bg' ? 'не е наличен' : 'currently unavailable') ?>" aria-describedby="floating-cta-help">
            <span class="floating-dog-portrait" aria-hidden="true"><img src="assets/images/k9-cta-shepherd.webp" width="256" height="256" alt=""></span>
            <span class="floating-brand-badge" aria-hidden="true"><img src="assets/icons/whatsapp.svg" width="24" height="24" alt=""></span>
            <span class="floating-channel-label" aria-hidden="true">WhatsApp</span>
        </button>
    </div>

    <div class="floating-cta floating-cta-viber" data-floating-cta="viber" data-side="right">
        <button type="button" class="floating-channel viber" data-channel="viber" data-channel-url="<?= k9e(k9_channel_url('viber')) ?>" aria-label="Viber — <?= k9_channel_url('viber') ? 'K9 Academy' : ($lang === 'bg' ? 'не е наличен' : 'currently unavailable') ?>" aria-describedby="floating-cta-help">
            <span class="floating-dog-portrait" aria-hidden="true"><img src="assets/images/k9-cta-shepherd.webp" width="256" height="256" alt=""></span>
            <span class="floating-brand-badge" aria-hidden="true"><img src="assets/icons/viber.svg" width="24" height="24" alt=""></span>
            <span class="floating-channel-label" aria-hidden="true">Viber</span>
        </button>
    </div>

    <div class="floating-remove-target" data-floating-remove aria-hidden="true">
        <span class="floating-remove-mark" aria-hidden="true"><img src="assets/images/k9-cta-shepherd.webp" width="256" height="256" alt=""></span>
        <span><strong><?= k9e(k9t('drop_hide')) ?></strong><small><?= k9e(k9t('drop_hide_note')) ?></small></span>
    </div>

    <button class="floating-restore-tab" type="button" data-floating-restore hidden aria-label="<?= k9e(k9t('restore_channels')) ?>">
        <img src="assets/images/k9-cta-shepherd.webp" width="256" height="256" alt="" aria-hidden="true">
        <span aria-hidden="true">‹</span>
    </button>

    <div class="sr-only" role="status" aria-live="polite" aria-atomic="true" data-floating-status data-hidden-message="<?= k9e(k9t('channel_hidden')) ?>" data-restored-message="<?= k9e(k9t('channels_restored')) ?>"></div>
</div>

<div class="cookie-wall" hidden data-cookie-wall>
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


<div class="toast" role="status" aria-live="polite" aria-atomic="true" data-toast></div>

<script src="assets/js/site-20260905.js" defer></script>
<script src="assets/js/floating-20260906.js" defer></script>
<script src="assets/js/interface-20260906-glass.js" defer></script>
<?php if (!empty($hasBreedCarousel)): ?><script src="assets/js/breed-carousel-20260906.js" defer></script><?php endif; ?>
<?php if (!empty($hasDogViewer)): ?><script type="module" src="assets/js/dog-viewer-20260906.js"></script><?php endif; ?>
</body>
</html>
