# E2E tests — Event Tickets Manager for WooCommerce (Free)

Playwright end-to-end suite that drives the real wp-admin and storefront UI against a
running local WordPress + WooCommerce install with this plugin active. No REST API is
registered by the plugin (confirmed by static analysis), so every test automates the
actual admin screens and storefront pages the way a real user would.

## 1. One-time setup

```bash
cd wp-content/plugins/event-tickets-manager-for-woocommerce/tests-e2e
cp .env.example .env      # adjust BASE_URL/credentials if your local site differs
npm install
npx playwright install --with-deps chromium
```

`.env` defaults assume:
- Site URL: `http://localhost:10066`
- Admin user: `admin` / `admin`

## 2. One-time site prep (required for the shortcode tests)

Two of the specs (`tests/07-shortcodes-frontend.spec.js`) expect two WordPress pages to
already exist with these shortcodes in their content:

| Page slug | Shortcode |
|---|---|
| `event-listing` | `[wps_my_all_event_list]` |
| `event-checkin` | `[wps_etmfw_event_checkin_page]` |

Create them once via **Pages > Add New** in wp-admin (or update `EVENT_LISTING_PAGE_SLUG` /
`CHECKIN_PAGE_SLUG` in `.env` to point at pages you already have). Every other spec creates
and cleans up its own test data automatically.

Also make sure at least one WooCommerce payment gateway that doesn't require a live
processor is enabled (**WooCommerce > Settings > Payments** — "Direct bank transfer" or
"Check payments" both work) so `09-order-lifecycle.spec.js` can complete a full checkout.

## 3. Running the suite

```bash
npm test              # headless, full suite
npm run test:headed   # watch it click through the UI
npm run test:ui       # Playwright's interactive UI mode — best for authoring/debugging
npm run test:debug    # step through with the inspector
npx playwright test tests/03-custom-fields-repeater.spec.js   # a single file
npm run report        # open the last HTML report
```

The suite is intentionally run with a single worker (`workers: 1` in
`playwright.config.js`) because tests create and publish real products/orders against
one shared WordPress database — running them in parallel would race on wp-admin state.

## 4. What's covered

- **00-smoke** — plugin is active, menus registered, product type registered.
- **01-admin-settings** — every settings tab loads without a fatal error.
- **02-product-creation** — Events tab visibility toggling, required fields, publishing,
  booking-offset persistence, trash/hide checkbox.
- **03-custom-fields-repeater** — add/remove rows, every field type, required flag,
  persistence after save.
- **04-user-type-pricing** — base-price toggle, multi-row ticket types, stock limits,
  persistence.
- **05-recurring-event** — daily recurrence, weekly/monthly locked without Pro, AJAX
  create button wiring.
- **06-events-admin-list** — WooCommerce > Events list shows published events.
- **07-shortcodes-frontend** — event listing + check-in shortcodes render and validate.
- **08-cart-checkout** — sold-individually enforcement, zero-quantity validation, COD
  gateway hidden, booking-offset purchase blocking.
- **09-order-lifecycle** — full purchase through order-complete, ticket generation
  trigger.
- **10-my-account-tickets** — My Account "Event Tickets" endpoint and table.
- **11-expired-event** — expired events hide Add to Cart and show the expiry notice.

## 5. Known limitations / follow-ups

- Selectors were captured from a full static read of the plugin's PHP/template source
  (see `utils/selectors.js` for the map), not from a live DOM inspection — if a class or
  id has since changed in the plugin, update `utils/selectors.js` first; every page
  object and spec reads from that one file.
- The check-in flow's actual server-side check-in success path (AJAX
  `wps_etmfw_make_user_checkin`) is exercised only for the invalid-email/empty-submit
  client-side cases here, since a full success run needs a real ticket number tied to a
  completed order — extend `07-shortcodes-frontend.spec.js` once you have a fixture
  order to check in against.
- Email delivery (order-complete PDF email, resend-ticket flows) is not asserted — doing
  so needs a mail catcher (e.g. MailHog/Mailpit) wired into the local environment.
- If your local site's checkout requires a country/state not defaulted by WooCommerce,
  adjust `fillCheckoutBillingMinimal()` in `page-objects/StorefrontPage.js`.
