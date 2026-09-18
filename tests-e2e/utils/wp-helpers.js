// Reusable helpers shared across specs. Everything here drives the real wp-admin /
// storefront UI (no REST API is registered by this plugin per static analysis of
// package/rest-api/version1/class-event-tickets-manager-for-woocommerce-api-process.php,
// so UI automation is the only reliable way to create/inspect data end-to-end).

const { expect } = require('@playwright/test');
const S = require('./selectors');

/** Formats a JS Date the way the plugin's datetimepicker field expects (MM/DD/YYYY HH:mm). */
function formatEtmfwDateTime(date) {
  const pad = (n) => String(n).padStart(2, '0');
  return (
    `${pad(date.getMonth() + 1)}/${pad(date.getDate())}/${date.getFullYear()} ` +
    `${pad(date.getHours())}:${pad(date.getMinutes())}`
  );
}

function uniqueTitle(prefix) {
  return `${prefix} ${Date.now()}`;
}

/** Builds a start/end date pair N days in the future spanning `durationHours`. */
function futureEventWindow(daysFromNow = 7, durationHours = 3) {
  const start = new Date();
  start.setDate(start.getDate() + daysFromNow);
  start.setHours(18, 0, 0, 0);
  const end = new Date(start.getTime() + durationHours * 60 * 60 * 1000);
  return { start, end };
}

/** Builds a start/end pair that is already in the past, for "expired event" scenarios. */
function pastEventWindow(daysAgo = 2) {
  const end = new Date();
  end.setDate(end.getDate() - daysAgo);
  const start = new Date(end.getTime() - 3 * 60 * 60 * 1000);
  return { start, end };
}

/**
 * Creates a new "Events" type product via wp-admin > Products > Add New, fills the
 * required Events tab fields, and publishes it. Returns { productId, title }.
 * Extra Events-tab fields can be filled by the caller before this returns if `beforePublish`
 * is supplied (it receives the Playwright `page`).
 */
async function createEventProduct(page, { title, start, end, venue = 'Test Venue, Test City', beforePublish } = {}) {
  const productTitle = title || uniqueTitle(process.env.TEST_EVENT_PRODUCT_PREFIX || 'E2E Test Event');
  const { start: s, end: e } = { start: start || futureEventWindow().start, end: end || futureEventWindow().end };

  await page.goto('/wp-admin/post-new.php?post_type=product');
  await page.fill(S.woo.productTitle, productTitle);

  await page.selectOption(S.admin.productTypeSelect, 'event_ticket_manager');
  await page.click(S.admin.eventsTab);
  await expect(page.locator(S.admin.eventsPanel)).toBeVisible();

  await page.fill(S.admin.startDateTime, formatEtmfwDateTime(s));
  await page.fill(S.admin.endDateTime, formatEtmfwDateTime(e));
  await page.fill(S.admin.eventVenue, venue);

  if (typeof beforePublish === 'function') {
    await beforePublish(page);
  }

  await page.click(S.admin.publishButton);
  await page.waitForSelector('#message.updated, .notice-success', { timeout: 20_000 }).catch(() => {});

  const url = page.url();
  const match = url.match(/post=(\d+)/);
  const productId = match ? match[1] : null;

  return { productId, title: productTitle };
}

/** Moves a product (by id) to Trash via wp-admin, ignoring failures (best-effort cleanup). */
async function trashProduct(page, productId) {
  if (!productId) return;
  try {
    await page.goto(`/wp-admin/post.php?post=${productId}&action=edit`);
    page.once('dialog', (d) => d.accept());
    await page.click('a.submitdelete');
  } catch (_) {
    // best-effort cleanup only
  }
}

/** Logs in via the storefront `wp-login.php` form (used by specs that need a second/customer session). */
async function loginAsCustomer(page, { baseURL, user, password }) {
  await page.goto(`${baseURL}/wp-login.php`);
  await page.fill('#user_login', user);
  await page.fill('#user_pass', password);
  await Promise.all([page.waitForNavigation(), page.click('#wp-submit')]);
}

module.exports = {
  formatEtmfwDateTime,
  uniqueTitle,
  futureEventWindow,
  pastEventWindow,
  createEventProduct,
  trashProduct,
  loginAsCustomer,
};
