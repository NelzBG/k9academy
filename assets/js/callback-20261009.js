(() => {
 'use strict';
 const bg = document.documentElement.lang === 'bg';
 const text = {
  invalid: bg ? 'Въведете валиден телефонен номер.' : 'Enter a valid phone number.',
  sending: bg ? 'Изпращаме заявката…' : 'Sending your request…',
  sent: bg ? 'Заявката е изпратена. Благодарим ви!' : 'Request sent. Thank you!',
  limited: bg ? 'Изпратени са няколко заявки. Моля, обадете се на +359 892 360 550.' : 'Several requests have already been sent. Please call +359 892 360 550.',
  uncertain: bg ? 'Не успяхме да потвърдим изпращането. Номерът ви остава във формата. Можете да ни се обадите на +359 892 360 550.' : 'We could not confirm delivery. Your number is still in the form. You can call us on +359 892 360 550.'
 };
 document.querySelectorAll('[data-callback-form]').forEach(form => {
  const phone = form.querySelector('[name="phone"]'), button = form.querySelector('[type="submit"]'), status = form.querySelector('[data-callback-status]');
  let sending = false;
  phone.addEventListener('input', () => phone.setCustomValidity(''));
  form.addEventListener('submit', async event => {
   event.preventDefault();
   if (sending) return;
   const value = phone.value.trim(), digits = value.replace(/\D/g, '').length;
   if (!/^\+?[0-9(). -]{7,40}$/.test(value) || digits < 7 || digits > 15) {
    phone.setCustomValidity(text.invalid); status.textContent = text.invalid; phone.reportValidity(); return;
   }
   phone.setCustomValidity('');
   const data = new URLSearchParams({kind: 'callback', language: form.querySelector('[name="language"]').value,
    phone: value, website: form.querySelector('[name="website"]').value, page: form.querySelector('[name="page"]').value});
   if (location.origin === 'https://www.k9academy.bg') data.set('page', location.origin + location.pathname);
   sending = true; button.disabled = true; form.setAttribute('aria-busy', 'true'); status.textContent = text.sending;
   const controller = new AbortController(), timer = setTimeout(() => controller.abort(), 15000);
   try {
    const response = await fetch(form.action, {method: 'POST', credentials: 'omit', headers: {Accept: 'application/json'}, body: data, signal: controller.signal});
    const result = await response.json();
    if (response.ok && result.ok === true) { form.reset(); status.textContent = text.sent; }
    else status.textContent = response.status === 429 ? text.limited : response.status === 422 ? text.invalid : text.uncertain;
   } catch { status.textContent = text.uncertain; }
   finally { clearTimeout(timer); sending = false; button.disabled = false; form.removeAttribute('aria-busy'); }
  });
 });
})();
