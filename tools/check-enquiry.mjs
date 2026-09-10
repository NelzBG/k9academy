import {spawnSync} from 'node:child_process';
import assert from 'node:assert/strict';
import {dirname,resolve} from 'node:path';
import {fileURLToPath} from 'node:url';
const root=resolve(dirname(fileURLToPath(import.meta.url)),'..');
const php=process.env.PHP_BINARY||(process.platform==='win32'?'C:/xampp/php/php.exe':'php');
const server={HTTP_ORIGIN:'https://www.k9academy.bg',REQUEST_METHOD:'POST',CONTENT_TYPE:'application/x-www-form-urlencoded',CONTENT_LENGTH:'500',REMOTE_ADDR:'127.0.0.1'};
const valid={language:'bg',name:'Тест K9',phone:'+359000000000',email:'qa@example.invalid',dog:'Test dog',service:'consultation',message:'Local validation only. No email is sent.',consent:'yes',website:''};
const cases=[
['other origin',403,{HTTP_ORIGIN:'https://example.invalid'},{}],
['missing origin',403,{HTTP_ORIGIN:''},{}],
['wrong method',405,{REQUEST_METHOD:'GET'},{}],
['wrong content type',415,{CONTENT_TYPE:'text/plain'},{}],
['oversized body',413,{CONTENT_LENGTH:'20000'},{}],
['missing name',422,{}, {name:''}],
['missing consent',422,{}, {consent:''}],
['honeypot',422,{}, {website:'spam'}],
['array field',422,{}, {name:['injected']}],
['invalid programme',422,{}, {service:'unknown'}],
['email header injection',422,{}, {email:'test@example.invalid\r\nBcc: other@example.invalid'}],
['too long message',422,{}, {message:'x'.repeat(3001)}],
['valid but unconfigured',503,{},{}]
];
for(const [name,status,override,fields] of cases){
 const data=Buffer.from(JSON.stringify({server:{...server,...override},post:{...valid,...fields}})).toString('base64');
 const code='register_shutdown_function(function(){echo "\\nSTATUS:".http_response_code();}); $test=json_decode(base64_decode("'+data+'"),true); $_SERVER=$test["server"]; $_POST=$test["post"]; require $argv[1];';
 const result=spawnSync(php,['-r',code,resolve(root,'tools/backend/enquiry.php')],{encoding:'utf8',env:{...process.env,K9_ENQUIRY_CONFIG:resolve(root,'tools/backend/not-configured.local.php')}});
 assert.equal(result.status,0,name+': '+result.stderr);
 assert.match(result.stdout,new RegExp('STATUS:'+status+'$'),name+': '+result.stdout);
 assert.match(result.stdout,/"ok":false/,name);
}
console.log('PASS: '+cases.length+' enquiry validation cases. No delivery attempted.');
