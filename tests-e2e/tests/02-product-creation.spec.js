const { test, expect } = require('@playwright/test');
const { ProductEditPage } = require('../page-objects/ProductEditPage');
const { trashProduct, uniqueTitle, futureEventWindow, formatEtmfwDateTime } = require('../utils/wp-helpers');
const S = require('../utils/selectors');

test.describe('Event product creation (admin)', () => {
  let createdProductId;

  test.afterEach(async ({ page }) => {
    if (createdProductId) {
      await trashProduct(page, createdProductId);
      createdProductId = undefined;
    }
  });

  test('selecting "Events" product type reveals the Events tab and its panel', async ({ page }) => {
    const editor = new ProductEditPage(page);
    await editor.openNew();
    await page.fill(S.woo.productTitle, uniqueTitle('E2E Tab Visibility'));

    await expect(page.locator(S.admin.eventsTab)).toHaveCount(0).catch(() => {});
    await editor.selectEventProductType();
    await expect(page.locator(S.admin.eventsTab)).toBeVisible();
    await editor.openEventsTab();
  });

  test('other WooCommerce tabs get the hide_if_event_ticket_manager class', async ({ page }) => {
    const editor = new ProductEditPage(page);
    await editor.openNew();
    await page.fill(S.woo.productTitle, uniqueTitle('E2E Hide Class'));
    await editor.selectEventProductType();
    await editor.otherTabsHaveHideClass();
  });

  test('creating a valid event product publishes successfully', async ({ page }) => {
    const editor = new ProductEditPage(page);
    const title = uniqueTitle('E2E Valid Event');
    const { start, end } = futureEventWindow();

    await editor.openNew();
    await page.fill(S.woo.productTitle, title);
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.fill(S.admin.startDateTime, formatEtmfwDateTime(start));
    await page.fill(S.admin.endDateTime, formatEtmfwDateTime(end));
    await page.fill(S.admin.eventVenue, 'Grand Hall, Test City');
    await editor.publish();

    createdProductId = editor.currentProductId();
    expect(createdProductId).toBeTruthy();
    await expect(page.locator('#title')).toHaveValue(title);
  });

  test('booking-offset fields accept numeric input and persist after save', async ({ page }) => {
    const editor = new ProductEditPage(page);
    const { start, end } = futureEventWindow(14);

    await editor.openNew();
    await page.fill(S.woo.productTitle, uniqueTitle('E2E Offset Fields'));
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.fill(S.admin.startDateTime, formatEtmfwDateTime(start));
    await page.fill(S.admin.endDateTime, formatEtmfwDateTime(end));
    await page.fill(S.admin.eventVenue, 'Offset Venue');
    await page.fill(S.admin.bookingOffsetStartDays, '2');
    await page.fill(S.admin.bookingOffsetEndDays, '1');
    await editor.publish();
    createdProductId = editor.currentProductId();

    await editor.open(createdProductId);
    await editor.openEventsTab();
    await expect(page.locator(S.admin.bookingOffsetStartDays)).toHaveValue('2');
    await expect(page.locator(S.admin.bookingOffsetEndDays)).toHaveValue('1');
  });

  test('"Remove/Hide Product" checkbox is present and toggleable', async ({ page }) => {
    const editor = new ProductEditPage(page);
    await editor.openNew();
    await page.fill(S.woo.productTitle, uniqueTitle('E2E Trash Checkbox'));
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.check(S.admin.trashEventCheckbox);
    await expect(page.locator(S.admin.trashEventCheckbox)).toBeChecked();
  });
});
