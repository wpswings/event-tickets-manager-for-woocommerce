const { test, expect } = require('@playwright/test');
const { MyAccountEventsPage } = require('../page-objects/MyAccountEventsPage');
const S = require('../utils/selectors');

test.describe('My Account > Event Tickets endpoint', () => {
  test('the "Event Tickets" menu item appears in My Account navigation', async ({ page }) => {
    await page.goto('/my-account/');
    const acc = new MyAccountEventsPage(page);
    await expect(page.locator(S.myAccount.accountMenuEventTickets)).toBeVisible();
  });

  test('the event-ticket endpoint renders the dashboard without a fatal error', async ({ page }) => {
    const acc = new MyAccountEventsPage(page);
    await acc.open();
    await expect(page.locator('body')).not.toContainText('Fatal error');
    await expect(page.locator(S.myAccount.dashboardRoot)).toBeVisible();
  });

  test('the events table is present (even if empty for this account)', async ({ page }) => {
    const acc = new MyAccountEventsPage(page);
    await acc.open();
    await expect(page.locator(S.myAccount.eventsTable)).toHaveCount(1);
  });
});
