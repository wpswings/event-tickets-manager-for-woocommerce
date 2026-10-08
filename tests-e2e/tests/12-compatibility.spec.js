const { test, expect } = require('@playwright/test');
const { ProductEditPage } = require('../page-objects/ProductEditPage');
const { StorefrontPage } = require('../page-objects/StorefrontPage');
const { createEventProduct, trashProduct } = require('../utils/wp-helpers');
const S = require('../utils/selectors');

// Guards the WP/WC compatibility bump: the plugin must load cleanly, stay out of
// WooCommerce's "incompatible" list (it declares custom_order_tables and
// cart_checkout_blocks support in the main plugin file), and raise no PHP or JS
// errors of its own on the screens it touches.
const EXPECTED_VERSION = process.env.EXPECTED_PLUGIN_VERSION || '1.6.1';

// Minimums from the plugin header ("Requires at least" / "WC requires at least").
const MIN_WP_VERSION = '6.7';
const MIN_WC_VERSION = '6.5';

/** Numeric dotted-version compare: returns <0, 0 or >0 like strcmp. */
function compareVersions(a, b) {
  const pa = a.split('.').map(Number);
  const pb = b.split('.').map(Number);
  for (let i = 0; i < Math.max(pa.length, pb.length); i++) {
    const diff = (pa[i] || 0) - (pb[i] || 0);
    if (diff) return diff;
  }
  return 0;
}

// Matches only errors raised from this plugin's own folder — not the -pro add-on,
// and not other plugins on the site — so an unrelated notice can't fail the suite.
const OWN_PATH = /plugins\/event-tickets-manager-for-woocommerce\//;
const PHP_ERROR = /(Fatal error|Warning|Deprecated|Notice):[^\n]*plugins\/event-tickets-manager-for-woocommerce\//;

/** Collects uncaught JS errors and console errors that originate from this plugin's assets. */
function watchOwnJsErrors(page) {
  const errors = [];
  page.on('pageerror', (err) => {
    if (OWN_PATH.test(err.stack || '')) errors.push(err.message);
  });
  page.on('console', (msg) => {
    if (msg.type() === 'error' && OWN_PATH.test(msg.location().url || '')) errors.push(msg.text());
  });
  return errors;
}

async function expectNoOwnPhpErrors(page) {
  const html = await page.content();
  expect(html.match(PHP_ERROR), 'PHP error/notice raised from this plugin').toBeNull();
}

