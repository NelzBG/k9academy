import {readFile,writeFile,mkdir} from 'node:fs/promises';
import {dirname,resolve,join} from 'node:path';
import {fileURLToPath} from 'node:url';
const root=resolve(dirname(fileURLToPath(import.meta.url)),'..');
const routes=JSON.parse(await readFile(join(root,'src/routes.json'),'utf8'));
const origin=JSON.parse(await readFile(join(root,'src/production.json'),'utf8')).origin;
const live=process.argv.includes('--live');
const results=[]; const errors=[];
for (const lang of ['bg','en']) for (const slug of Object.values(routes)) {
 const route='/'+(lang==='en'?'en/':'')+slug;
 let html;
 if(live) { const response=await fetch(origin+route,{signal:AbortSignal.timeout(20000)});if(response.status!==200)errors.push(`${route}: HTTP ${response.status}`);html=await response.text(); }
 else html=await readFile(join(root,route.slice(1),'index.html'),'utf8');
 const contextual=html.match(/<(?:main|aside)\b[\s\S]*?<\/main>|<aside class="learning-links[\s\S]*?<\/aside>/g)?.join('\n')||'';
 const links=[...contextual.matchAll(/<a\b[^>]*href="([^"]+)"[^>]*>([\s\S]*?)<\/a>/g)].map(m=>({url:new URL(m[1].replaceAll('&amp;','&'),origin+route),label:m[2].replace(/<[^>]*>/g,'').trim()})).filter(x=>x.url.origin===origin&&x.url.pathname!==route);
 const unique=[...new Set(links.map(x=>x.url.pathname))];
 if(unique.length<3)errors.push(`${route}: fewer than three contextual destinations`);
 if(/\{\{[a-z]+/.test(html))errors.push(`${route}: unresolved editorial link`);
 results.push({route,contextualDestinations:unique,links:links.map(x=>({href:x.url.pathname+x.url.hash,label:x.label})),bytes:Buffer.byteLength(html)});
}
const sitemap=await readFile(join(root,'sitemap.xml'),'utf8');
const indexed=[...sitemap.matchAll(/<loc>(.*?)<\/loc>/g)].map(m=>m[1]);
for(const r of results)if(!indexed.includes(origin+r.route))errors.push(`${r.route}: missing from sitemap`);
await mkdir(join(root,'reports'),{recursive:true});
await writeFile(join(root,'reports',live?'links-live.json':'links-local.json'),JSON.stringify({checkedAt:new Date().toISOString(),live,results,errors},null,2)+'\n');
console.log(JSON.stringify({pages:results.length,minimumContextualDestinations:Math.min(...results.map(r=>r.contextualDestinations.length)),errors},null,2));
if(errors.length)process.exitCode=1;
