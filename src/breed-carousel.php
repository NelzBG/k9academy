<?php
$hasBreedCarousel = true;
$k9Breeds = [
    ['shepherd', 'German shepherd', 'Немска овчарка'],
    ['golden', 'Golden retriever', 'Голдън ретривър'],
    ['doodle', 'Doodle', 'Дудъл'],
    ['pomeranian', 'Pomeranian', 'Померан'],
    ['chow', 'Chow chow', 'Чау-чау'],
    ['bernese', 'Bernese mountain dog', 'Бернско планинско куче'],
];
?>
<section class="k9-breeds" aria-labelledby="k9-breeds-title" data-breed-carousel>
    <div class="site-container">
        <div class="k9-breeds-heading">
            <div>
                <p class="k9-overline"><?= $lang === 'bg' ? 'РАЗЛИЧЕН ХАРАКТЕР. ОБЩА ПОСОКА.' : 'DIFFERENT CHARACTERS. SHARED DIRECTION.' ?></p>
                <h2 id="k9-breeds-title"><?= $lang === 'bg' ? 'Всяко куче е индивидуалност.' : 'Every dog is an individual.' ?></h2>
            </div>
            <div class="k9-carousel-controls">
                <button class="k9-icon-button" type="button" data-breed-prev aria-label="<?= $lang === 'bg' ? 'Предишни породи' : 'Previous breeds' ?>" aria-controls="k9-breed-track"><?= k9_icon('arrow','k9-icon-back') ?></button>
                <button class="k9-icon-button" type="button" data-breed-next aria-label="<?= $lang === 'bg' ? 'Следващи породи' : 'Next breeds' ?>" aria-controls="k9-breed-track"><?= k9_icon('arrow') ?></button>
            </div>
        </div>
        <div class="k9-breed-track" id="k9-breed-track" tabindex="0" role="region" aria-label="<?= $lang === 'bg' ? 'Породи кучета — плъзнете за още' : 'Dog breeds — swipe to explore' ?>">
            <?php foreach ($k9Breeds as $i => [$file, $en, $bg]): ?>
            <figure class="k9-breed-card">
                <span class="k9-breed-number" aria-hidden="true">0<?= $i + 1 ?></span>
                <img src="assets/images/breeds-20260905/<?= k9e($file) ?>.webp" width="720" height="390" alt="<?= k9e($lang === 'bg' ? $bg : $en) ?>" loading="lazy" decoding="async" draggable="false">
                <figcaption><?= k9e($lang === 'bg' ? $bg : $en) ?></figcaption>
            </figure>
            <?php endforeach; ?>
        </div>
        <div class="k9-breeds-footer"><button class="k9-carousel-toggle" type="button" data-breed-toggle aria-controls="k9-breed-track" aria-pressed="false" hidden><?= $lang === 'bg' ? 'Спри движението' : 'Pause movement' ?></button><p><?= $lang === 'bg' ? 'Подходът следва темперамента, а не етикета.' : 'The approach follows the temperament, not the label.' ?></p><span data-breed-status aria-live="off"></span></div>
    </div>
</section>
