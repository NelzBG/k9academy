# K9 Academy

The approved PHP design is rendered to static HTML for the existing GitHub Pages site at https://www.k9academy.bg. Bulgarian is served at / and English at /en/. The original Home.html, About.html and Contact.html URLs remain as redirect pages.

## Build and preview

Requires Node.js 20+ and PHP 8.1+ with mbstring. Set PHP_BINARY if PHP is installed at a nonstandard path.

```sh
node tools/build.mjs --preview
node tools/check.mjs
node tools/check-enquiry.mjs
node tools/serve.mjs
```

The preview opens at http://127.0.0.1:8780. The optional --qa-form flag enables an entirely local form stub: a message containing [fail] simulates a failed submission. Other valid messages receive a simulated acceptance. This flag never sends email and is only for testing client behaviour.

The existing compiled styles are included. They are not rebuilt during the HTML export. For an intentional style change, install the pinned dependencies with pnpm install --frozen-lockfile and run pnpm build:css. Source templates are under src/; shared images, fonts, video and model files are under assets/.

## Production contacts

Fill src/production.json with the confirmed recipient address, tested HTTPS enquiry endpoint and confirmed international WhatsApp/Viber numbers. Public configuration contains no credentials.

tools/backend/enquiry.php is an independent PHP relay for the existing NextGen host. It must not be deployed to GitHub Pages. Its recipient and sender configuration belongs outside public_html in /home/customer/k9academy-private/enquiry-config.php, or at the private path configured by K9_ENQUIRY_CONFIG. Use tools/backend/enquiry-config.example.php as a template and restrict it to 0600. The relay accepts requests only from https://www.k9academy.bg, validates the fields, includes a honeypot and limits requests by address. No enquiry bodies are stored on disk. Verify real mailbox delivery before launch.

The production build refuses incomplete contact configuration:

```sh
node tools/build.mjs
node tools/check.mjs --production
```

Commit the generated HTML, robots.txt and sitemap.xml with their source changes. The existing main/root Pages publishing source and CNAME remain in use. _config.yml excludes the templates, tools and tests and explicitly includes the self-hosted Three.js vendor directory.

## Release and rollback

Migration branch: migration/static-pages-20260910.
Rollback branch: rollback/pre-migration-20260910.
Original production commit: 6709fbb81b869e5e0b53b65f0a8a9576aacf0b06.

Merge only the completed, tested production build. A push to main triggers the existing Pages publication. After deployment, check both languages, video/model loading, navigation and real form delivery on the public domain.

To roll back, revert the migration merge commit on main and push the revert. Do not force-push main. The rollback branch preserves the complete original tree.

The separate missing DNS address records for k9academy.bg must be corrected at its Azure DNS provider. The existing www address already points to GitHub Pages.
