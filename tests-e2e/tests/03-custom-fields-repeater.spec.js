const { test, expect } = require('@playwright/test');
const { ProductEditPage } = require('../page-objects/ProductEditPage');
const { trashProduct, uniqueTitle, futureEventWindow, formatEtmfwDateTime } = require('../utils/wp-helpers');
const S = require('../utils/selectors');

test.describe('Custom fields repeater (Events tab)', () => {
  let editor, createdProductId;

  test.beforeEach(async ({ page }) => {
    editor = new ProductEditPage(page);
    const { start, end } = futureEventWindow();
    await editor.openNew();
    await page.fill(S.woo.productTitle, uniqueTitle('E2E Custom Fields'));
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.fill(S.admin.startDateTime, formatEtmfwDateTime(start));
    await page.fill(S.admin.endDateTime, formatEtmfwDateTime(end));
    await page.fill(S.admin.eventVenue, 'Custom Fields Venue');
  });

  test.afterEach(async ({ page }) => {
    if (createdProductId) await trashProduct(page, createdProductId);
    createdProductId = undefined;
  });

  test('adding a row grows the repeater table', async ({ page }) => {
    const before = await editor.customFieldRowCount();
    await editor.addCustomFieldRow(before, { label: 'T-Shirt Size', type: 'text' });
    const after = await editor.customFieldRowCount();
    expect(after).toBeGreaterThan(before);
  });

  test('each field type option is selectable (text, textarea, email, number, date, yes-no)', async ({ page }) => {
    const before = await editor.customFieldRowCount();
    await editor.addCustomFieldRow(before, { label: 'Dietary Notes', type: 'textarea' });
    await expect(page.locator(S.admin.customFieldType(before))).toHaveValue('textarea');

    for (const type of ['email', 'number', 'date', 'yes-no']) {
      await page.selectOption(S.admin.customFieldType(before), type);
      await expect(page.locator(S.admin.customFieldType(before))).toHaveValue(type);
    }
  });

  test('required checkbox can be toggled on a row', async ({ page }) => {
    const before = await editor.customFieldRowCount();
    await editor.addCustomFieldRow(before, { label: 'Emergency Contact', type: 'text', required: true });
    await expect(page.locator(S.admin.customFieldRequired(before))).toBeChecked();
  });

  test('removing a row shrinks the repeater table', async ({ page }) => {
    const before = await editor.customFieldRowCount();
    await editor.addCustomFieldRow(before, { label: 'Temp Row', type: 'text' });
    const afterAdd = await editor.customFieldRowCount();
    await editor.removeLastCustomFieldRow();
    await page.waitForTimeout(300);
    const afterRemove = await editor.customFieldRowCount();
    expect(afterRemove).toBeLessThan(afterAdd);
  });

  test('custom fields persist after publish and reopening the product', async ({ page }) => {
    const before = await editor.customFieldRowCount();
    await editor.addCustomFieldRow(before, { label: 'Persisted Field', type: 'text', required: true });
    await editor.publish();
    createdProductId = editor.currentProductId();

    await editor.open(createdProductId);
    await editor.openEventsTab();
    await expect(page.locator(`${S.admin.customFieldsBody} tr`).first()).toBeVisible();
    await expect(page.locator(S.admin.customFieldsBody)).toContainText('Persisted Field');
  });
});
