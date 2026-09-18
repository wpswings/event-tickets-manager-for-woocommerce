const { expect } = require('@playwright/test');
const S = require('./../utils/selectors');

/** Single event product page + cart/checkout flow on the storefront. */
class StorefrontPage {
  constructor(page) {
    this.page = page;
  }

  async openProduct(productId) {
    await this.page.goto(`/?p=${productId}`);
  }

  async addToCartButtonVisible() {
    return this.page.locator(S.frontend.addToCartButton).isVisible().catch(() => false);
  }

  async expiredMessageVisible() {
    return this.page.locator(S.frontend.expiredMessage).isVisible().catch(() => false);
  }

  async setUserTypeQty(index, qty) {
    const input = this.page.locator(S.frontend.userTypeQtyInput(index));
    await input.fill(String(qty));
  }

  async submitAddToCart() {
    await this.page.click(S.frontend.addToCartButton);
  }

  async wooNoticeText() {
    const notice = this.page.locator(S.woo.variationsNotice).first();
    await notice.waitFor({ state: 'visible', timeout: 10_000 }).catch(() => {});
    return notice.textContent();
  }

  async goToCart() {
    await this.page.goto('/?page_id=0&post_type=page'); // placeholder, real nav below
  }

  async openCart() {
    await this.page.goto('/cart/');
  }

  async openCheckout() {
    await this.page.goto('/checkout/');
  }

  async fillCheckoutBillingMinimal({ first = 'E2E', last = 'Tester', email = `e2e${Date.now()}@example.test`, phone = '5555550100', address = '123 Test St', city = 'Testville', zip = '90210', country } = {}) {
    const p = this.page;
    await p.fill('#billing_first_name', first).catch(() => {});
    await p.fill('#billing_last_name', last).catch(() => {});
    await p.fill('#billing_email', email).catch(() => {});
    await p.fill('#billing_phone', phone).catch(() => {});
    await p.fill('#billing_address_1', address).catch(() => {});
    await p.fill('#billing_city', city).catch(() => {});
    await p.fill('#billing_postcode', zip).catch(() => {});
    if (country) {
      await p.selectOption('#billing_country', country).catch(() => {});
    }
  }

  async placeOrder() {
    await this.page.click(S.woo.checkoutPlaceOrderButton);
  }

  async expectOrderReceived() {
    await expect(this.page.locator(S.woo.orderReceivedTitle)).toBeVisible({ timeout: 20_000 });
  }
}

module.exports = { StorefrontPage };
