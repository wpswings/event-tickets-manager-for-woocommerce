<?php
/**
 * Regression check for color picker asset loading on admin tabs.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root       = dirname( __DIR__ );
$admin_file = file_get_contents( $root . '/admin/class-event-tickets-manager-for-woocommerce-admin.php' );

if ( false === $admin_file ) {
	fwrite( STDERR, "Unable to read admin loader file.\n" );
	exit( 1 );
}

$missing = array();
$needles = array(
	'dashboard tab color picker gate' => "'event-tickets-manager-for-woocommerce-dashboard-settings'",
	'color picker pill style'         => "-colorpicker-pill",
	'color picker init script'        => "-colorpicker-init",
	'color picker init path'          => "admin/src/js/etmfw-colorpicker-init.js",
	'color picker style path'         => "admin/src/scss/etmfw-colorpicker.css",
);

foreach ( $needles as $label => $needle ) {
	if ( false === strpos( $admin_file, $needle ) ) {
		$missing[] = "loader missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing admin color picker loader contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Admin color picker loader contract present.\n" );
