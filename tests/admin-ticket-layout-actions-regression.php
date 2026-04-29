<?php
/**
 * Regression check for ticket layout action row positioning.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root    = dirname( __DIR__ );
$scss    = file_get_contents( $root . '/admin/src/scss/event-tickets-manager-for-woocommerce-admin-global.css' );
$partial = file_get_contents( $root . '/admin/partials/event-tickets-manager-for-woocommerce-ticket-layout-setting.php' );

if ( false === $scss || false === $partial ) {
	fwrite( STDERR, "Unable to read ticket layout action source files.\n" );
	exit( 1 );
}

$missing = array();
$needles = array(
	'ticket layout action wrapper'   => 'wps-etmfw-layout-actions',
	'action wrapper selector'        => '.wps-form-group.wps_center_save_changes {',
	'sticky action positioning'      => 'position: sticky;',
	'sticky action bottom'           => 'bottom: 0;',
	'action row spacing'             => 'padding: 10px 0;',
);

foreach ( $needles as $label => $needle ) {
	$file = 'ticket layout action wrapper' === $label ? $partial : $scss;

	if ( false === strpos( $file, $needle ) ) {
		$missing[] = "missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing ticket layout action row contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Ticket layout action row contract present.\n" );
