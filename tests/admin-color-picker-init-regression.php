<?php
/**
 * Regression check for shared admin color picker initialization.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root = dirname( __DIR__ );
$init = file_get_contents( $root . '/admin/src/js/etmfw-colorpicker-init.js' );
$admin = file_get_contents( $root . '/admin/src/js/event-tickets-manager-for-woocommerce-admin.js' );

if ( false === $init || false === $admin ) {
	fwrite( STDERR, "Unable to read color picker source files.\n" );
	exit( 1 );
}

$missing = array();

$init_needles = array(
	'shared initializer'            => 'window.etmfwInitColorPickers = function( context ) {',
	'picker meta wrapper'           => 'wps-etmfw-picker-meta',
	'picker title token'            => 'wps-etmfw-picker-title',
	'live hex hook'                 => 'data-etmfw-color-hex',
	'css color variable sync'       => '--etmfw-picked-color',
	'generic settings description'  => '.wps-etmfw-ui-field__description',
	'table helper description'      => '.wps_etmfw_helper_text',
	'change/input binding'          => "change.etmfwColor input.etmfwColor",
	'double init guard'             => "data('etmfw-initialized')",
);

foreach ( $init_needles as $label => $needle ) {
	if ( false === strpos( $init, $needle ) ) {
		$missing[] = "init missing {$label}: {$needle}";
	}
}

$admin_needles = array(
	'shared init call'              => 'window.etmfwInitColorPickers( document );',
	'text color event binding'      => "$(document).on('change input', '.wps_etmfw_pdf_text_color'",
	'border color event binding'    => "$(document).on('change input', '.wps_etmfw_select_ticket_border_color'",
	'background color event binding'=> "$(document).on('change input', '.wps_etmfw_select_ticket_background'",
	'header color event binding'    => "$(document).on('change input', '.wps_etmfw_select_ticket_header_background'",
);

foreach ( $admin_needles as $label => $needle ) {
	if ( false === strpos( $admin, $needle ) ) {
		$missing[] = "admin js missing {$label}: {$needle}";
	}
}

if ( false !== strpos( $admin, "$('.wps_etmfw_colorpicker').wpColorPicker()" ) ) {
	$missing[] = 'admin js still directly initializes all .wps_etmfw_colorpicker inputs instead of reusing shared initializer';
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing shared admin color picker contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Shared admin color picker initialization contract present.\n" );

