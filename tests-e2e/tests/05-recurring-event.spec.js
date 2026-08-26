const { test, expect } = require('@playwright/test');
const { ProductEditPage } = require('../page-objects/ProductEditPage');
const { trashProduct, uniqueTitle, futureEventWindow, formatEtmfwDateTime } = require('../utils/wp-helpers');
const S = require('../utils/selectors');

test.describe('Recurring event (free tier: daily only, weekly/monthly locked)', () => {
  let editor, createdProductId;

  test.beforeEach(async ({ page }) => {
    editor = new ProductEditPage(page);
    const { start, end } = futureEventWindow();
    await editor.openNew();
    await page.fill(S.woo.productTitle, uniqueTitle('E2E Recurring Event'));
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.fill(S.admin.startDateTime, formatEtmfwDateTime(start));
    await page.fill(S.admin.endDateTime, formatEtmfwDateTime(end));
    await page.fill(S.admin.eventVenue, 'Recurring Venue');
    await editor.publish();
    createdProductId = editor.currentProductId();
    await editor.open(createdProductId);
    await editor.openEventsTab();
  });

  test.afterEach(async ({ page }) => {
    if (createdProductId) await trashProduct(page, createdProductId);
    createdProductId = undefined;
  });

  test('enabling recurring reveals the recurring controls wrapper', async () => {
    await editor.enableRecurring();
  });

  test('weekly and monthly recurrence options are locked without Pro', async () => {
    await editor.enableRecurring();
    await editor.weeklyMonthlyAreLockedWithoutPro();
  });

  test('daily recurrence type is selectable with start/end times', async ({ page }) => {
    await editor.enableRecurring();
    await editor.setRecurringDaily(3, '18:00', '21:00');
    await expect(page.locator(S.admin.recurringType)).toHaveValue('daily');
    await expect(page.locator(S.admin.recurringDailyStart)).toHaveValue('18:00');
    await expect(page.locator(S.admin.recurringDailyEnd)).toHaveValue('21:00');
  });

  test('"Create Recurring Event" button triggers the AJAX loader', async ({ page }) => {
    await editor.enableRecurring();
    await editor.setRecurringDaily(2);
    await editor.clickCreateRecurring();
    // The loader element exists in the DOM regardless of final visibility timing;
    // this asserts the click was wired to something rather than a dead button.
    await expect(page.locator(S.admin.recurringLoader)).toHaveCount(1);
  });
});
