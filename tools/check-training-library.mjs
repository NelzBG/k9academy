import {readFile,access} from 'node:fs/promises';
import assert from 'node:assert/strict';
const photos=JSON.parse(await readFile('src/training-photos.json','utf8'));
assert.equal(new Set(photos.map(p=>p.id)).size,20);
for(const p of photos)for(const w of [640,1280,1920])await access('assets/images/training-library/training-'+String(p.id).padStart(3,'0')+'-'+w+'.webp');
for(const lang of ['','en/'])for(const route of ['','about/','services/','training/','contact/']){
 const html=await readFile(lang+route+'index.html','utf8');
 assert(html.includes('data-training-gallery'),route+' gallery missing');
 assert(!html.includes('googleusercontent.com')&&!html.includes('photos.google.com'),'Photo library must be self-hosted');
 const count=[...html.matchAll(/data-training-photo /g)].length;
 assert(count>=6,route+' insufficient photos');
 if(route==='training/')assert.equal(count,20);
 assert(html.includes('training-library-20260914.js'));
}
console.log('PASS: 10 localized galleries, 20 curated photos and 60 self-hosted responsive assets.');
