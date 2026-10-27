<section class="callback-section k9-textured" id="call-me-back" aria-labelledby="callback-title">
 <div class="site-container callback-grid">
  <div class="callback-copy"><p class="eyebrow">K9 / <?= $lang==='bg'?'ДА ПОГОВОРИМ':'LET’S TALK' ?></p><h2 id="callback-title"><?= $lang==='bg'?'Да поговорим за вашето куче.':'Let’s talk about your dog.' ?></h2><p><?= $lang==='bg'?'Обадете ни се, за да обсъдим подходящото обучение за вашето куче.':'Call us to discuss the right training for your dog.' ?> <a class="callback-programmes" href="<?= k9e(k9_url('services.php')) ?>"><?= $lang==='bg'?'Разгледайте програмите':'Explore our programmes' ?></a></p></div>
  <div class="callback-call">
   <a class="button button-acid" href="tel:<?= k9e($production['call']) ?>"><?= $lang==='bg'?'Обадете се сега':'Call now' ?><?= k9_icon('arrow') ?></a>
   <a class="callback-number" href="tel:<?= k9e($production['call']) ?>">+359 892 360 550</a>
  </div>
 </div>
</section>
