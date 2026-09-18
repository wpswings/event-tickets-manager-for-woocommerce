const S = require('./../utils/selectors');

/** Any page containing the [wps_etmfw_event_checkin_page] shortcode. */
class CheckinPage {
  constructor(page) {
    this.page = page;
  }

  async open(pageSlug) {
    await this.page.goto(`/${pageSlug}/`);
  }

  async formVisible() {
    return this.page.locator(S.frontend.checkinForm).isVisible().catch(() => false);
  }

  async submitEmpty() {
    await this.page.click(S.frontend.checkinSubmitButton);
  }

  async submitWithInvalidEmail(ticket = '12345') {
    await this.page.fill(S.frontend.checkinTicketInput, ticket);
    await this.page.fill(S.frontend.checkinEmailInput, 'not-an-email');
    await this.page.click(S.frontend.checkinSubmitButton);
  }

  async messageText() {
    return this.page.locator(S.frontend.checkinMessage).textContent().catch(() => '');
  }
}

module.exports = { CheckinPage };
