const { expect } = require('@playwright/test');
const S = require('./../utils/selectors');

/** Wraps the wp-admin "Edit Product" screen's Events tab and its repeaters. */
class ProductEditPage {
  constructor(page) {
    this.page = page;
  }

  async open(productId) {
    await this.page.goto(`/wp-admin/post.php?post=${productId}&action=edit`);
  }

  async openNew() {
    await this.page.goto('/wp-admin/post-new.php?post_type=product');
  }

  async selectEventProductType() {
    await this.page.selectOption(S.admin.productTypeSelect, 'event_ticket_manager');
  }

  async openEventsTab() {
    await this.page.click(S.admin.eventsTab);
    await expect(this.page.locator(S.admin.eventsPanel)).toBeVisible();
  }

  async isEventsTabVisible() {
    return this.page.locator(S.admin.eventsTab).isVisible();
  }

  async otherTabsHaveHideClass() {
    // WooCommerce toggles li.hide_if_event_ticket_manager to display:none via its own JS;
    // we just assert the class is present, which is what the plugin actually controls.
    const generalTabLi = this.page.locator('.product_data_tabs li.general_options');
    await expect(generalTabLi).toHaveClass(/hide_if_event_ticket_manager/);
  }

  async addCustomFieldRow(index, { label, type = 'text', required = false }) {
    await this.page.click(S.admin.addCustomFieldButton);
    const row = this.page.locator(S.admin.customFieldRow(index));
    await expect(row).toBeVisible();
    await this.page.fill(S.admin.customFieldLabel(index), label);
    await this.page.selectOption(S.admin.customFieldType(index), type);
    if (required) {
      await this.page.check(S.admin.customFieldRequired(index));
    }
  }

  async removeLastCustomFieldRow() {
    const removeButtons = this.page.locator(S.admin.removeCustomFieldButton);
    await removeButtons.last().click();
  }

  async customFieldRowCount() {
    return this.page.locator(`${S.admin.customFieldsBody} tr`).count();
  }

  async enableUserTypePricing() {
    await this.page.check(S.admin.notBasePriceRadio);
  }

  async addUserTypePriceRow(index, { label, price, stockLimit }) {
    await this.page.click(S.admin.addUserTypeButton);
    const row = this.page.locator(S.admin.userTypeRow(index));
    await expect(row).toBeVisible();
    await this.page.fill(S.admin.userTypeLabel(index), label);
    await this.page.fill(S.admin.userTypePrice(index), String(price));
    if (stockLimit !== undefined) {
      await this.page.fill(S.admin.userTypeStockLimit(index), String(stockLimit));
    }
  }

  async userTypeRowCount() {
    return this.page.locator(`${S.admin.userTypeBody} tr`).count();
  }

  async enableRecurring() {
    await this.page.check(S.admin.recurringEnableCheckbox);
    await expect(this.page.locator(S.admin.recurringWrapper)).toBeVisible();
  }

  async setRecurringDaily(value, startTime = '18:00', endTime = '21:00') {
    await this.page.fill(S.admin.recurringValue, String(value));
    await this.page.selectOption(S.admin.recurringType, 'daily');
    await this.page.fill(S.admin.recurringDailyStart, startTime);
    await this.page.fill(S.admin.recurringDailyEnd, endTime);
  }

  async weeklyMonthlyAreLockedWithoutPro() {
    const type = this.page.locator(S.admin.recurringType);
    const weeklyOption = type.locator('option[value="weekly"]');
    const monthlyOption = type.locator('option[value="monthly"]');
    await expect(weeklyOption).toHaveClass(/disabled-pro-option/);
    await expect(monthlyOption).toHaveClass(/disabled-pro-option/);
  }

  async clickCreateRecurring() {
    await this.page.click(S.admin.createRecurringButton);
  }

  async clickDeleteRecurring() {
    this.page.once('dialog', (d) => d.accept());
    await this.page.click(S.admin.deleteRecurringButton);
  }

  async publish() {
    await this.page.click(S.admin.publishButton);
    await this.page
      .waitForSelector('#message.updated, .notice-success', { timeout: 20_000 })
      .catch(() => {});
  }

  async update() {
    await this.page.click(S.admin.updateButton);
    await this.page
      .waitForSelector('#message.updated, .notice-success', { timeout: 20_000 })
      .catch(() => {});
  }

  currentProductId() {
    const match = this.page.url().match(/post=(\d+)/);
    return match ? match[1] : null;
  }
}

module.exports = { ProductEditPage };
