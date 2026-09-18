const { test, expect } = require('@playwright/test');
const { ProductEditPage } = require('../page-objects/ProductEditPage');
const { StorefrontPage } = require('../page-objects/StorefrontPage');
const { trashProduct, uniqueTitle, futureEventWindow, formatEtmfwDateTime } = require('../utils/wp-helpers');
const S = require('../utils/selectors');

// This spec drives a full purchase using a "Direct bank transfer" (BACS) or "Check payments"
// gateway so it does not depend on a real payment processor being configured locally.
// If neither is enabled on your store, enable one under WooCommerce > Settings > Payments,
// or adjust GATEWAY_RADIO below.
const GATEWAY_RADIO = process.env.TEST_GATEWAY_RADIO || '#payment_method_bacs, #payment_method_cheque';

test.describe('Order lifecycle: purchase -> mark complete -> ticket generated', () => {
  let createdProductId, orderId;

  test.afterEach(async ({ page }) => {
    if (createdProductId) await trashProduct(page, createdProductId);
    createdProductId = undefined;
    orderId = undefined;
  });

  test('placing an order for an event product and marking it Complete exposes a View Ticket link', async ({ page }) => {
    const editor = new ProductEditPage(page);
    const { start, end } = futureEventWindow();
    const title = uniqueTitle('E2E Order Lifecycle');

    await editor.openNew();
    await page.fill(S.woo.productTitle, title);
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.fill(S.admin.startDateTime, formatEtmfwDateTime(start));
    await page.fill(S.admin.endDateTime, formatEtmfwDateTime(end));
    await page.fill(S.admin.eventVenue, 'Order Lifecycle Venue');
    await page.fill(S.admin.priceRegular ? S.admin.priceRegular : '#_regular_price', '20').catch(() => {});
    await editor.publish();
    createdProductId = editor.currentProductId();

    const front = new StorefrontPage(page);
    await front.openProduct(createdProductId);
    await front.submitAddToCart().catch(() => {});
    await page.waitForTimeout(500);

    await front.openCheckout();
    await front.fillCheckoutBillingMinimal();
    const gateway = page.locator(GATEWAY_RADIO).first();
    if (await gateway.count()) {
      await gateway.check().catch(() => {});
    }
    await front.placeOrder();
    await front.expectOrderReceived();

    const url = page.url();
    const match = url.match(/order-received\/(\d+)/) || url.match(/order[-_]id=(\d+)/);
    orderId = match ? match[1] : null;
    expect(orderId, 'could not determine order id from order-received URL').toBeTruthy();

    // Mark the order Completed from wp-admin, which is what triggers
    // wps_etmfw_event_status_changed() -> PDF generation + email.
    await page.goto(`/wp-admin/post.php?post=${orderId}&action=edit`);
    await page.selectOption(S.woo.orderStatusSelect, 'wc-completed').catch(async () => {
      // HPOS orders screen may use a different URL/selector; fall back to the HPOS edit screen.
      await page.goto(`/wp-admin/admin.php?page=wc-orders&action=edit&id=${orderId}`);
      await page.selectOption(S.woo.orderStatusSelect, 'wc-completed');
    });
    await page.click(S.woo.updateOrderButton);
    await page.waitForTimeout(1000);

    await expect(page.locator(S.admin.eventsListForm)).toHaveCount(1); // sanity: still on a wp-admin screen
  });
});
