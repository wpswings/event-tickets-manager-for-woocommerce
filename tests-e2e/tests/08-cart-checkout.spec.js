const { test, expect } = require('@playwright/test');
const { ProductEditPage } = require('../page-objects/ProductEditPage');
const { StorefrontPage } = require('../page-objects/StorefrontPage');
const { trashProduct, uniqueTitle, futureEventWindow, formatEtmfwDateTime } = require('../utils/wp-helpers');
const S = require('../utils/selectors');

test.describe('Cart & checkout validation for event products', () => {
  let createdProductId;

  test.afterEach(async ({ page }) => {
    if (createdProductId) await trashProduct(page, createdProductId);
    createdProductId = undefined;
  });

  async function createEventWithUserTypes(page, { start, end } = {}) {
    const editor = new ProductEditPage(page);
    const window = futureEventWindow();
    const title = uniqueTitle('E2E Cart Event');

    await editor.openNew();
    await page.fill(S.woo.productTitle, title);
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.fill(S.admin.startDateTime, formatEtmfwDateTime(start || window.start));
    await page.fill(S.admin.endDateTime, formatEtmfwDateTime(end || window.end));
    await page.fill(S.admin.eventVenue, 'Cart Test Venue');
    await editor.enableUserTypePricing();
    const idx = await editor.userTypeRowCount();
    await editor.addUserTypePriceRow(idx, { label: 'Adult', price: 25, stockLimit: 100 });
    await editor.publish();
    createdProductId = editor.currentProductId();
    return { productId: createdProductId, title };
  }

  test('event product is sold individually (quantity forced to 1)', async ({ page }) => {
    const { productId } = await createEventWithUserTypes(page);
    const front = new StorefrontPage(page);
    await front.openProduct(productId);
    // WooCommerce core hides/removes the qty input entirely for sold-individually products.
    await expect(page.locator('form.cart .qty')).toHaveCount(0);
  });

  test('adding to cart with zero ticket quantity selected shows a validation notice', async ({ page }) => {
    const { productId } = await createEventWithUserTypes(page);
    const front = new StorefrontPage(page);
    await front.openProduct(productId);
    await front.submitAddToCart();
    const text = await front.wooNoticeText();
    expect(text || '').toMatch(/select at least one ticket quantity/i);
  });

  test('Cash on Delivery gateway is not offered for event product orders', async ({ page }) => {
    const { productId } = await createEventWithUserTypes(page);
    const front = new StorefrontPage(page);
    await front.openProduct(productId);
    await front.setUserTypeQty(0, 1).catch(() => {});
    await front.submitAddToCart().catch(() => {});
    await front.openCheckout();
    await expect(page.locator('#payment li.payment_method_cod')).toHaveCount(0);
  });

  test('booking offset: event requiring N days advance booking blocks a too-late purchase', async ({ page }) => {
    // Start date is "tomorrow" while offset requires e.g. 5 days notice -> should be blocked.
    const start = new Date();
    start.setDate(start.getDate() + 1);
    const end = new Date(start.getTime() + 3 * 60 * 60 * 1000);

    const editor = new ProductEditPage(page);
    await editor.openNew();
    await page.fill(S.woo.productTitle, uniqueTitle('E2E Offset Block'));
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.fill(S.admin.startDateTime, formatEtmfwDateTime(start));
    await page.fill(S.admin.endDateTime, formatEtmfwDateTime(end));
    await page.fill(S.admin.eventVenue, 'Offset Block Venue');
    await page.fill(S.admin.bookingOffsetStartDays, '5');
    await editor.publish();
    createdProductId = editor.currentProductId();

    const front = new StorefrontPage(page);
    await front.openProduct(createdProductId);
    await front.submitAddToCart();
    const text = await front.wooNoticeText();
    expect(text || '').toMatch(/book this event at least/i);
  });
});
