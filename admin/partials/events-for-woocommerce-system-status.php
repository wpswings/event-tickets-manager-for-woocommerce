<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html for system status.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Events_For_Woocommerce
 * @subpackage Events_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Template for showing information about system status.
global $efw_mwb_efw_obj;
$efw_default_status = $efw_mwb_efw_obj->mwb_efw_plug_system_status();
$efw_wordpress_details = is_array( $efw_default_status['wp'] ) && ! empty( $efw_default_status['wp'] ) ? $efw_default_status['wp'] : array();
$efw_php_details = is_array( $efw_default_status['php'] ) && ! empty( $efw_default_status['php'] ) ? $efw_default_status['php'] : array();
?>
<div class="mwb-efw-table-wrap">
	<div class="mwb-col-wrap">
		<div id="mwb-efw-table-inner-container" class="table-responsive mdc-data-table">
			<div class="mdc-data-table__table-container">
				<table class="mwb-efw-table mdc-data-table__table mwb-table" id="mwb-efw-wp">
					<thead>
						<tr>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'WP Variables', 'events-for-woocommerce' ); ?></th>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'WP Values', 'events-for-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody class="mdc-data-table__content">
						<?php if ( is_array( $efw_wordpress_details ) && ! empty( $efw_wordpress_details ) ) { ?>
							<?php foreach ( $efw_wordpress_details as $wp_key => $wp_value ) { ?>
								<?php if ( isset( $wp_key ) && 'wp_users' != $wp_key ) { ?>
									<tr class="mdc-data-table__row">
										<td class="mdc-data-table__cell"><?php echo esc_html( $wp_key ); ?></td>
										<td class="mdc-data-table__cell"><?php echo esc_html( $wp_value ); ?></td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="mwb-col-wrap">
		<div id="mwb-efw-table-inner-container" class="table-responsive mdc-data-table">
			<div class="mdc-data-table__table-container">
				<table class="mwb-efw-table mdc-data-table__table mwb-table" id="mwb-efw-sys">
					<thead>
						<tr>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'Sysytem Variables', 'events-for-woocommerce' ); ?></th>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'System Values', 'events-for-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody class="mdc-data-table__content">
						<?php if ( is_array( $efw_php_details ) && ! empty( $efw_php_details ) ) { ?>
							<?php foreach ( $efw_php_details as $php_key => $php_value ) { ?>
								<tr class="mdc-data-table__row">
									<td class="mdc-data-table__cell"><?php echo esc_html( $php_key ); ?></td>
									<td class="mdc-data-table__cell"><?php echo esc_html( $php_value ); ?></td>
								</tr>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
