<?php
/**
 * Regression check for the "event not started" check-in notice styling contract.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root       = dirname( __DIR__ );
$php_file   = file_get_contents( $root . '/public/class-event-tickets-manager-for-woocommerce-public.php' );
$js_file    = file_get_contents( $root . '/public/src/js/event-tickets-manager-for-woocommerce-checkin-page.js' );
$style_file = file_get_contents( $root . '/public/src/scss/event-tickets-manager-for-woocommerce-public.css' );

if ( false === $php_file || false === $js_file || false === $style_file ) {
	fwrite( STDERR, "Unable to read check-in notice source files.\n" );
	exit( 1 );
}

$missing = array();
$needles = array(
	'php notice class for not started state' => "\$response['notice_class'] = 'wps_check_in_notice';",
	'js notice class reset'                  => 'removeClass("wps_check_in_success wps_check_in_error wps_check_in_notice")',
	'js notice class application'            => 'response.notice_class ? response.notice_class : ( response.result ? "wps_check_in_success" : "wps_check_in_error" )',
	'css green notice selector'              => '.wps-etmfw-checkin-form__message.wps_check_in_notice',
);

foreach ( $needles as $label => $needle ) {
	$file = 'css green notice selector' === $label ? $style_file : ( str_starts_with( $label, 'php' ) ? $php_file : $js_file );

	if ( false === strpos( $file, $needle ) ) {
		$missing[] = "missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing check-in notice contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Check-in not-started notice contract present.\n" );
