<?php
/**
 * Regression check for the admin email logo upload control contract.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root      = dirname( __DIR__ );
$admin_php = file_get_contents( $root . '/admin/class-event-tickets-manager-for-woocommerce-admin.php' );
$js_file   = file_get_contents( $root . '/admin/src/js/event-tickets-manager-for-woocommerce-admin.js' );
$css_file  = file_get_contents( $root . '/admin/src/scss/event-tickets-manager-for-woocommerce-admin-global.css' );
$ui_css    = file_get_contents( $root . '/admin/css/event-tickets-manager-for-woocommerce-admin-ui.css' );
$ui_php    = file_get_contents( $root . '/admin/ui/components/class-event-tickets-manager-for-woocommerce-ui-components.php' );

if ( false === $admin_php || false === $js_file || false === $css_file || false === $ui_css || false === $ui_php ) {
	fwrite( STDERR, "Unable to read admin logo upload source files.\n" );
	exit( 1 );
}

$missing = array();
$needles = array(
	'field-specific remove label' => "'button_text' => __( 'Remove Logo', 'event-tickets-manager-for-woocommerce' )",
	'renderer button text support' => "isset( \$item['button_text'] ) ? \$item['button_text'] : __( 'Remove', 'event-tickets-manager-for-woocommerce' )",
	'modern media modal binding' => "var customUploader = wp.media(",
	'media select handler'       => "customUploader.on('select', function() {",
	'composed control override'  => '.wps_etmfw_mail_setting_upload_logo_box .wps-etmfw-ui-composed-control {',
	'preview box layout override' => '.wps_etmfw_mail_setting_upload_logo_box #wps_etmfw_mail_setting_remove_logo {',
	'button reset override'      => '.wps_etmfw_mail_setting_upload_logo_box button#wps_etmfw_mail_setting_upload_logo_button {',
	'visible remove control override' => '.wps_etmfw_mail_setting_upload_logo_box .wps-etmfw-ui-preview-remove {',
);

foreach ( $needles as $label => $needle ) {
	if ( str_contains( $label, 'field-specific' ) ) {
		$file = $admin_php;
	} elseif ( str_contains( $label, 'renderer' ) ) {
		$file = $ui_php;
	} elseif ( str_contains( $label, 'media' ) ) {
		$file = $js_file;
	} else {
		$file = $css_file;
	}

	if ( false === strpos( $file, $needle ) ) {
		$missing[] = "missing {$label}: {$needle}";
	}
}

if ( false === strpos( $ui_css, '.wps-etmfw-ui-composed-control {' ) ) {
	$missing[] = 'missing base composed control layout in admin UI CSS';
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing admin email logo upload contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Admin email logo upload contract present.\n" );
