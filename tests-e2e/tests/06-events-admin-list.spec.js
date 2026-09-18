const { test, expect } = require('@playwright/test');
const { EventsAdminPage } = require('../page-objects/EventsAdminPage');
const { ProductEditPage } = require('../page-objects/ProductEditPage');
const { trashProduct, uniqueTitle, futureEventWindow, formatEtmfwDateTime } = require('../utils/wp-helpers');
const S = require('../utils/selectors');

test.describe('Events admin list (WooCommerce > Events)', () => {
  test('events list screen loads without a fatal error', async ({ page }) => {
    const list = new EventsAdminPage(page);
    await list.open();
    await expect(page.locator('body')).not.toContainText('Fatal error');
  });

  test('a newly published event product appears in the Events list', async ({ page }) => {
    const editor = new ProductEditPage(page);
    const { start, end } = futureEventWindow();
    const title = uniqueTitle('E2E Listed Event');

    await editor.openNew();
    await page.fill(S.woo.productTitle, title);
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.fill(S.admin.startDateTime, formatEtmfwDateTime(start));
    await page.fill(S.admin.endDateTime, formatEtmfwDateTime(end));
    await page.fill(S.admin.eventVenue, 'List Venue');
    await editor.publish();
    const productId = editor.currentProductId();

    const list = new EventsAdminPage(page);
    await list.open();
    await expect(page.locator('body')).toContainText(title);

    await trashProduct(page, productId);
  });

  test('export/filter control (wps_export_select_events) is present', async ({ page }) => {
    const list = new EventsAdminPage(page);
    await list.open();
    await expect(page.locator(S.admin.eventsFilterSelect)).toHaveCount(1);
  });
});
