<?php
/**
 * Regression check for shortcode helper note layout in admin settings.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root    = dirname( __DIR__ );
$ui_css  = file_get_contents( $root . '/admin/css/event-tickets-manager-for-woocommerce-admin-ui.css' );
$ui_php  = file_get_contents( $root . '/admin/ui/components/class-event-tickets-manager-for-woocommerce-ui-components.php' );
$admin   = file_get_contents( $root . '/admin/class-event-tickets-manager-for-woocommerce-admin.php' );

if ( false === $ui_css || false === $ui_php || false === $admin ) {
	fwrite( STDERR, "Unable to read shortcode note layout source files.\n" );
	exit( 1 );
}

$missing = array();
$needles = array(
	'general shortcode field type'     => "'type'  => 'wps_simple_text'",
	'shortcode code highlighting'      => "preg_replace( '/(\\[[^\\]]+\\])/', '<code>\$1</code>', \$description, 1 )",
	'shortcode helper wider width'     => '.wps-etmfw-ui-field--wps_simple_text .wps-etmfw-ui-control-box {',
	'shortcode helper max width value' => 'width: min(540px, calc(100vw - 64px));',
	'shortcode helper code nowrap'     => '.wps-etmfw-ui-control-box--note code {',
	'shortcode helper code wrap rule'  => 'white-space: nowrap;',
);

foreach ( $needles as $label => $needle ) {
	if ( str_contains( $label, 'general shortcode' ) ) {
		$file = $admin;
	} elseif ( str_contains( $label, 'highlighting' ) ) {
		$file = $ui_php;
	} else {
		$file = $ui_css;
	}

	if ( false === strpos( $file, $needle ) ) {
		$missing[] = "missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing shortcode helper note layout contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Shortcode helper note layout contract present.\n" );
