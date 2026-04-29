<?php
/**
 * Regression check for event list card link decoration.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root     = dirname( __DIR__ );
$css_file = file_get_contents( $root . '/public/src/scss/event-tickets-manager-for-woocommerce-public.css' );
$php_file = file_get_contents( $root . '/public/class-event-tickets-manager-for-woocommerce-public.php' );

if ( false === $css_file || false === $php_file ) {
	fwrite( STDERR, "Unable to read event list card source files.\n" );
	exit( 1 );
}

$missing = array();
$needles = array(
	'card link markup'                 => 'class="wps-etmw-card-link"',
	'card link selector'               => '.wps-etmw-card-link,',
	'card link hover selector'         => '.wps-etmw-card-link:hover,',
	'card link descendant selector'    => '.wps-etmw-card-link * {',
	'card link no underline important' => 'text-decoration: none !important;',
);

foreach ( $needles as $label => $needle ) {
	$file = 'card link markup' === $label ? $php_file : $css_file;

	if ( false === strpos( $file, $needle ) ) {
		$missing[] = "missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing event list card decoration contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Event list card decoration contract present.\n" );
