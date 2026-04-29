<?php
/**
 * Regression check for selected tab intro card layout.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root   = dirname( __DIR__ );
$layout = file_get_contents( $root . '/admin/ui/layouts/class-event-tickets-manager-for-woocommerce-admin-layout.php' );
$css    = file_get_contents( $root . '/admin/css/event-tickets-manager-for-woocommerce-admin-ui.css' );

if ( false === $layout || false === $css ) {
	fwrite( STDERR, "Unable to read intro card layout sources.\n" );
	exit( 1 );
}

$missing = array();

$layout_needles = array(
	'intro card renderer'         => 'private static function render_intro_card',
	'intro card class output'     => 'wps-etmfw-ui-card wps-etmfw-ui-intro-card',
	'section card class output'   => 'wps-etmfw-ui-card wps-etmfw-ui-card--section',
);

foreach ( $layout_needles as $label => $needle ) {
	if ( false === strpos( $layout, $needle ) ) {
		$missing[] = "layout missing {$label}: {$needle}";
	}
}

$css_needles = array(
	'intro card shell'            => '.wps-etmfw-ui-intro-card {',
	'intro card heading'          => '.wps-etmfw-ui-intro-card .wps-etmfw-ui-card__heading h2 {',
	'section card padding'        => '.wps-etmfw-ui-card--section {',
);

foreach ( $css_needles as $label => $needle ) {
	if ( false === strpos( $css, $needle ) ) {
		$missing[] = "css missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing tab intro card contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Tab intro card contract present.\n" );
