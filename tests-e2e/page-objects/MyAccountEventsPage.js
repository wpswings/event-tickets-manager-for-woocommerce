const S = require('./../utils/selectors');

/** WooCommerce My Account > "Event Tickets" custom endpoint (slug: event-ticket). */
class MyAccountEventsPage {
  constructor(page) {
    this.page = page;
  }

  async open() {
    await this.page.goto('/my-account/event-ticket/');
  }

  async isMenuItemVisible() {
    return this.page.locator(S.myAccount.accountMenuEventTickets).isVisible().catch(() => false);
  }

  async dashboardVisible() {
    return this.page.locator(S.myAccount.dashboardRoot).isVisible().catch(() => false);
  }

  async openTransferTab() {
    await this.page.click(S.myAccount.transferTabButton);
  }

  async eventsTableRowCount() {
    return this.page.locator(`${S.myAccount.eventsTable} tbody tr`).count();
  }
}

module.exports = { MyAccountEventsPage };
