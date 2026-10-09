import { readFile, writeFile, mkdir } from 'node:fs/promises';
import { spawnSync } from 'node:child_process';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const routes = JSON.parse(await readFile(join(root, 'src/routes.json'), 'utf8'));
const config = JSON.parse(await readFile(join(root, 'src/production.json'), 'utf8'));
const preview = process.argv.includes('--preview');
const output = resolve(root, process.env.K9_OUTPUT || '.');
const php = process.env.PHP_BINARY || (process.platform === 'win32' ? 'C:/xampp/php/php.exe' : 'php');
if (!preview) {
  if (!['direct', 'online'].includes(config.contactMode)) throw new Error('Choose contact mode.');
  if (config.contactMode === 'online') {
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(config.email)) throw new Error('Production enquiry email is required.');
  if (!/^https:\/\//.test(config.enquiryEndpoint)) throw new Error('A tested HTTPS enquiry endpoint is required.');
  }
  for (const channel of ['call', 'viber']) {
    if (config[channel] && !/^\+?[1-9]\d{7,14}$/.test(config[channel])) throw new Error('Confirm the ' + channel + ' number before release.');
  }
}
const write = async (path, content) => {
  const file = join(output, path);
  await mkdir(dirname(file), { recursive: true });
  await writeFile(file, content, 'utf8');
};
// One stylesheet request preserves source ordering and relative asset URLs.
const styleSources = ['site.css','interface-20260906-glass.css','training-library-20260914.css','editorial-20261009.css'];
let styles = (await Promise.all(styleSources.map(css=>readFile(join(root,'assets/css',css),'utf8')))).join('\n').replace(/url\(([\"']?)(\.\.\/[^\"')]+)\1\)/g, (_,quote,path)=>'url('+quote+new URL(path,'https://www.k9academy.bg/assets/css/').pathname+quote+')');
// Keep the small font subsets separately cacheable across the bilingual pages.
await write('assets/css/k9-site-20261009.css', styles);
const pages = [];
for (const language of ['bg', 'en']) {
  for (const [file, slug] of Object.entries(routes)) {
    const route = '/' + (language === 'en' ? 'en/' : '') + slug;
    const rendered = spawnSync(php, ['-d', 'display_errors=stderr', join(root, 'tools/render.php'), file, language], { encoding: 'utf8', env: { ...process.env, K9_PREVIEW: preview ? '1' : '0' } });
    if (rendered.status !== 0 || rendered.stderr.trim()) throw new Error(file + ' (' + language + '): ' + rendered.stderr);
    await write(route.slice(1) + 'index.html', rendered.stdout);
    pages.push({ route, language, file });
  }
}
const escape = value => value.replaceAll('&', '&amp;').replaceAll('"', '&quot;').replaceAll('<', '&lt;');
for (const [name, route] of Object.entries({ 'Home.html': '/', 'About.html': '/about/', 'Contact.html': '/contact/', 'bg/index.html': '/' })) {
  await write(name, '<!doctype html><html lang="bg"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>K9 Academy</title><link rel="canonical" href="' + config.origin + route + '"><meta http-equiv="refresh" content="0;url=' + route + '"><script>location.replace(' + JSON.stringify(route) + ' + location.search + location.hash);</script></head><body><a href="' + route + '">K9 Academy</a></body></html>\n');
}
await write('robots.txt', 'User-agent: *\n' + (preview ? 'Disallow: /' : 'Allow: /\nDisallow: /src/\nDisallow: /tools/\nDisallow: /tests/\nSitemap: ' + config.origin + '/sitemap.xml') + '\n');
await write('CNAME', 'www.k9academy.bg\n');
await write('sitemap.xml', '<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">\n' + pages.map(({ route, file }) => {
  const bg = '/' + routes[file], en = '/en/' + routes[file];
  return '  <url><loc>' + escape(config.origin + route) + '</loc><xhtml:link rel="alternate" hreflang="bg" href="' + escape(config.origin + bg) + '"/><xhtml:link rel="alternate" hreflang="en" href="' + escape(config.origin + en) + '"/><xhtml:link rel="alternate" hreflang="x-default" href="' + escape(config.origin + bg) + '"/></url>';
}).join('\n') + '\n</urlset>\n');
const errorRender = spawnSync(php, ['-d', 'display_errors=stderr', join(root, 'tools/render-error.php')], {encoding:'utf8'});
if (errorRender.status !== 0 || errorRender.stderr.trim()) throw Error(errorRender.stderr);
await write('404.html', errorRender.stdout);
const llms = '# K9 Academy\n\n> Bulgarian dog training and guidance for people and dogs. Educational guides support reward-based practice and do not replace an individual consultation.\n\n## Website\n- Bulgarian: ' + config.origin + '/\n- English: ' + config.origin + '/en/\n- Contact: ' + config.origin + '/contact/\n- Email: mail.k9shop@gmail.com\n- Phone: +359892360550\n\n## Guides and services\n' + pages.filter(p => p.language==='en' && !/privacy|terms/.test(p.route)).map(p => '- [' + p.route + '](' + config.origin + p.route + ')').join('\n') + '\n\n## Discovery\n- Sitemap: ' + config.origin + '/sitemap.xml\n- Robots: ' + config.origin + '/robots.txt\n\nPaid PDF files and customer order links are private and are not part of the crawlable website. Do not infer service prices, availability or guarantees from general educational content.\n';
await write('llms.txt', llms);
await write('llm.txt', llms);
const indexKey = JSON.parse(await readFile(join(root, 'src/search.json'),'utf8')).indexNowKey;
await write(indexKey + '.txt', indexKey + '\n');
console.log('Generated ' + pages.length + ' bilingual pages (' + (preview ? 'preview; indexing disabled' : 'production') + ').');
