<?php
/**
 * Regression check for shared admin floating action rows.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root   = dirname( __DIR__ );
$ui_css = file_get_contents( $root . '/admin/css/event-tickets-manager-for-woocommerce-admin-ui.css' );
$ui_php = file_get_contents( $root . '/admin/ui/components/class-event-tickets-manager-for-woocommerce-ui-components.php' );

if ( false === $ui_css || false === $ui_php ) {
	fwrite( STDERR, "Unable to read shared admin action row source files.\n" );
	exit( 1 );
}

$missing = array();
$needles = array(
	'action row class assignment' => "\$row_classes[] = 'wps-etmfw-ui-field--actions';",
	'sticky action row selector'  => '.wps-etmfw-ui-field--actions {',
	'sticky action positioning'   => 'position: sticky;',
	'sticky action bottom'        => 'bottom: 0;',
	'sticky action z-index'       => 'z-index: 5;',
);

foreach ( $needles as $label => $needle ) {
	$file = 'action row class assignment' === $label ? $ui_php : $ui_css;

	if ( false === strpos( $file, $needle ) ) {
		$missing[] = "missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing shared admin floating action contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Shared admin floating action contract present.\n" );
