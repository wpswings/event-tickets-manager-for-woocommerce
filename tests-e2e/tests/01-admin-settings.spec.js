const { test, expect } = require('@playwright/test');
const S = require('../utils/selectors');

// Tab keys straight from wps_etmfw_plug_default_tabs() in
// includes/class-event-tickets-manager-for-woocommerce.php
const TABS = [
  'event-tickets-manager-for-woocommerce-overview',
  'event-tickets-manager-for-woocommerce-general',
  'event-tickets-manager-for-woocommerce-email-template',
  'event-tickets-manager-for-woocommerce-ticket-layout-setting',
  'event-tickets-manager-for-woocommerce-other-settings',
  'event-tickets-manager-for-woocommerce-dashboard-settings',
  'event-tickets-manager-for-woocommerce-integrations',
  'event-tickets-manager-for-woocommerce-system-status',
];

test.describe('Admin settings screen', () => {
  test('settings page loads under the WP Swings menu', async ({ page }) => {
    await page.goto('/wp-admin/admin.php?page=event_tickets_manager_for_woocommerce_menu');
    await expect(page).toHaveURL(/page=event_tickets_manager_for_woocommerce_menu/);
    await expect(page.locator('body')).not.toContainText('You do not have sufficient permissions');
  });

  for (const tab of TABS) {
    const tabLabel = tab.replace('event-tickets-manager-for-woocommerce-', '');
    test(`"${tabLabel}" tab is reachable`, async ({ page }) => {
      await page.goto(
        `/wp-admin/admin.php?page=event_tickets_manager_for_woocommerce_menu&etmfw_tab=${tab}`
      );
      await expect(page.locator('body')).not.toContainText('Fatal error');
      await expect(page.locator('body')).not.toContainText('You do not have sufficient permissions');
    });
  }

  test('Events (recurring) admin screen loads', async ({ page }) => {
    await page.goto('/wp-admin/edit.php?post_type=product&page=wps-etmfw-recurring-events-info');
    await expect(page.locator('body')).not.toContainText('Fatal error');
  });
});
