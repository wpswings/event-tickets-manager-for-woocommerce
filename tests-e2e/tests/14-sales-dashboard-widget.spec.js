const { test, expect } = require('@playwright/test');
const { createEventProduct, trashProduct, uniqueTitle, futureEventWindow } = require('../utils/wp-helpers');
const S = require('../utils/selectors');

// Covers the "Event Tickets Sales" wp-admin Dashboard widget, moved from Pro 2.3.9 into
// Free (admin/class-event-tickets-manager-for-woocommerce-sales-dashboard.php). It keeps
// the Pro widget id and CSS classes, so these selectors are unchanged.
// The suite runs as admin, which has manage_woocommerce, so the widget must be registered.

const STAT_KEYS = ['net-sales', 'tickets-sold', 'upcoming-events', 'low-stock', 'out-of-stock'];

/** Reads a stat's displayed number (number_format_i18n output, e.g. "1,234") as an int. */
async function readCount(page, key) {
  const text = (await page.locator(S.dashboard.statValue(key)).innerText()).trim();
  return parseInt(text.replace(/[^\d]/g, ''), 10);
}

/**
 * Opens the Dashboard and makes sure the widget is not hidden via Screen Options
 * (a previous run or a real user may have unticked it; that is stored per user).
 */
async function openDashboardWithWidget(page) {
  await page.goto('/wp-admin/index.php');
  const widget = page.locator(S.dashboard.salesWidget);
  await expect(widget, 'widget is registered on wp_dashboard_setup').toHaveCount(1);

  if (!(await widget.isVisible())) {
    await page.click(S.dashboard.screenOptionsToggle);
    await page.check(S.dashboard.salesWidgetScreenOption);
    await page.click(S.dashboard.screenOptionsToggle);
  }
  await expect(widget).toBeVisible();
  return widget;
}

test.describe('Event Tickets Sales dashboard widget', () => {
  test('widget is shown on the Dashboard with its title', async ({ page }) => {
    await openDashboardWithWidget(page);
    await expect(page.locator(S.dashboard.salesWidgetTitle)).toContainText('Event Tickets Sales');
  });

  test('widget renders all five default stats in order', async ({ page }) => {
    await openDashboardWithWidget(page);
    const rows = page.locator(S.dashboard.statRows);
    await expect(rows).toHaveCount(STAT_KEYS.length);

    for (const [i, key] of STAT_KEYS.entries()) {
      await expect(rows.nth(i)).toHaveClass(new RegExp(`\\bwps-etmfwp-${key}\\b`));
    }

    await expect(page.locator(S.dashboard.stat('net-sales'))).toContainText('Net ticket sales this month');
    await expect(page.locator(S.dashboard.stat('tickets-sold'))).toContainText('Tickets sold this month');
    await expect(page.locator(S.dashboard.stat('upcoming-events'))).toContainText('Upcoming events');
    await expect(page.locator(S.dashboard.stat('low-stock'))).toContainText('Low ticket stock');
    await expect(page.locator(S.dashboard.stat('out-of-stock'))).toContainText('Sold out events');
  });

  test('the two monthly stats are full width, the rest half width', async ({ page }) => {
    await openDashboardWithWidget(page);
    for (const key of ['net-sales', 'tickets-sold']) {
      await expect(page.locator(S.dashboard.stat(key))).toHaveClass(/\bwps-etmfwp-full\b/);
    }
    for (const key of ['upcoming-events', 'low-stock', 'out-of-stock']) {
      await expect(page.locator(S.dashboard.stat(key))).not.toHaveClass(/\bwps-etmfwp-full\b/);
    }
  });

  test('stat values are formatted numbers / prices', async ({ page }) => {
    await openDashboardWithWidget(page);

    // wc_price() markup: a .woocommerce-Price-amount span with a number in it.
    const netSales = page.locator(S.dashboard.statValue('net-sales'));
    await expect(netSales.locator('.woocommerce-Price-amount')).toHaveCount(1);
    await expect(netSales).toHaveText(/\d/);

    for (const key of ['tickets-sold', 'upcoming-events', 'low-stock', 'out-of-stock']) {
      expect(Number.isNaN(await readCount(page, key)), `${key} shows a number`).toBe(false);
    }
  });

  test('each stat links to the matching admin screen', async ({ page }) => {
    await openDashboardWithWidget(page);
    const href = (key) => page.locator(S.dashboard.statLink(key)).getAttribute('href');

    // Orders link follows the active order storage (HPOS vs legacy posts).
    const ordersHref = /admin\.php\?page=wc-orders|edit\.php\?post_type=shop_order/;
    expect(await href('net-sales')).toMatch(ordersHref);
    expect(await href('tickets-sold')).toMatch(ordersHref);
    expect(await href('upcoming-events')).toContain('product_type=event_ticket_manager');
    expect(await href('low-stock')).toContain('type=lowstock');
    expect(await href('out-of-stock')).toMatch(/product_type=event_ticket_manager.*stock_status=outofstock/);
  });

  test('the orders link opens a working Orders screen', async ({ page }) => {
    await openDashboardWithWidget(page);
    await page.click(S.dashboard.statLink('tickets-sold'));
    await expect(page.locator('body')).not.toContainText('Fatal error');
    await expect(page.locator('h1.wp-heading-inline, .wp-heading-inline').first()).toContainText('Orders');
  });

  test('widget stylesheet is loaded on the Dashboard only', async ({ page }) => {
    const widgetCss = 'link[rel="stylesheet"][href*="event-tickets-manager-for-woocommerce-dashboard-widget.css"]';

    await page.goto('/wp-admin/index.php');
    await expect(page.locator(widgetCss)).toHaveCount(1);

    await page.goto('/wp-admin/plugins.php');
    await expect(page.locator(widgetCss)).toHaveCount(0);
  });

  test.describe('live counts', () => {
    let createdProductId;

    test.afterEach(async ({ page }) => {
      if (createdProductId) await trashProduct(page, createdProductId);
      createdProductId = undefined;
    });

    test('"Upcoming events" goes up by one after publishing a future event', async ({ page }) => {
      await openDashboardWithWidget(page);
      const before = await readCount(page, 'upcoming-events');

      // Stats are computed on every render (no caching), so the new event must show at once.
      const { start, end } = futureEventWindow(14);
      ({ productId: createdProductId } = await createEventProduct(page, { title: uniqueTitle('E2E Dashboard Upcoming'), start, end }));
      expect(createdProductId).toBeTruthy();

      await openDashboardWithWidget(page);
      expect(await readCount(page, 'upcoming-events')).toBe(before + 1);
    });
  });
});
