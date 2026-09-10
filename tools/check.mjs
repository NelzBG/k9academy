import { readFile, readdir, stat } from 'node:fs/promises';
import { dirname, resolve, join, posix } from 'node:path';
import { fileURLToPath } from 'node:url';
import assert from 'node:assert/strict';
const sourceRoot = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const root = resolve(sourceRoot, process.env.K9_OUTPUT || '.');
const config=JSON.parse(await readFile(join(sourceRoot,'src/production.json'),'utf8'));
const routes=JSON.parse(await readFile(join(sourceRoot,'src/routes.json'),'utf8'));
const production=process.argv.includes('--production');
const failures=[], checked=new Set();
const check = (condition,message) => { if(!condition) failures.push(message); };
async function exactFile(path) {
  const relative=path.replace(/^\//,'');
  if(checked.has(relative)) return;
  checked.add(relative);
  let base=root;
  for(const part of relative.split('/').filter(Boolean)) {
    const names=await readdir(base).catch(()=>[]);
    if(!names.includes(part)) { failures.push('Missing/case-mismatched file: '+relative);return; }
    base=join(base,part);
  }
  if((await stat(base)).isDirectory()) await exactFile('/'+relative.replace(/\/$/,'')+'/index.html');
}
for(const lang of ['bg','en']) for(const [source,slug] of Object.entries(routes)) {
  const route='/'+(lang==='en'?'en/':'')+slug;
  const path=join(root,route.slice(1),'index.html');
  const html=await readFile(path,'utf8');
  for (const match of html.matchAll(/<meta property="og:image" content="([^"]+)"/g)) {
    const image = new URL(match[1]);
    check(image.origin === config.origin, route+': wrong social image origin');
    await exactFile(image.pathname);
  }
  check(html.includes('<html lang="'+lang+'"'),route+': wrong language');
  check((html.match(/<h1(?:\s|>)/g)||[]).length===1,route+': expected one h1');
  check(html.includes('rel="canonical" href="'+config.origin+route+'"'),route+': wrong canonical');
  for(const target of ['bg','en']) check(html.includes('hreflang="'+target+'" href="'+config.origin+'/'+(target==='en'?'en/':'')+slug+'"'),route+': missing translation');
  check(!html.includes('<?php') && !html.includes('<?='),route+': unrendered PHP');
  check(!/href="[^"]*\.php/.test(html),route+': PHP navigation remains');
  check(!html.includes('\ufffd'),route+': invalid UTF-8');
  if(production) {
    check(!/noindex|design demo|дизайн демо|before official launch/i.test(html),route+': preview content remains');
    if(html.includes('data-contact-form')) check(html.includes('data-endpoint="https://'),route+': form endpoint missing');
  }
  for(const match of html.matchAll(/(?:href|src|poster|data-src|data-model)="([^"]+)"/g)) {
    const ref=match[1].replaceAll('&amp;','&');
    if(/^(?:https?:|mailto:|tel:|viber:|data:)/.test(ref)) continue;
    const url=new URL(ref,'https://www.k9academy.bg'+route);
    await exactFile(decodeURIComponent(url.pathname));
    if(url.hash) {
      const target=join(root,url.pathname.slice(1),url.pathname.endsWith('/')?'index.html':'');
      const body=await readFile(target,'utf8').catch(()=>'');
      check(body.includes('id="'+decodeURIComponent(url.hash.slice(1))+'"'),route+': broken anchor '+ref);
    }
  }
  for(const match of html.matchAll(/srcset="([^"]+)"/g)) for(const item of match[1].split(',')) {
    const ref=item.trim().split(/\s+/)[0];
    await exactFile(new URL(ref,'https://www.k9academy.bg'+route).pathname);
  }
  for(const match of html.matchAll(/<script type="application\/ld\+json">([\s\S]*?)<\/script>/g)) JSON.parse(match[1]);
}
for(const css of ['site.css','interface-20260906-glass.css']) {
  const text=await readFile(join(root,'assets/css',css),'utf8');
  for(const match of text.matchAll(/url\(["']?([^"')]+)["']?\)/g)) if(!match[1].startsWith('data:')) await exactFile(new URL(match[1],'https://www.k9academy.bg/assets/css/'+css).pathname);
}
const sitemap=await readFile(join(root,'sitemap.xml'),'utf8');
check((sitemap.match(/<loc>/g)||[]).length===16,'Sitemap must contain 16 pages');
check((await readFile(join(root,'CNAME'),'utf8')).trim()==='www.k9academy.bg','Custom domain changed');
if(production) check(!(await readFile(join(root,'robots.txt'),'utf8')).includes('Disallow: /'),'Robots blocks production');
if(failures.length) { console.error(failures.join('\n'));process.exit(1); }
console.log('PASS: 16 bilingual pages, canonical/hreflang, all local links/anchors, srcsets and CSS assets ('+checked.size+' paths).');
