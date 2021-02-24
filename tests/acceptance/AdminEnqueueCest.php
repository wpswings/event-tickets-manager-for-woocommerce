<?php 

class AdminEnqueueCest
{
    public function _before(AcceptanceTester $I)
    {
        // will be executed at the begining of each test.
        $I->loginAsAdmin();
        $I->am('administrator');
    }

    public function enqueue_script_test(AcceptanceTester $I)
    {
        $I->wantTo('Check admin script on the plugins page');
        $I->amOnAdminPage('admin.php?page=event_tickets_manager_for_woocommerce_menu');
        $I->seeInSource('event-tickets-manager-for-woocommerce-admin.js');
        $I->seeInSource('event-tickets-manager-for-woocommerce-select2.js');
    }

    public function enqueue_style_test(AcceptanceTester $I)
    {
        $I->wantTo('Check admin styles on the plugins page');
        $I->amOnAdminPage('admin.php?page=event_tickets_manager_for_woocommerce_menu');
        $I->seeInSource('event-tickets-manager-for-woocommerce-admin.scss');
        $I->seeInSource('event-tickets-manager-for-woocommerce-select2.css');
    }
}
