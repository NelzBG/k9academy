import http from 'node:http';
import { readFile, stat } from 'node:fs/promises';
import { dirname, extname, join, resolve, sep } from 'node:path';
import { fileURLToPath } from 'node:url';
const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const port = Number(process.env.PORT || 8780);
const qaForm = process.argv.includes('--qa-form');
const types = { '.html':'text/html; charset=utf-8', '.css':'text/css; charset=utf-8', '.js':'text/javascript; charset=utf-8', '.json':'application/json', '.woff2':'font/woff2', '.webp':'image/webp', '.png':'image/png', '.jpg':'image/jpeg', '.svg':'image/svg+xml', '.glb':'model/gltf-binary', '.webm':'video/webm', '.xml':'application/xml; charset=utf-8', '.txt':'text/plain; charset=utf-8' };
http.createServer(async (req,res) => {
  try {
    const url = new URL(req.url, 'http://localhost');
    if (qaForm && url.pathname === '/__qa/enquiry' && req.method === 'POST') {
      let body = '';
      for await (const chunk of req) { body += chunk; if(body.length > 16000) throw Error('too large'); }
      const fields = new URLSearchParams(body);
      const valid = ['name','phone','service','message','consent'].every(key => fields.get(key));
      const fail = fields.get('message')?.includes('[fail]');
      res.writeHead(valid && !fail ? 200 : 503, {'Content-Type':'application/json'});
      res.end(JSON.stringify({ok: Boolean(valid && !fail)})); return;
    }
    if (!['GET','HEAD'].includes(req.method)) { res.writeHead(405); res.end(); return; }
    let target = resolve(root, '.' + decodeURIComponent(url.pathname));
    if (!target.startsWith(root + sep) && target !== root) { res.writeHead(403); res.end(); return; }
    if (/(^|[\\/])(src|tools|tests|\\.git)([\\/]|$)/.test(target.slice(root.length))) { res.writeHead(404); res.end(); return; }
    let info = await stat(target);
    if (info.isDirectory()) { target=join(target,'index.html'); info=await stat(target); }
    let data = await readFile(target);
    if (qaForm && extname(target) === '.html') data=Buffer.from(data.toString().replaceAll('data-endpoint=""','data-endpoint="/__qa/enquiry"'));
    const headers = { 'Content-Type': types[extname(target)] || 'application/octet-stream', 'Cache-Control':'no-store' };
    const range = req.headers.range?.match(/^bytes=(\d+)-(\d*)$/);
    if (range && extname(target) === '.webm') {
      const start=Number(range[1]), end=Math.min(range[2] ? Number(range[2]) : data.length-1,data.length-1);
      if(start>end) { res.writeHead(416);res.end();return; }
      res.writeHead(206,{...headers,'Accept-Ranges':'bytes','Content-Range':'bytes '+start+'-'+end+'/'+data.length,'Content-Length':end-start+1});
      res.end(req.method==='HEAD'?undefined:data.subarray(start,end+1)); return;
    }
    res.writeHead(200,{...headers,'Content-Length':data.length});
    res.end(req.method === 'HEAD' ? undefined : data);
  } catch { res.writeHead(404,{'Content-Type':'text/plain'});res.end('Not found'); }
}).listen(port,'127.0.0.1',()=>console.log('K9 static preview: http://127.0.0.1:'+port+(qaForm?' (local form test stub)':'')));
