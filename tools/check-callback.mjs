import assert from 'node:assert/strict';
import {readFile,mkdtemp,writeFile,rm,realpath} from 'node:fs/promises';
import {spawnSync} from 'node:child_process';
import {dirname,resolve,join,basename} from 'node:path';
import {tmpdir} from 'node:os';
import {fileURLToPath} from 'node:url';
import {createHash} from 'node:crypto';
import vm from 'node:vm';
const root=resolve(dirname(fileURLToPath(import.meta.url)),'..');
const php=process.env.PHP_BINARY||(process.platform==='win32'?'C:/xampp/php/php.exe':'php');
const routes=JSON.parse(await readFile(join(root,'src/routes.json'),'utf8'));
let pages=0;
for(const path of [...['','en/'].flatMap(prefix=>Object.values(routes).map(slug=>prefix+slug+'index.html')),'404.html']){
 const html=await readFile(join(root,path),'utf8');
 assert.equal((html.match(/data-callback-form/g)||[]).length,1,path+': one callback form');
 const form=html.match(/<form\b[^>]*data-callback-form[\s\S]*?<\/form>/)[0];
 assert(form.includes('action="https://www.nextgen.run/demo/k9/api/enquiry.php"'));
 const shown=form.replace(/<div hidden[\s\S]*?<\/div>/g,'');
 const inputs=[...shown.matchAll(/<input\b[^>]*>/g)].map(m=>m[0]).filter(tag=>!tag.includes('type="hidden"'));
 assert.equal(inputs.length,1);assert.match(inputs[0],/type="tel"/);assert.match(inputs[0],/name="phone"/);assert.match(inputs[0],/required/);
 assert(!shown.includes('type="checkbox"'));assert(form.includes('name="kind" value="callback"'));
 assert(html.includes('id="call-me-back"'));assert(html.includes('href="#call-me-back"'));
 assert(html.includes('callback-20261009.js?v=20261009-callback9'));pages++;
}
const source=await readFile(join(root,'assets/js/callback-20261009.js'),'utf8');
function client({lang='en',response={ok:true,status:200,json:async()=>({ok:true})},failure=false,pending=false,phoneValue='+359 892 360 550'}={}){
 const fields={phone:{value:phoneValue,setCustomValidity(v){this.validity=v;},reportValidity(){this.reported=true;}},language:{value:lang},page:{value:'https://www.k9academy.bg/'},website:{value:''}};
 const button={disabled:false},status={textContent:''},handlers=new Map(),calls=[];let resolveFetch;
 fields.phone.addEventListener=(name,fn)=>handlers.set('phone:'+name,fn);
 const form={action:'https://www.nextgen.run/demo/k9/api/enquiry.php',attrs:{},querySelector(key){if(key==='[type="submit"]')return button;if(key==='[data-callback-status]')return status;return fields[key.match(/name="([^"]+)"/)[1]];},addEventListener(name,fn){handlers.set(name,fn);},setAttribute(k,v){this.attrs[k]=v;},removeAttribute(k){delete this.attrs[k];},reset(){fields.phone.value='';}};
 const sandbox={document:{documentElement:{lang},querySelectorAll:()=>[form]},location:{origin:'https://www.k9academy.bg',pathname:'/en/puppy-training/',search:'?private=value'},URLSearchParams,AbortController,setTimeout:()=>1,clearTimeout:()=>{},fetch:async(url,args)=>{calls.push({url,args});if(failure)throw Error('Network uncertainty');if(pending)return new Promise(r=>{resolveFetch=r;});return response;}};
 vm.runInNewContext(source,sandbox);
 return {fields,button,status,calls,submit:()=>handlers.get('submit')({preventDefault(){}}),finish:()=>resolveFetch(response)};
}
let h=client({phoneValue:'+++++++'});await h.submit();assert.equal(h.calls.length,0);assert(h.fields.phone.reported);
for(const lang of ['bg','en']){
 h=client({lang});await h.submit();assert.equal(h.calls.length,1);assert.equal(h.fields.phone.value,'');assert(!h.button.disabled);
 const body=h.calls[0].args.body;assert.equal(body.get('kind'),'callback');assert.equal(body.get('language'),lang);assert.equal(body.get('phone'),'+359 892 360 550');assert.equal(body.get('page'),'https://www.k9academy.bg/en/puppy-training/');assert(!body.has('email'));assert(!body.has('name'));assert(!body.toString().includes('private'));assert.equal(h.calls[0].args.credentials,'omit');
 assert.match(h.status.textContent,lang==='bg'?/Благодарим/:/Thank you/);
}
h=client({pending:true});const first=h.submit();await h.submit();assert.equal(h.calls.length,1);assert(h.button.disabled);h.finish();await first;
for(const options of [{failure:true},{response:{ok:false,status:503,json:async()=>({ok:false})}},{response:{ok:false,status:429,json:async()=>({ok:false})}},{response:{ok:true,status:200,json:async()=>{throw Error('Invalid response');}}}]){
 h=client(options);await h.submit();assert.equal(h.calls.length,1);assert.equal(h.fields.phone.value,'+359 892 360 550');assert(!h.button.disabled);assert.match(h.status.textContent,/call|confirm/i);
}
const temp=await mkdtemp(join(tmpdir(),'k9-callback-tests-'));
try{
 const config=join(temp,'config.php');
 await writeFile(config,"<?php return ['recipient'=>'mail.k9shop@gmail.com','from'=>'website@nextgen.run'];\n");
 const server={HTTP_ORIGIN:'https://www.k9academy.bg',REQUEST_METHOD:'POST',CONTENT_TYPE:'application/x-www-form-urlencoded',CONTENT_LENGTH:'250',REMOTE_ADDR:'callback-test'};
 const valid={kind:'callback',language:'en',phone:'+359892360550',website:'',page:'https://www.k9academy.bg/en/puppy-training/'};
 const cases=[
  ['phone only accepted',200,{},{},true],['Bulgarian accepted',200,{}, {language:'bg'},true],
  ['missing phone',422,{}, {phone:''}],['letters in phone',422,{}, {phone:'not-a-phone'}],['too few digits',422,{}, {phone:'....----'}],['too many digits',422,{}, {phone:'1'.repeat(16)}],['misplaced plus',422,{}, {phone:'123+456789'}],
  ['array phone',422,{}, {phone:['123456789']}],['honeypot',422,{}, {website:'bot'}],['foreign page',422,{}, {page:'https://example.invalid/'}],['page credentials',422,{}, {page:'https://user@www.k9academy.bg/'}],['private query excluded',422,{}, {page:'https://www.k9academy.bg/?private=1'}],['invalid kind',422,{}, {kind:'unknown'}],['invalid language',422,{}, {language:'xx'}],
  ['other origin',403,{HTTP_ORIGIN:'https://example.invalid'},{}],['missing origin',403,{HTTP_ORIGIN:''},{}],['wrong method',405,{REQUEST_METHOD:'GET'},{}],
  ['native confirmation',200,{HTTP_ACCEPT:'text/html'}, {},true],['native validation',422,{HTTP_ACCEPT:'text/html'}, {phone:'bad'}],
  ['transport failure',503,{},{},false],['unconfigured',503,{},{},false,'missing'],['rate limit',429,{REMOTE_ADDR:'rate-blocked'},{},true]
 ];
 await import('node:fs/promises').then(fs=>fs.mkdir(join(temp,'enquiry-rates')));
 await writeFile(join(temp,'enquiry-rates',createHash('sha256').update('rate-blocked').digest('hex')+'.json'),JSON.stringify({start:Math.floor(Date.now()/1000),count:5}));
 for(const [name,expected,override,post,mailOk=false,configMode] of cases){
  const data=Buffer.from(JSON.stringify({server:{...server,...override},post:{...valid,...post},mailOk})).toString('base64');
  const code='register_shutdown_function(function(){echo "\\nSTATUS:".http_response_code();}); $test=json_decode(base64_decode("'+data+'"),true); function mail($to,$subject,$body,$headers,$params){global $test; if($to!=="mail.k9shop@gmail.com")throw new Exception("Wrong recipient"); return $test["mailOk"]; } $_SERVER=$test["server"]; $_POST=$test["post"]; require $argv[1];';
  const r=spawnSync(php,['-d','disable_functions=mail','-r',code,join(root,'tools/backend/enquiry.php')],{encoding:'utf8',env:{...process.env,K9_ENQUIRY_CONFIG:configMode==='missing'?join(temp,'missing.php'):config}});
  assert.equal(r.status,0,name+': '+r.stderr);assert.equal(r.stderr,'',name+': no warnings');assert.match(r.stdout,new RegExp('STATUS:'+expected+'$'),name+': '+r.stdout);
  if(override.HTTP_ACCEPT)assert.match(r.stdout,/<h1>/,name+': branded native response');
  else assert.match(r.stdout,new RegExp('"ok":'+(expected===200)),name);
 }
 console.log('PASS: '+pages+' callback pages, 8 client scenarios and '+cases.length+' endpoint cases. All mail transports mocked; no email sent.');
}finally{
 const actual=await realpath(temp);
 assert.equal(dirname(actual),await realpath(tmpdir()));assert(basename(actual).startsWith('k9-callback-tests-'));
 await rm(actual,{recursive:true,force:true});
}
