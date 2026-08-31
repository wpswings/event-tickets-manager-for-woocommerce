const S = require('./../utils/selectors');

/** wp-admin > WooCommerce > Events (wps-etmfw-events-info) list screen. */
class EventsAdminPage {
  constructor(page) {
    this.page = page;
  }

  async open() {
    await this.page.goto('/wp-admin/edit.php?post_type=product&page=wps-etmfw-events-info');
  }

  async rowForTitle(title) {
    return this.page.locator('tr', { hasText: title });
  }

  async selectAllRows() {
    await this.page.check(S.admin.eventsSelectAllCheckbox).catch(() => {});
  }

  async bulkDelete() {
    await this.page.selectOption(S.admin.eventsBulkActionSelect, 'bulk-delete');
    this.page.once('dialog', (d) => d.accept());
    await this.page.click('#doaction');
  }
}

module.exports = { EventsAdminPage };
