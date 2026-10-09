import {writeFile,mkdir} from 'node:fs/promises';
import {dirname,join,resolve} from 'node:path';
import {fileURLToPath} from 'node:url';
const root=resolve(dirname(fileURLToPath(import.meta.url)),'..');
const api='https://www.nextgen.run/demo/k9/api/ebooks/';const checks=[];const privateCheckouts=[];
for(const [product,language] of [['start','bg'],['puppy','en'],['complete','en']]){
 const response=await fetch(api+'checkout.php',{method:'POST',redirect:'manual',headers:{Origin:'https://www.k9academy.bg','Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({product,language}),signal:AbortSignal.timeout(45000)});
 const location=response.headers.get('location')||'';const valid=response.status===303&&location.startsWith('https://checkout.stripe.com/');
 checks.push({product,status:response.status,checkoutOnStripe:valid});if(valid)privateCheckouts.push({product,language,url:location});else checks.at(-1).message=(await response.text()).replace(/<[^>]*>/g,' ').slice(-250);
}
for(const [path,options,expected] of [
 ['checkout.php',{method:'POST',headers:{Origin:'https://evil.example','Content-Type':'application/x-www-form-urlencoded'},body:'product=start&language=en'},403],
 ['checkout.php',{method:'POST',headers:{Origin:'https://www.k9academy.bg','Content-Type':'application/x-www-form-urlencoded'},body:'product=unknown&language=en'},422],
 ['complete.php?session_id=cs_live_invalid',{},403],
 ['download.php?order=..%2Fcatalog.json&language=en&expires=2000000000&signature=invalid',{},403],
 ['webhook.php',{method:'POST',headers:{'Content-Type':'application/json'},body:'{"type":"checkout.session.completed"}'},400],
 ['ebooks-lib.php',{},403]
]){const response=await fetch(api+path,{...options,signal:AbortSignal.timeout(30000)});checks.push({path,status:response.status,expected,pass:response.status===expected});}
await mkdir(join(root,'reports'),{recursive:true});await writeFile(join(root,'reports/backend.json'),JSON.stringify({checkedAt:new Date().toISOString(),checks},null,2)+'\n');await writeFile(join(root,'reports/backend-checkouts.local.json'),JSON.stringify(privateCheckouts,null,2)+'\n');console.log(JSON.stringify(checks,null,2));if(checks.some(c=>c.pass===false||c.checkoutOnStripe===false))process.exitCode=1;
