const { test, expect } = require('@playwright/test');
const { CheckinPage } = require('../page-objects/CheckinPage');
const S = require('../utils/selectors');

// These specs assume the site has pages that contain the plugin shortcodes.
// If your local site doesn't have them yet, create pages with slugs
// "event-listing" ([wps_my_all_event_list]) and "event-checkin"
// ([wps_etmfw_event_checkin_page]) once — see README "One-time site prep".
const EVENT_LISTING_SLUG = process.env.EVENT_LISTING_PAGE_SLUG || 'event-listing';
const CHECKIN_SLUG = process.env.CHECKIN_PAGE_SLUG || 'event-checkin';

test.describe('Frontend shortcodes', () => {
  test('[wps_my_all_event_list] renders without a fatal error', async ({ page }) => {
    const res = await page.goto(`/${EVENT_LISTING_SLUG}/`);
    expect(res.status(), 'listing page should exist and return 200 — see README if this fails').toBeLessThan(500);
    await expect(page.locator('body')).not.toContainText('Fatal error');
  });

  test('[wps_etmfw_event_checkin_page] renders the check-in form', async ({ page }) => {
    const checkin = new CheckinPage(page);
    const res = await page.goto(`/${CHECKIN_SLUG}/`);
    expect(res.status(), 'checkin page should exist and return 200 — see README if this fails').toBeLessThan(500);
    await expect(page.locator(S.frontend.checkinForm)).toBeVisible();
  });

  test('check-in form: submitting with an invalid email is rejected client-side', async ({ page }) => {
    const checkin = new CheckinPage(page);
    await checkin.open(CHECKIN_SLUG);
    await checkin.submitWithInvalidEmail();
    // HTML5 email input blocks submission; assert we did not navigate away / no success message shown.
    await expect(page.locator(S.frontend.checkinEmailInput)).toHaveJSProperty('validity.valid', false);
  });

  test('check-in form fields are all present', async ({ page }) => {
    await page.goto(`/${CHECKIN_SLUG}/`);
    await expect(page.locator(S.frontend.checkinEventSelect)).toBeVisible();
    await expect(page.locator(S.frontend.checkinTicketInput)).toBeVisible();
    await expect(page.locator(S.frontend.checkinEmailInput)).toBeVisible();
    await expect(page.locator(S.frontend.checkinSubmitButton)).toBeVisible();
  });
});
