<?php /** The existing Seedance visual, now a compact feature below the hero. */ ?>
<section id="k9-motion-feature" class="k9-motion-feature" aria-labelledby="k9-motion-title">
    <div class="site-container k9-motion-layout">
        <div class="k9-motion-copy">
            <p class="k9-overline"><?= $lang === 'bg' ? 'K9 / В ДВИЖЕНИЕ' : 'K9 / IN MOTION' ?></p>
            <h2 id="k9-motion-title"><?= $lang === 'bg' ? 'Енергия.<br><em>С посока.</em>' : 'Energy.<br><em>With direction.</em>' ?></h2>
            <p><?= $lang === 'bg' ? 'Силата е само началото. Фокусът, балансът и доверието превръщат инстинкта в екипна работа.' : 'Power is just the beginning. Focus, balance and trust turn instinct into teamwork.' ?></p>
            <a class="k9-button k9-button-outline" href="#dog-lab"><?= $lang === 'bg' ? 'Запознайте се с атлета' : 'Meet the athlete' ?><?= k9_icon('arrow') ?></a>
        </div>
        <div class="k9-motion-stage">
            <div class="k9-motion-word" aria-hidden="true">K9</div>
            <div class="k9h-athlete" aria-hidden="true">
                <img src="assets/video/k9-shepherd-loop-poster-20260906.webp" width="1280" height="720" alt="" loading="lazy" decoding="async">
                <video loop muted playsinline preload="none" tabindex="-1" data-src="assets/video/k9-shepherd-loop-20260906.webm"></video>
            </div>
            <span class="k9-motion-label" aria-hidden="true"><?= $lang === 'bg' ? 'ИНСТИНКТ / ФОКУС' : 'INSTINCT / FOCUS' ?></span>
            <button class="k9h-toggle k9-button k9-button-outline" type="button" data-play="<?= $lang === 'bg' ? 'Пусни движението' : 'Play motion' ?>" data-pause="<?= $lang === 'bg' ? 'Спри движението' : 'Pause motion' ?>" hidden><b><?= $lang === 'bg' ? 'Спри движението' : 'Pause motion' ?></b></button>
        </div>
    </div>
</section>
