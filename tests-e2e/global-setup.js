// Logs into wp-admin once and reuses the session for every test (via storageState),
// exactly like WooCommerce core's own E2E suite does.
require('dotenv').config();
const { chromium } = require('@playwright/test');
const path = require('path');
const fs = require('fs');

module.exports = async () => {
  const baseURL = process.env.BASE_URL || 'http://localhost:10066';
  const user = process.env.WP_ADMIN_USER || 'admin';
  const password = process.env.WP_ADMIN_PASSWORD || 'admin';

  const authDir = path.join(__dirname, 'playwright', '.auth');
  fs.mkdirSync(authDir, { recursive: true });
  const storagePath = path.join(authDir, 'admin.json');

  const browser = await chromium.launch();
  const page = await browser.newPage();

  await page.goto(`${baseURL}/wp-login.php`);
  await page.fill('#user_login', user);
  await page.fill('#user_pass', password);
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'load' }),
    page.click('#wp-submit'),
  ]);

  // Sanity check: we should have landed on wp-admin, not be looking at the login form again.
  if (page.url().includes('wp-login.php')) {
    await browser.close();
    throw new Error(
      `Global setup could not log into ${baseURL}/wp-admin as "${user}". ` +
      'Check BASE_URL / WP_ADMIN_USER / WP_ADMIN_PASSWORD in your .env file.'
    );
  }

  await page.context().storageState({ path: storagePath });
  await browser.close();
};
