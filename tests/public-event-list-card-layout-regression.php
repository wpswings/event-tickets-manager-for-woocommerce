<?php
/**
 * Regression check for event list card layout contract.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root     = dirname( __DIR__ );
$css_file = file_get_contents( $root . '/public/src/scss/event-tickets-manager-for-woocommerce-public.css' );

if ( false === $css_file ) {
	fwrite( STDERR, "Unable to read event list card stylesheet.\n" );
	exit( 1 );
}

$missing = array();
$needles = array(
	'card link block layout'           => 'display: block;',
	'grid card selector scope'         => '#wps-search-results.wps_card .wps-etmw-card {',
	'card top grid layout'             => 'grid-template-columns: auto minmax(0, 1fr);',
	'card image margin reset'          => '.wps-etmw-card-image {',
	'card body grid layout'            => 'grid-template-columns: 120px minmax(0, 1fr);',
	'card venue margin reset'          => '.wps-etmw-card-venue {',
	'card footer grid layout'          => 'grid-template-columns: minmax(0, 1fr) auto;',
	'card mobile single column layout' => '.wps-etmw-card-footer {',
);

foreach ( $needles as $label => $needle ) {
	if ( false === strpos( $css_file, $needle ) ) {
		$missing[] = "missing {$label}: {$needle}";
	}
}

if ( false === strpos( $css_file, ".wps-etmw-card-image {\n  width: 120px;\n  height: 96px;\n  margin: 0;" ) ) {
	$missing[] = 'missing card image margin reset contract';
}

if ( false === strpos( $css_file, ".wps-etmw-card-venue {\n  margin: 0;" ) ) {
	$missing[] = 'missing card venue margin reset contract';
}

if ( false === strpos( $css_file, "@media screen and (max-width: 780px) {\n  #wps-search-results.wps_card .wps-etmw-card-top,\n  #wps-search-results.wps_card .wps-etmw-card-body {\n    grid-template-columns: 1fr;" ) ) {
	$missing[] = 'missing mobile single-column contract for card top/body';
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing event list card layout contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Event list card layout contract present.\n" );
