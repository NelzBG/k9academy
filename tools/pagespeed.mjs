import {readFile,writeFile,mkdir} from 'node:fs/promises';
import {dirname,resolve,join} from 'node:path';
import {fileURLToPath} from 'node:url';
const root=resolve(dirname(fileURLToPath(import.meta.url)),'..');
const origin=JSON.parse(await readFile(join(root,'src/production.json'),'utf8')).origin;
const result=[];await mkdir(join(root,'reports/pagespeed'),{recursive:true});
for(const route of ['/','/en/'])for(const strategy of ['mobile','desktop']) {
 const query=new URLSearchParams({url:origin+route,strategy});
 for(const category of ['performance','accessibility','best-practices','seo'])query.append('category',category);
 if(process.env.PAGESPEED_API_KEY)query.set('key',process.env.PAGESPEED_API_KEY);
 console.log(`Auditing ${route} ${strategy}`);
 try {
  const response=await fetch('https://www.googleapis.com/pagespeedonline/v5/runPagespeed?'+query,{signal:AbortSignal.timeout(180000)});
  const data=await response.json();const tag=(route==='/en/'?'en':'bg')+'-'+strategy;
  if(!response.ok||!data.lighthouseResult) {result.push({route,strategy,status:response.status,error:data.error?.message||'No Lighthouse result'});continue;}
  // Report excludes URLs that can contain an API key and all unnecessary analytics fields.
  const lhr=data.lighthouseResult;const scores=Object.fromEntries(Object.entries(lhr.categories).map(([k,v])=>[k,Math.round(v.score*100)]));
  const report={route,strategy,fetchTime:lhr.fetchTime,lighthouseVersion:lhr.lighthouseVersion,finalUrl:lhr.finalUrl,scores,metrics:Object.fromEntries(['first-contentful-paint','largest-contentful-paint','speed-index','total-blocking-time','cumulative-layout-shift'].map(k=>[k,{value:lhr.audits[k]?.numericValue,display:lhr.audits[k]?.displayValue}])),findings:Object.values(lhr.audits).filter(a=>a.score!==null&&a.score<1).map(a=>({id:a.id,title:a.title,score:a.score,display:a.displayValue,details:a.details}))};
  await writeFile(join(root,'reports/pagespeed',tag+'.json'),JSON.stringify(report,null,2)+'\n');result.push({route,strategy,scores,fetchTime:lhr.fetchTime});
 }catch(e){result.push({route,strategy,error:e.name==='TimeoutError'?'API timed out':e.message});}
}
await writeFile(join(root,'reports/pagespeed/summary.json'),JSON.stringify({checkedAt:new Date().toISOString(),result},null,2)+'\n');console.log(JSON.stringify(result,null,2));
if(result.some(r=>r.error))process.exitCode=1;
