// Central selector map, transcribed verbatim from a full static read of the plugin's
// PHP/template source (admin/class-event-tickets-manager-for-woocommerce-admin.php,
// public/class-event-tickets-manager-for-woocommerce-public.php, and templates/**).
// Keeping every selector in one place means a markup change only needs one edit here.

module.exports = {
  admin: {
    topMenu: '#toplevel_page_wps-plugins',
    eventsSubmenuLink: '#adminmenu a[href*="page=wps-etmfw-events-info"]',
    settingsSubmenuLink: '#adminmenu a[href*="page=event_tickets_manager_for_woocommerce_menu"]',
    settingsTabLink: (tab) => `a[href*="etmfw_tab=${tab}"]`,

    // Product data > "Events" tab
    productTypeSelect: '#product-type',
    eventTicketOption: '#product-type option[value="event_ticket_manager"]',
    eventsTab: 'li.event_ticket_options a',
    eventsPanel: '#wps_etmfw_event_data',
    startDateTime: '#etmfw_start_date_time',
    bookingOffsetStartDays: '#etmfw_booking_offset_start_days',
    endDateTime: '#etmfw_end_date_time',
    bookingOffsetEndDays: '#etmfw_booking_offset_end_days',
    eventVenue: '#etmfw_event_venue',
    eventVenueLat: '#etmfw_event_venue_lat',
    eventVenueLng: '#etmfw_event_venue_lng',
    trashEventCheckbox: '#etmfw_event_trash_event',
    displayMapCheckbox: '#etmfw_display_map',
    recurringEnableCheckbox: '#etmfwp_recurring_event_enable',
    productNonceField: '#wps_etmfw_product_nonce_field',

    // Custom fields repeater
    customFieldsTable: 'table.wps_etmfw_field_table',
    customFieldsBody: 'tbody.wps_etmfw_field_body',
    customFieldRow: (n) => `tr.wps_etmfw_field_wrap[data-id="${n}"]`,
    addCustomFieldButton: 'input.wps_etmfw_add_fields_button',
    removeCustomFieldButton: 'input.wps_etmfw_remove_row_btn',
    customFieldLabel: (n) => `#label_fields_${n}`,
    customFieldType: (n) => `#type_fields_${n}`,
    customFieldRequired: (n) => `#required_fields_${n}`,

    // User-type pricing repeater
    basePriceRadio: '#wps_etmfw_base_price',
    notBasePriceRadio: '#wps_etmfw_not_base_price',
    userTypeTable: 'table.wps_etmfwpp_user_field_table',
    userTypeBody: 'tbody.wps_etmfwpp_user_field_body',
    userTypeRow: (n) => `tr.wps_etmfwpp_user_field_wrap[data-id="${n}"]`,
    addUserTypeButton: 'input.wps_etmfwppp_user_add_fields_button',
    removeUserTypeButton: 'input.wps_user_type_remove',
    userTypeLabel: (n) => `#label_fields_${n}`,
    userTypePrice: (n) => `#price_fields_${n}`,
    userTypeStockLimit: (n) => `#stock_limit_fields_${n}`,
    userTypeInventoryError: 'div.wps_etmfwpp_inventory_error',

    // Recurring event
    recurringWrapper: '#wps_main_recurring_wrapper_id',
    recurringValue: '#wps_recurring_value_id',
    recurringType: '#wps_recurring_type',
    recurringDailyStart: 'input.wps_event_daily_start_time',
    recurringDailyEnd: 'input.wps_event_daily_end_time',
    createRecurringButton: '#wps_etmfw_create_recurring_id',
    deleteRecurringButton: '#wps_etmfw_delete_create_recurring_id',
    recurringLoader: '#wps_recurring_loader',

    // Events list (wps-etmfw-events-info)
    eventsListForm: 'form',
    eventsBulkActionSelect: 'select[name="action"]',
    eventsRowCheckbox: 'input[name="wps_etmfw_event_ids[]"]',
    eventsSelectAllCheckbox: '#cb-select-all-1',
    eventsFilterSelect: 'select[name="wps_export_select_events"]',
    publishButton: '#publish',
    updateButton: '#publish',
  },

  frontend: {
    productWrapper: '.wps_etmfw_product_wrapper',
    eventInfoSection: '.wps_etmfw_event_info_section',
    eventDate: '#wps_etmwf_event_date .wps_etmfw_date_label',
    eventTime: '#wps_etmwf_event_time .wps_etmfw_date_label',
    eventVenue: '#wps_etmwf_event_venue',
    userTypeRow: '.wps_etmfw_user_type_list .wps_etmfw_user_type_row',
    userTypeMinus: 'button.wps-etmfw-minus',
    userTypePlus: 'button.wps-etmfw-plus',
    userTypeQtyInput: (n) => `input.qty[name="wps_etmfw_user_type_qty[${n}]"]`,
    eventMapIframe: '#wps_etmfw_event_map',
    expiredMessage: '.etmfw_expiration_message',
    addToCartButton: '.single_add_to_cart_button',
    dynamicFormWrapper: (productId) => `#wps_etmfw_dynamic_form_fr_${productId}`,
    dynamicFormTotalMembers: '#wps_total_member',
    dynamicFormTotalPrice: '#wps_total_price',
    dynamicFormAddMore: '#wps_add_more_people',
    viewTicketSection: '.wps_etmfw_view_ticket_section',
    calendarSection: '.wps_etmfw_calendar_section',
    additionalFieldGroup: '.wps-form-group',
    additionalFieldLabel: '.wps_etmfw_field_label',
    mandatoryMarker: '.wps_etmfw_mandatory_fields',
    viewTicketPdfLink: 'a.wps_view_ticket_pdf',
    addToCalendarLink: 'a.wps_etmfw_add_event_calendar',

    // Checkin shortcode
    checkinForm: 'form.wps-etmfw-checkin-form',
    checkinEventSelect: '#wps_etmfw_event_selected',
    checkinTicketInput: '#wps_etmfw_imput_ticket',
    checkinEmailInput: '#wps_etmfw_chckin_email',
    checkinSubmitButton: '#wps_etmfw_checkin_button',
    checkinMessage: '#wps_etmfw_error_message',
    checkinLoader: '#wps_etmfw_checkin_loader',
  },

  myAccount: {
    dashboardRoot: '#wps-etmfw_modern-dashboard',
    heroStats: '.wps-etmfw_dashboard-hero__stats > .wps-etmfw_dashboard-stat',
    eventsTabButton: '.wps-etmfw_mdisan-item.wps-etmfw_mdisant-events',
    transferTabButton: '.wps-etmfw_mdisan-item.wps-etmfw_mdisant-trans',
    transferForm: '.wps-etmfw-transfer-form',
    eventsTable: 'table#wps_myevent_table_id.woocommerce-orders-table',
    statusBadge: (status) => `.wps-etmfw-status-badge--${status}`,
    viewOrderLink: '.wps-etmfw-action-link--view',
    downloadPdfLink: '.wps-etmfw-action-link--download',
    accountMenuEventTickets: 'nav.woocommerce-MyAccount-navigation a[href*="event-ticket"]',
  },

  woo: {
    // Core WooCommerce selectors we rely on, not plugin-specific.
    productTitle: '#title',
    priceRegular: '#_regular_price',
    productDataTabsList: '.product_data_tabs',
    generalTab: '.general_tab a',
    variationsNotice: '.woocommerce-message, .woocommerce-error, .woocommerce-info',
    cartTable: 'table.shop_table.cart',
    checkoutPlaceOrderButton: '#place_order',
    orderReceivedTitle: '.woocommerce-order',
    orderStatusSelect: '#order_status',
    updateOrderButton: 'button.save_order, button[name="save"]',
  },
};
