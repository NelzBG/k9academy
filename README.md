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

The October release uses contactMode: online in src/production.json. Phone and Viber use +359 892 360 550, Messenger opens m.me/k9academybg, and email opens mail.k9shop@gmail.com. WhatsApp has been replaced by calling with a custom generated icon. The public endpoint accepts the configured HTTPS origin; an Origin-less GET correctly returns JSON 403. The shared delivery transport was verified through an actual two-PDF test to the owner-selected address. The message reached the connected Gmail mailbox in Spam, with SPF, DKIM and DMARC passing. Keep the confirmed recipient, tested endpoint and direct phone/email alternatives. Public configuration contains no credentials.

tools/backend/enquiry.php and enquiry-mailer.php implement an independent PHP relay for the existing NextGen host. It must not be deployed to GitHub Pages. Its recipient and sender configuration belongs outside public_html in /home/customer/k9academy-private/enquiry-config.php, or at the private path configured by K9_ENQUIRY_CONFIG. Use tools/backend/enquiry-config.example.php as a template and restrict it to 0600. The relay accepts requests only from https://www.k9academy.bg, validates the fields, includes a honeypot and limits requests by address. No enquiry bodies are stored on disk. The relay sends a branded Bulgarian or English team notification and customer acknowledgement. PHP mail acceptance does not prove inbox delivery. Verify public-browser delivery and real mailbox receipt before enabling online mode. Run node tools/check-enquiry.mjs, php tools/check-enquiry-mailer.php and node tools/check-contact-handlers.mjs for validation.

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

Merge only the completed, tested production build. A push to main triggers the existing Pages publication. After deployment, check both languages, video/model loading, navigation and active contact options on the public domain.

To roll back, revert the migration merge commit on main and push the revert. Do not force-push main. The rollback branch preserves the complete original tree.

The separate missing DNS address records for k9academy.bg must be corrected at its Azure DNS provider. The existing www address already points to GitHub Pages.

## Training photo library

`src/training-gallery.php` is the shared PHP include used by Home, About, Services, Training and Contact in both languages. Training contains the complete curated selection of 20 real photographs; other pages use themed subsets. Page heroes and three background panels also use the library. The existing abstract homepage hero and animated models are preserved.

`src/training-photos.json` records dimensions. Each selected original has self-hosted 640, 1280 and 1920 pixel WebP derivatives; originals and EXIF metadata are not published. Native scroll snapping supports touch and keyboard navigation, and the modal viewer supports arrow keys, swipe and Escape. The gallery remains usable as ordinary image links without JavaScript.

## October 2026 technical release

New bilingual guidance and articles are in `src/editorial.json`; shared e-book cards in `src/ebook-section.php`. YouTube is delayed until media consent and idle time; reduced-motion/data-saver visitors retain posters and a play option. PDFs are never published in GitHub Pages or in the public repository. See `tools/backend/README.md` for payment verification, private delivery and the required release gate. `node tools/audit-links.mjs` scans every built route; `node tools/pagespeed.mjs` records real PageSpeed Insights results. `node tools/indexnow.mjs` verifies the public key file before submitting. Google sitemap submission requires the verified Search Console property; IndexNow receipt does not establish indexing.

The final audit refinement converts the original licensed Manrope/Oswald glyph maps to WOFF2, preloads the two primary faces and embeds the combined styles in the initial document. CSS background discovery is static, so it does not wait for JavaScript or force layout reads. The combined file is generated from the four existing stylesheet sources; edit those sources and rebuild. The initial payload trades some cross-page CSS caching for fewer render-blocking round trips on this small static website.

The October 9 layout refinement reserves the fixed header dimensions in CSS, renders privacy choices before the page content with platform fonts, prevents focus from scrolling the page, and traps keyboard focus inside the privacy dialog. Saved choices hide the panel before paint. The effects control keeps its allocated space while hidden. Bold Manrope is preloaded for the editorial headings.

The final font-delivery refinement embeds small licensed BG/EN website subsets in the generated style block. Visible text and standard Latin/Cyrillic character coverage are checked against each original font. Original full glyph maps and licence files are retained. Local decorative animation and native video pause while privacy or navigation dialogs cover the page.
