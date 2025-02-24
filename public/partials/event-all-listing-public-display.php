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
<div class="wps-etmw_search-input-wrap" id="wps-etmw_search">
	<div class="wps-etmw_si-wrap">
		<div class="wps-etmw_search-input">
			<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ); ?>public/src/image/search.svg" alt="search" class="search">
			<input type="text" name="s" id="wps-search-event" placeholder="Search event..." />
		</div>
		<div class="wps_select_event_listing_type-wrap">
			<input type="radio" id="wps_list" name="wps_select_event_listing_type" value="wps_list">
			<label for="wps_list"><img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ); ?>public/src/image/list.svg" alt="list" class="list"></label>
			<input type="radio" id="wps_card" name="wps_select_event_listing_type" value="wps_card" checked>
			<label for="wps_card"><img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ); ?>public/src/image/grid.svg" alt="search" class="grid"></label>
		</div>
	</div>

	<div id="wps-search-results" class="wps_card"></div>
	<div id="wps-product-loader" class="wps-etmw_search-loader">
		<img id="wps-loader" src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ); ?>public/src/image/loading.gif" alt="Loading..." class="loader">
	</div>
</div>
