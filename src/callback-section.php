<section class="callback-section k9-textured" id="call-me-back" aria-labelledby="callback-title">
 <div class="site-container callback-grid">
  <div class="callback-copy"><p class="eyebrow">K9 / <?= $lang==='bg'?'ДА ПОГОВОРИМ':'LET’S TALK' ?></p><h2 id="callback-title"><?= $lang==='bg'?'Предпочитате да ви се обадим?':'Prefer a call?' ?></h2><p><?= $lang==='bg'?'Оставете само телефонния си номер, за да обсъдим подходящото обучение за вашето куче.':'Leave just your phone number to discuss the right training for your dog.' ?></p></div>
  <form class="callback-form" action="<?= k9e($production['enquiryEndpoint']) ?>" method="post" data-callback-form novalidate>
   <input type="hidden" name="kind" value="callback"><input type="hidden" name="language" value="<?= k9e($lang) ?>"><input type="hidden" name="page" value="<?= k9e($production['origin'].k9_url($currentFile)) ?>">
   <div hidden aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
   <label for="callback-phone"><?= $lang==='bg'?'Телефонен номер':'Phone number' ?></label>
   <div class="callback-fields"><input id="callback-phone" type="tel" name="phone" autocomplete="tel" inputmode="tel" maxlength="40" required aria-describedby="callback-note callback-status"><button class="button button-acid" type="submit"><?= $lang==='bg'?'Обадете ми се':'Call me back' ?><?= k9_icon('arrow') ?></button></div>
   <p id="callback-note" class="callback-note"><?= $lang==='bg'?'Използваме номера ви само за отговор на тази заявка.':'We use your number only to respond to this request.' ?> <a href="<?= k9e(k9_url('privacy.php')) ?>"><?= $lang==='bg'?'Поверителност':'Privacy' ?></a></p>
   <p id="callback-status" class="callback-status" data-callback-status role="status" aria-live="polite"></p>
  </form>
 </div>
</section>
