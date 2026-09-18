const { test, expect } = require('@playwright/test');
const { ProductEditPage } = require('../page-objects/ProductEditPage');
const { trashProduct, uniqueTitle, futureEventWindow, formatEtmfwDateTime } = require('../utils/wp-helpers');
const S = require('../utils/selectors');

test.describe('User-type pricing repeater (Events tab)', () => {
  let editor, createdProductId;

  test.beforeEach(async ({ page }) => {
    editor = new ProductEditPage(page);
    const { start, end } = futureEventWindow();
    await editor.openNew();
    await page.fill(S.woo.productTitle, uniqueTitle('E2E User Type Pricing'));
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.fill(S.admin.startDateTime, formatEtmfwDateTime(start));
    await page.fill(S.admin.endDateTime, formatEtmfwDateTime(end));
    await page.fill(S.admin.eventVenue, 'Pricing Venue');
  });

  test.afterEach(async ({ page }) => {
    if (createdProductId) await trashProduct(page, createdProductId);
    createdProductId = undefined;
  });

  test('switching to "not base price" reveals the price-rule table', async ({ page }) => {
    await editor.enableUserTypePricing();
    await expect(page.locator(S.admin.userTypeTable)).toBeVisible();
  });

  test('adding a price rule row with label/price/stock limit', async ({ page }) => {
    await editor.enableUserTypePricing();
    const before = await editor.userTypeRowCount();
    await editor.addUserTypePriceRow(before, { label: 'Adult', price: 49.99, stockLimit: 100 });
    const after = await editor.userTypeRowCount();
    expect(after).toBeGreaterThan(before);
    await expect(page.locator(S.admin.userTypePrice(before))).toHaveValue('49.99');
  });

  test('multiple ticket types (Adult / Child / VIP) can be added', async ({ page }) => {
    await editor.enableUserTypePricing();
    let idx = await editor.userTypeRowCount();
    await editor.addUserTypePriceRow(idx, { label: 'Adult', price: 49.99, stockLimit: 100 });
    idx = await editor.userTypeRowCount();
    await editor.addUserTypePriceRow(idx, { label: 'Child', price: 24.99, stockLimit: 50 });
    idx = await editor.userTypeRowCount();
    await editor.addUserTypePriceRow(idx, { label: 'VIP', price: 149.99, stockLimit: 10 });

    await expect(page.locator(S.admin.userTypeBody)).toContainText('Adult');
    await expect(page.locator(S.admin.userTypeBody)).toContainText('Child');
    await expect(page.locator(S.admin.userTypeBody)).toContainText('VIP');
  });

  test('stock limit field accepts "Unlimited" placeholder (empty value allowed)', async ({ page }) => {
    await editor.enableUserTypePricing();
    const idx = await editor.userTypeRowCount();
    await editor.addUserTypePriceRow(idx, { label: 'General Admission', price: 19.99 });
    await expect(page.locator(S.admin.userTypeStockLimit(idx))).toHaveAttribute('placeholder', 'Unlimited');
  });

  test('removing a price rule row shrinks the table', async ({ page }) => {
    await editor.enableUserTypePricing();
    const before = await editor.userTypeRowCount();
    await editor.addUserTypePriceRow(before, { label: 'Temp Type', price: 9.99 });
    const afterAdd = await editor.userTypeRowCount();
    await page.locator(S.admin.removeUserTypeButton).last().click();
    await page.waitForTimeout(300);
    const afterRemove = await editor.userTypeRowCount();
    expect(afterRemove).toBeLessThan(afterAdd);
  });

  test('pricing rules persist after publish and reopening the product', async ({ page }) => {
    await editor.enableUserTypePricing();
    const idx = await editor.userTypeRowCount();
    await editor.addUserTypePriceRow(idx, { label: 'Persisted Type', price: 33.5, stockLimit: 20 });
    await editor.publish();
    createdProductId = editor.currentProductId();

    await editor.open(createdProductId);
    await editor.openEventsTab();
    await expect(page.locator(S.admin.notBasePriceRadio)).toBeChecked();
    await expect(page.locator(S.admin.userTypeBody)).toContainText('Persisted Type');
  });
});
