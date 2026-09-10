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
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(config.email)) throw new Error('Production enquiry email is required.');
  if (!/^https:\/\//.test(config.enquiryEndpoint)) throw new Error('A tested HTTPS enquiry endpoint is required.');
  for (const channel of ['whatsapp', 'viber']) {
    if (!/^\+?[1-9]\d{7,14}$/.test(config[channel])) throw new Error('Confirm the ' + channel + ' number before release.');
  }
}
const write = async (path, content) => {
  const file = join(output, path);
  await mkdir(dirname(file), { recursive: true });
  await writeFile(file, content, 'utf8');
};
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
await write('robots.txt', 'User-agent: *\n' + (preview ? 'Disallow: /' : 'Allow: /\nSitemap: ' + config.origin + '/sitemap.xml') + '\n');
await write('CNAME', 'www.k9academy.bg\n');
await write('sitemap.xml', '<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">\n' + pages.map(({ route, file }) => {
  const bg = '/' + routes[file], en = '/en/' + routes[file];
  return '  <url><loc>' + escape(config.origin + route) + '</loc><xhtml:link rel="alternate" hreflang="bg" href="' + escape(config.origin + bg) + '"/><xhtml:link rel="alternate" hreflang="en" href="' + escape(config.origin + en) + '"/><xhtml:link rel="alternate" hreflang="x-default" href="' + escape(config.origin + bg) + '"/></url>';
}).join('\n') + '\n</urlset>\n');
await write('404.html', '<!doctype html><html lang="bg"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex"><title>404 — K9 Academy</title><link rel="stylesheet" href="/assets/css/site.css"><link rel="icon" href="/assets/images/brand-20260905/logo.webp"></head><body><main class="site-container legal-page"><p class="eyebrow">K9 / 404</p><h1>Страницата не е намерена.<br>Page not found.</h1><p><a class="button button-acid" href="/">Начало</a> <a class="button button-ink" href="/en/">Home</a></p></main></body></html>\n');
console.log('Generated ' + pages.length + ' bilingual pages (' + (preview ? 'preview; indexing disabled' : 'production') + ').');
