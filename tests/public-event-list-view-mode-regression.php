<?php
/**
 * Regression check for event listing mode-specific layout contracts.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root     = dirname( __DIR__ );
$css_file = file_get_contents( $root . '/public/src/scss/event-tickets-manager-for-woocommerce-public.css' );

if ( false === $css_file ) {
	fwrite( STDERR, "Unable to read event listing stylesheet.\n" );
	exit( 1 );
}

$missing = array();
$needles = array(
	'grid container selector'          => '#wps-search-results.wps_card {',
	'grid card selector scope'         => '#wps-search-results.wps_card .wps-etmw-card {',
	'list container selector'          => '#wps-search-results.wps_list {',
	'list card selector scope'         => '#wps-search-results.wps_list .wps-etmw-card {',
	'list top row selector scope'      => '#wps-search-results.wps_list .wps-etmw-card-top {',
	'list body selector scope'         => '#wps-search-results.wps_list .wps-etmw-card-body {',
	'list footer selector scope'       => '#wps-search-results.wps_list .wps-etmw-card-footer {',
	'list actions selector scope'      => '#wps-search-results.wps_list .wps-etmw-card-actions {',
	'list mobile selector scope'       => '#wps-search-results.wps_list .wps-etmw-card-top,',
);

foreach ( $needles as $label => $needle ) {
	if ( false === strpos( $css_file, $needle ) ) {
		$missing[] = "missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing event listing mode layout contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Event listing mode layout contract present.\n" );
