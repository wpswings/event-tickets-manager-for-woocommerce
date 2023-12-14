<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/public/partials
 */

?>
<div class="wps-etmw_search-input-wrap">
	<div class="wps-etmw_search-input">
		<input type="text" name="s" id="wps-search-event" placeholder="Search event by alphabet" />
		<select name="wps_select_event_listing_type" id="wps_select_type_main">
			<option value="wps_list">List</option>
			<option value="wps_card-2">Card 2</option>
			<option value="wps_card-3">Card 3</option>
		</select>

	</div>

	<div id="wps-search-results" class="wps_list"></div>
	<div id="wps-product-loader" class="wps-etmw_search-loader">
		<img id="wps-loader" src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ); ?>public/src/image/loading.gif" alt="Loading..." class="loader">
	</div>
</div>
