const { test, expect } = require('@playwright/test');
const { ProductEditPage } = require('../page-objects/ProductEditPage');
const { StorefrontPage } = require('../page-objects/StorefrontPage');
const { createEventProduct, trashProduct, uniqueTitle } = require('../utils/wp-helpers');
const S = require('../utils/selectors');

// What a shopper sees on a single event product page: the event details block,
// the ticket-type quantity picker (public/src/js/...-public.js), and custom
// attendee fields (templates/frontend/...-additional-field-html.php).
//
// Ticket types and custom fields go on separate products because both repeaters
// render their label inputs as #label_fields_N, so the ids collide on one screen.

const VENUE = 'Riverside Pavilion, Test City';
const REQUIRED_FIELD = 'Emergency Contact';

/** Sets the regular price so the product is purchasable whatever the store's defaults are. */
async function setRegularPrice(page, price) {
  await page.click(S.woo.generalTab);
  await page.fill(S.woo.priceRegular, String(price));
  await page.click(S.admin.eventsTab);
}

async function createTicketTypeEvent(page, title) {
  return createEventProduct(page, {
    title,
    venue: VENUE,
    beforePublish: async (p) => {
      const editor = new ProductEditPage(p);
      await setRegularPrice(p, 25);
      await editor.enableUserTypePricing();
      let idx = await editor.userTypeRowCount();
      await editor.addUserTypePriceRow(idx, { label: 'Adult', price: 25, stockLimit: 2 });
      idx = await editor.userTypeRowCount();
      await editor.addUserTypePriceRow(idx, { label: 'Child', price: 10 });
    },
  });
}

async function createRequiredFieldEvent(page, title) {
  return createEventProduct(page, {
    title,
    venue: VENUE,
    beforePublish: async (p) => {
      const editor = new ProductEditPage(p);
      await setRegularPrice(p, 15);
      const idx = await editor.customFieldRowCount();
      await editor.addCustomFieldRow(idx, { label: REQUIRED_FIELD, type: 'text', required: true });
    },
  });
}

test.describe('Frontend: single event product page', () => {
  let createdProductId;

  test.afterEach(async ({ page }) => {
    if (createdProductId) await trashProduct(page, createdProductId);
    createdProductId = undefined;
  });

  test('event details block shows date, time and venue', async ({ page }) => {
    ({ productId: createdProductId } = await createEventProduct(page, { venue: VENUE }));
    expect(createdProductId).toBeTruthy();

    await new StorefrontPage(page).openProduct(createdProductId);

    await expect(page.locator(S.frontend.eventInfoSection)).toBeVisible();
    // Date/time formats follow the site's WP settings, so only assert they rendered.
    await expect(page.locator(S.frontend.eventDate)).not.toBeEmpty();
    await expect(page.locator(S.frontend.eventTime)).not.toBeEmpty();
    await expect(page.locator(S.frontend.eventVenue)).toContainText('Riverside Pavilion');
  });

  test.describe('ticket-type picker', () => {
    let title;

    test.beforeEach(async ({ page }) => {
      title = uniqueTitle('E2E Ticket Picker');
      ({ productId: createdProductId } = await createTicketTypeEvent(page, title));
      expect(createdProductId).toBeTruthy();
      await new StorefrontPage(page).openProduct(createdProductId);
    });

    test('lists every ticket type with its name and price, starting at quantity 0', async ({ page }) => {
      const rows = page.locator(S.frontend.userTypeRow);
      await expect(rows).toHaveCount(2);

      await expect(rows.nth(0).locator(S.frontend.userTypeName)).toHaveText('Adult');
      await expect(rows.nth(0).locator(S.frontend.userTypePrice)).toContainText('25');
      await expect(rows.nth(1).locator(S.frontend.userTypeName)).toHaveText('Child');
      await expect(rows.nth(1).locator(S.frontend.userTypePrice)).toContainText('10');

      for (const i of [0, 1]) {
        await expect(rows.nth(i).locator(S.frontend.userTypeRowQty)).toHaveValue('0');
      }
    });

    test('plus/minus buttons change the quantity and never go below 0', async ({ page }) => {
      const child = page.locator(S.frontend.userTypeRow).nth(1);
      const qty = child.locator(S.frontend.userTypeRowQty);

      await child.locator(S.frontend.userTypePlus).click();
      await child.locator(S.frontend.userTypePlus).click();
      await expect(qty).toHaveValue('2');

      await child.locator(S.frontend.userTypeMinus).click();
      await expect(qty).toHaveValue('1');

      await child.locator(S.frontend.userTypeMinus).click();
      await child.locator(S.frontend.userTypeMinus).click();
      await expect(qty).toHaveValue('0');
    });

    test('plus button stops at the ticket type\'s stock limit', async ({ page }) => {
      const adult = page.locator(S.frontend.userTypeRow).nth(0);
      const qty = adult.locator(S.frontend.userTypeRowQty);

      await expect(qty).toHaveAttribute('max', '2');
      for (let i = 0; i < 4; i++) {
        await adult.locator(S.frontend.userTypePlus).click();
      }
      await expect(qty).toHaveValue('2');
    });

    test('choosing a ticket quantity and adding to cart puts the event in the cart', async ({ page }) => {
      const front = new StorefrontPage(page);
      await page.locator(S.frontend.userTypeRow).nth(0).locator(S.frontend.userTypePlus).click();
      await front.submitAddToCart();
      await page.waitForLoadState('load');

      await front.openCart();
      // Works for both the classic cart shortcode and the Cart block, which renders client-side.
      await expect(page.locator('body')).toContainText(title, { timeout: 20_000 });
    });
  });

  test.describe('required custom field', () => {
    test.beforeEach(async ({ page }) => {
      ({ productId: createdProductId } = await createRequiredFieldEvent(page, uniqueTitle('E2E Required Field')));
      expect(createdProductId).toBeTruthy();
      await new StorefrontPage(page).openProduct(createdProductId);
    });

    test('renders with its label, mandatory marker and required attribute', async ({ page }) => {
      const group = page.locator(S.frontend.additionalFieldGroup, { hasText: REQUIRED_FIELD });
      await expect(group.locator(S.frontend.additionalFieldLabel)).toContainText(REQUIRED_FIELD);
      await expect(group.locator(S.frontend.mandatoryMarker)).toBeVisible();
      await expect(page.locator(S.frontend.additionalFieldInput(REQUIRED_FIELD))).toHaveAttribute('required', /.*/);
    });

    test('adding to cart with the field empty is blocked by the browser', async ({ page }) => {
      const url = page.url();
      await new StorefrontPage(page).submitAddToCart();

      const input = page.locator(S.frontend.additionalFieldInput(REQUIRED_FIELD));
      await expect(input).toHaveJSProperty('validity.valueMissing', true);
      expect(page.url()).toBe(url);
      await expect(page.locator('body')).not.toContainText('has been added to your cart');
    });
  });
});
