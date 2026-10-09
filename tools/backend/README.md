# K9 Academy delivery service

This independent service belongs only to K9 Academy. GitHub Pages publishes the static site; PHP checkout, webhook and downloads run at `https://www.nextgen.run/demo/k9/api/ebooks/`.

Deploy the five endpoint/library files into that directory. Keep `catalog.json`, `signing.key`, `webhook.secret`, order records and the six PDFs in `/home/customer/k9academy-private/ebooks/`, outside the public document root. Files are 0600; private directories 0700. The existing merchant key remains on its existing host and is never copied into this repository or the browser.

`configure-ebooks.php` runs through SSH only. It validates the three Stripe prices and six private PDFs, creates or reuses the endpoint for the exact delivery URL, and stores its signing secret privately. It prints only public identifiers and readiness flags. Never print configuration contents or keys. Do not run it until the endpoint is deployed.

Checkout accepts only the three catalogue entries, quantity one, EUR, and the trusted configured prices. Fulfilment retrieves the session and line items from Stripe, requires a paid one-time session with the exact product, price and total, and checks project metadata. The webhook verifies its signature and timestamp before using any event. Events for other projects are acknowledged without processing or storage. A private file lock prevents duplicate email sends. Refund and dispute events suspend further downloads. A missed webhook can be recovered by the paid confirmation page or the CLI reconciliation command; unpaid sessions never receive files.

Download links are signed, expire after 30 days, and access only a paid order and one of its two named PDFs. PDFs are also attached to the buyer's receipt email. Delivery state distinguishes mail accepted from inbox receipt. A server accepting email does not establish receipt in a mailbox. Keep `src/ebooks.json` disabled until checkout, webhook, invalid-payment handling, downloads and actual mailbox receipt have been verified. Contact mode has the same inbox verification gate.

Run `php tools/backend/check-ebooks.php` for payment validation, signature, expiry, traversal, idempotency and MIME checks. Create an unpaid real checkout for each product to verify current merchant configuration without making a charge. A real purchase requires the buyer's own payment action. If verifying a live order, do not print its email or download token.

For recovery, back up this dedicated directory and the existing enquiry files before replacing them. Roll back only K9 files. Never change another website's catalogue, webhook, pricing or keys.

The existing enquiry endpoint also accepts kind=callback with language, phone, optional same-site page URL and the empty website honeypot. It shares the existing private recipient/from configuration and five-per-hour address limit with full enquiries. Callback delivery sends exactly one team email with a tel link and no customer email copy. Deploy enquiry-mailer.php before enquiry.php, after backing up and verifying the live hashes. Test fixtures use injected transports and send no real email.
