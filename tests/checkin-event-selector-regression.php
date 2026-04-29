<?php
/**
 * Regression check for the frontend event check-in selector contract.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root        = dirname( __DIR__ );
$public_file = file_get_contents( $root . '/public/class-event-tickets-manager-for-woocommerce-public.php' );

if ( false === $public_file ) {
	fwrite( STDERR, "Unable to read public check-in renderer.\n" );
	exit( 1 );
}

$missing = array();
$needles = array(
	'checkin event helper'       => 'private function wps_etmfw_get_checkin_events()',
	'checkin select name'        => '<select id="wps_etmfw_event_selected" name="wps_etmfw_event_selected">',
	'checkin select placeholder' => "' . __( 'Select an event', 'event-tickets-manager-for-woocommerce' ) . '",
	'checkin empty placeholder'  => "' . __( 'No events available', 'event-tickets-manager-for-woocommerce' ) . '",
	'event meta fallback'        => "get_post_meta( \$candidate_id, 'wps_etmfw_product_array', true )",
);

foreach ( $needles as $label => $needle ) {
	if ( false === strpos( $public_file, $needle ) ) {
		$missing[] = "renderer missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing frontend check-in selector contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Frontend check-in selector contract present.\n" );
