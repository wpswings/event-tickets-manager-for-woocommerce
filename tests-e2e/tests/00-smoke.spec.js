const { test, expect } = require('@playwright/test');
const S = require('../utils/selectors');

test.describe('Smoke: plugin is active and reachable', () => {
  test('wp-admin dashboard loads with an authenticated session', async ({ page }) => {
    await page.goto('/wp-admin/');
    await expect(page).toHaveURL(/wp-admin\/?$/);
    await expect(page.locator('#wpadminbar')).toBeVisible();
  });

  test('WP Swings top-level menu and Events submenu are registered', async ({ page }) => {
    await page.goto('/wp-admin/');
    await expect(page.locator(S.admin.topMenu)).toBeVisible();
    await expect(page.locator(S.admin.eventsSubmenuLink)).toBeVisible();
    await expect(page.locator(S.admin.settingsSubmenuLink)).toBeVisible();
  });

  test('"Events" product type is registered on the Add Product screen', async ({ page }) => {
    await page.goto('/wp-admin/post-new.php?post_type=product');
    await expect(page.locator(S.admin.eventTicketOption)).toHaveText(/Events/i);
  });

  test('WooCommerce is active (required dependency)', async ({ page }) => {
    await page.goto('/wp-admin/admin.php?page=wc-status');
    await expect(page.locator('h1, h2').first()).toBeVisible();
  });
});
