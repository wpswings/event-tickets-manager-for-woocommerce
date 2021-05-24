<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for integrations tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="mwb_etmfw_table_wrapper mwb_etmfw_overview-wrapper">
	<div class="mwb_etmfw_overview_reach-us">
		<div class=mwb_etmfw_overview_doc>
			<a href="https://docs.makewebbetter.com/event-tickets-manager-for-woocommerce/?utm_source=MWB-event-org&utm_medium=MWB-org-backend&utm_campaign=MWB-event-doc">
			<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/Documentation.svg' ); ?>">
		</a>
		</div>
		<div class=mwb_etmfw_overview_support>
			<a href="https://makewebbetter.com/submit-query/?utm_source=MWB-event-org&utm_medium=MWB-org-backend&utm_campaign=MWB-event-support">
			<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/Support.svg' ); ?>">
		</a>
		</div>
	</div>
	<div class="mwb_etmfw_overview_banner-img">
		<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/banner-img.jpg' ); ?>">
	</div>
	<div class="mwb_etmfw_overview_content">
		<h4 class="mwb_etmfw_overview_heading"><?php esc_html_e( 'What is an Event Tickets Manager For WooCommerce?', 'event-tickets-manager-for-woocommerce' ); ?></h4>
		<p><?php esc_html_e( 'Events Tickets Manager For WooCommerce is a plugin that allows merchants to add events as products on your WooCommerce store. The best part is that you can sell the tickets of your offline events without paying a single to third-party marketplace services.', 'event-tickets-manager-for-woocommerce' ); ?></p>
	</div>
	<div class="mwb_etmfw_plugin_can_do">
		<h5 class="mwb_etmfw_overview_heading"><?php esc_html_e( 'With Our Event Tickets Manager, You Can:', 'event-tickets-manager-for-woocommerce' ); ?></h5>
		<div class="mwb_emtfw_list_video_wrapper"> 
			<ol type="1">
				<li><?php esc_html_e( 'Add event-based products to your website.', 'event-tickets-manager-for-woocommerce' ); ?>
				</li>
				<li><?php esc_html_e( 'Create an event management and ticketing website.', 'event-tickets-manager-for-woocommerce' ); ?>
				</li>
				<li><?php esc_html_e( 'Show upcoming events through a calendar on your online store.', 'event-tickets-manager-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Manage the stock of tickets.', 'event-tickets-manager-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Display offline events location on google map.', 'event-tickets-manager-for-woocommerce' ); ?>
				</li>
				<?php do_action( 'mwb_etmfw_extent_plugin_feature_info' ); ?>
			</ol>
			<div class="etmfw-overview__video--url">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/hf2gImcoqqk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>
	</div>
	<div class="mwb_etmfw_plugin_benefit">
		<h5 class="mwb_etmfw_plugin_benefit_heading"><?php esc_html_e( 'Plugin Benefits', 'event-tickets-manager-for-woocommerce' ); ?></h5>

		<ol type="1">
			<li>
				<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/img6.png' ); ?>">
				<div class="mwb_etmfw_benefit_heading"><?php esc_html_e( 'Calendar Widget', 'event-tickets-manager-for-woocommerce' ); ?></div>
				<div class="mwb_etmfw_benefit_content"><?php esc_html_e( 'The plugin comes with a calendar widget to portray the upcoming events on your store to your customers.', 'event-tickets-manager-for-woocommerce' ); ?></div>
			</li>
			<li>
				<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/img11.png' ); ?>">
				<div class="mwb_etmfw_benefit_heading"><?php esc_html_e( 'Download Tickets As PDF', 'event-tickets-manager-for-woocommerce' ); ?></div>
				<div class="mwb_etmfw_benefit_content"><?php esc_html_e( 'The plugin allows the customers to download the tickets for offline use. Also, when a customer buys an event from your store the pdf is sent to the customer via email.', 'event-tickets-manager-for-woocommerce' ); ?></div>
			</li>
			<li>
				<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/img8.png' ); ?>">
				<div class="mwb_etmfw_benefit_heading"><?php esc_html_e( 'Display Location', 'event-tickets-manager-for-woocommerce' ); ?></div>
				<div class="mwb_etmfw_benefit_content"><?php esc_html_e( 'The Events Manager For WooCommerce plugin lets you display the location of your offline events with the help of Google Maps.', 'event-tickets-manager-for-woocommerce' ); ?></div>
			</li>			
			<?php do_action( 'mwb_etmfw_extent_plugin_feature_info' ); ?>
		</ol>
	</div>
	<div class="mwb_etmfw_plugin_premium">
		<h5 class="mwb_etmfw_plugin_premium_heading"><?php esc_html_e( 'Elite Features of Premium Version - Coming Soon', 'event-tickets-manager-for-woocommerce' ); ?></h5>
		<ol type="1">
			<li>
				<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/img9.png' ); ?>">
				<div class="mwb_etmfw_premium_heading"><?php esc_html_e( 'Promote Online Events', 'event-tickets-manager-for-woocommerce' ); ?></div>
				<div class="mwb_etmfw_premium_content"><?php esc_html_e( 'Promote your online webinars and other premium online events from your WooCommerce store with the help of our plugin.', 'event-tickets-manager-for-woocommerce' ); ?></div>
			</li>
			<li>
				<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/img7.png' ); ?>">	
				<div class="mwb_etmfw_premium_heading"><?php esc_html_e( 'Buy Multiple Tickets For Single Event', 'event-tickets-manager-for-woocommerce' ); ?></div>
				<div class="mwb_etmfw_premium_content"><?php esc_html_e( 'The customers will be able to buy multiple tickets for a single event in your store.', 'event-tickets-manager-for-woocommerce' ); ?></div>
			</li>
			<li>
				<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/img10.png' ); ?>">	
				<div class="mwb_etmfw_premium_heading"><?php esc_html_e( 'APIs To Verify Tickets', 'event-tickets-manager-for-woocommerce' ); ?></div>
				<div class="mwb_etmfw_premium_content"><?php esc_html_e( 'APIs that allow the admin to log in on mobile apps (android/iOS) and scan QR codes at physical events to verify the ticket.', 'event-tickets-manager-for-woocommerce' ); ?></div>
			</li>
			<li>
				<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/img5.png' ); ?>">	
				<div class="mwb_etmfw_premium_heading"><?php esc_html_e( 'Allow Attendees To Pass The Ticket', 'event-tickets-manager-for-woocommerce' ); ?></div>
				<div class="mwb_etmfw_premium_content"><?php esc_html_e( 'The customers can pass the ticket they have bought to their friends or family if they are not able to visit your event.', 'event-tickets-manager-for-woocommerce' ); ?></div>
			</li>
			<li>
				<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/img12.png' ); ?>">	
				<div class="mwb_etmfw_premium_heading"><?php esc_html_e( 'Reporting With Multiple Views', 'event-tickets-manager-for-woocommerce' ); ?></div>
				<div class="mwb_etmfw_premium_content"><?php esc_html_e( 'The plugin provides a report of how the events in your store are performing. You can filter the report event-wise and attendee-wise.', 'event-tickets-manager-for-woocommerce' ); ?></div>
			</li>			
			<?php do_action( 'mwb_etmfw_extent_plugin_feature_info' ); ?>
		</ol>
	</div>
</div>
<?php