test.describe('Compatibility: plugin loads cleanly on current WP / WC', () => {
  test(`Plugins screen lists the plugin as active at version ${EXPECTED_VERSION}`, async ({ page }) => {
    await page.goto('/wp-admin/plugins.php');
    const row = page.locator(S.plugins.pluginRow);
    await expect(row).toHaveClass(/\bactive\b/);
    await expect(row.locator(S.plugins.deactivateLink)).toBeVisible();
    await expect(row).toContainText(`Version ${EXPECTED_VERSION}`);
  });

  test('plugin is not in WooCommerce\'s "incompatible with enabled features" list', async ({ page }) => {
    // WooCommerce builds this filtered view from FeaturesUtil::declare_compatibility();
    // an undeclared or false-declared feature (e.g. HPOS) would put the plugin here.
    await page.goto('/wp-admin/plugins.php?plugin_status=incompatible_with_feature');
    await expect(page.locator(S.plugins.pluginRow)).toHaveCount(0);
  });

  test(`site meets the declared minimums (WP ${MIN_WP_VERSION}+, WC ${MIN_WC_VERSION}+)`, async ({ page }) => {
    // WordPress prints its version in the generator meta tag on the front end.
    await page.goto('/');
    const generatorMeta = page.locator('meta[name="generator"][content^="WordPress"]').first();
    const generator = (await generatorMeta.count()) ? await generatorMeta.getAttribute('content') : null;
    test.skip(!generator, 'Theme/plugin removes the WordPress generator tag, cannot read the WP version');
    const wpVersion = generator.replace('WordPress', '').trim();

    await page.goto('/wp-admin/plugins.php');
    const wcRowText = await page.locator('tr[data-plugin="woocommerce/woocommerce.php"]').innerText();
    const wcVersion = (wcRowText.match(/Version\s+([\d.]+)/) || [])[1];
    expect(wcVersion, 'WooCommerce version on the Plugins screen').toBeTruthy();

    test.info().annotations.push({ type: 'environment', description: `WordPress ${wpVersion}, WooCommerce ${wcVersion}` });
    expect(compareVersions(wpVersion, MIN_WP_VERSION), `WordPress ${wpVersion} >= ${MIN_WP_VERSION}`).toBeGreaterThanOrEqual(0);
    expect(compareVersions(wcVersion, MIN_WC_VERSION), `WooCommerce ${wcVersion} >= ${MIN_WC_VERSION}`).toBeGreaterThanOrEqual(0);
  });

  test(`plugin admin assets are cache-busted with version ${EXPECTED_VERSION}`, async ({ page }) => {
    // The settings screen enqueues its admin UI stylesheet with
    // EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_VERSION as ?ver=. If that constant lags
    // behind the plugin header, browsers keep serving the previous release's assets.
    await page.goto('/wp-admin/admin.php?page=event_tickets_manager_for_woocommerce_menu');
    const adminCss = page.locator('link[rel="stylesheet"][href*="event-tickets-manager-for-woocommerce-admin-ui.css"]');
    await expect(adminCss).toHaveCount(1);
    expect(await adminCss.getAttribute('href')).toContain(`ver=${EXPECTED_VERSION}`);
  });

  test('WooCommerce Orders screen (HPOS or legacy) raises no PHP errors from the plugin', async ({ page }) => {
    // Follow the real Orders menu link so the test works whichever order storage is on:
    // admin.php?page=wc-orders with HPOS, edit.php?post_type=shop_order without.
    await page.goto('/wp-admin/');
    const ordersLink = page.locator('#adminmenu a[href*="page=wc-orders"], #adminmenu a[href*="post_type=shop_order"]').first();
    const href = await ordersLink.getAttribute('href');
    expect(href, 'WooCommerce Orders menu link').toBeTruthy();

    await page.goto(new URL(href, page.url()).toString());
    await expect(page.locator('body')).not.toContainText('Fatal error');
    await expectNoOwnPhpErrors(page);
  });

  test('WooCommerce Features settings page loads', async ({ page }) => {
    await page.goto('/wp-admin/admin.php?page=wc-settings&tab=advanced&section=features');
    await expect(page.locator('body')).not.toContainText('Fatal error');
    await expectNoOwnPhpErrors(page);
  });

  test('settings and Events admin screens raise no PHP or JS errors from the plugin', async ({ page }) => {
    const jsErrors = watchOwnJsErrors(page);

    for (const url of [
      '/wp-admin/admin.php?page=event_tickets_manager_for_woocommerce_menu',
      '/wp-admin/edit.php?post_type=product&page=wps-etmfw-events-info',
    ]) {
      await page.goto(url);
      await page.waitForLoadState('networkidle').catch(() => {});
      await expectNoOwnPhpErrors(page);
    }

    expect(jsErrors, 'JS errors from plugin assets').toEqual([]);
  });

  test('product editor Events tab raises no PHP or JS errors from the plugin', async ({ page }) => {
    const jsErrors = watchOwnJsErrors(page);
    const editor = new ProductEditPage(page);

    await editor.openNew();
    await editor.selectEventProductType();
    await editor.openEventsTab();
    await page.waitForLoadState('networkidle').catch(() => {});

    await expectNoOwnPhpErrors(page);
    expect(jsErrors, 'JS errors from plugin assets').toEqual([]);
  });

  test.describe('storefront', () => {
    let createdProductId;

    test.afterEach(async ({ page }) => {
      if (createdProductId) await trashProduct(page, createdProductId);
      createdProductId = undefined;
    });

    test('single event product page raises no PHP or JS errors from the plugin', async ({ page }) => {
      ({ productId: createdProductId } = await createEventProduct(page, { venue: 'Compat Venue' }));
      expect(createdProductId).toBeTruthy();

      const jsErrors = watchOwnJsErrors(page);
      const front = new StorefrontPage(page);
      await front.openProduct(createdProductId);
      await page.waitForLoadState('networkidle').catch(() => {});

      await expect(page.locator(S.frontend.productWrapper)).toBeVisible();
      await expectNoOwnPhpErrors(page);
      expect(jsErrors, 'JS errors from plugin assets').toEqual([]);
    });
  });
});
