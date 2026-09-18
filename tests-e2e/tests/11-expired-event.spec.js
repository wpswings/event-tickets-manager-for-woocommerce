const { test, expect } = require('@playwright/test');
const { ProductEditPage } = require('../page-objects/ProductEditPage');
const { StorefrontPage } = require('../page-objects/StorefrontPage');
const { trashProduct, uniqueTitle, pastEventWindow, formatEtmfwDateTime } = require('../utils/wp-helpers');
const S = require('../utils/selectors');

test.describe('Expired event handling', () => {
  let createdProductId;

  test.afterEach(async ({ page }) => {
    if (createdProductId) await trashProduct(page, createdProductId);
    createdProductId = undefined;
  });

  test('a product whose end date is in the past is not purchasable and shows the expired message', async ({ page }) => {
    const editor = new ProductEditPage(page);
    const { start, end } = pastEventWindow(3);
    const title = uniqueTitle('E2E Expired Event');

    await editor.openNew();
    await page.fill(S.woo.productTitle, title);
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.fill(S.admin.startDateTime, formatEtmfwDateTime(start));
    await page.fill(S.admin.endDateTime, formatEtmfwDateTime(end));
    await page.fill(S.admin.eventVenue, 'Past Venue');
    await editor.publish();
    createdProductId = editor.currentProductId();

    const front = new StorefrontPage(page);
    await front.openProduct(createdProductId);

    await expect(page.locator(S.frontend.expiredMessage)).toContainText('This event has expired');
    await expect(page.locator(S.woo.checkoutPlaceOrderButton)).toHaveCount(0);
    expect(await front.addToCartButtonVisible()).toBeFalsy();
  });
});
