import {readFile} from 'node:fs/promises';
import assert from 'node:assert/strict';
const routes=JSON.parse(await readFile('src/routes.json','utf8')),config=JSON.parse(await readFile('src/production.json','utf8'));
let forms=0;
for(const lang of ['','en/'])for(const [source,route]of Object.entries(routes)){
 const html=await readFile(lang+route+'index.html','utf8');
 assert(html.includes('href="tel:+359892360550"'),'Call link missing');
 assert(html.includes('href="viber://chat?number=%2B359892360550"'),'Viber destination missing');
 assert(!html.includes('data-channel="whatsapp"')&&!html.includes('https://wa.me/'),'Old WhatsApp action remains');
 assert(html.includes('mailto:mail.k9shop@gmail.com'),'Email contact missing');
 assert(html.includes('href="https://m.me/k9academybg" data-messenger-cta'),'Messenger link missing');
 const m=html.match(/<form[^>]*data-contact-form[^>]*>([\s\S]*?)<\/form>/);
 if(config.contactMode==='online'&&!['privacy.php','terms.php'].includes(source)){
  assert(m,'Form missing on '+lang+route);
  assert(m[0].includes('data-endpoint="'+config.enquiryEndpoint+'"'));
  assert(/name="email"[^>]*required/.test(m[1]),'Customer email must be required');
  assert(m[1].includes('name="consent"')&&m[1].includes('name="website"'));
  forms++;
 }
}
assert.equal(forms,config.contactMode==='online'?12:0);
console.log('PASS: '+forms+' enquiry forms; all call, Viber, Messenger and email handlers point to confirmed destinations.');
