<?php
/**
 * Regression check for image-matched admin color picker styles.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root = dirname( __DIR__ );
$style = file_get_contents( $root . '/admin/src/scss/etmfw-colorpicker.css' );

if ( false === $style ) {
	fwrite( STDERR, "Unable to read color picker stylesheet.\n" );
	exit( 1 );
}

$missing = array();
$needles = array(
	'picker shell'                 => '.wps-etmfw-picker {',
	'picker uses color var'        => '--etmfw-picked-color',
	'meta wrapper'                 => '.wps-etmfw-picker-meta',
	'row heading'                  => '.wps-etmfw-picker-head',
	'title badge'                  => '.wps-etmfw-picker-title',
	'hex label'                    => '.wps-etmfw-picker-hex',
	'description text'             => '.wps-etmfw-picker-description',
	'swatch button'                => '.wps-etmfw-picker .wp-color-result',
	'hide wp result text'          => '.wps-etmfw-picker .wp-color-result-text',
	'hide raw input wrap'          => '.wps-etmfw-picker .wp-picker-input-wrap',
	'popup positioning'            => '.wps-etmfw-picker .wp-picker-holder',
	'iris popup skin'              => '.wps-etmfw-picker .iris-picker',
	'focus state'                  => '.wps-etmfw-picker:focus-within',
);

foreach ( $needles as $label => $needle ) {
	if ( false === strpos( $style, $needle ) ) {
		$missing[] = "style missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing admin color picker style contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Admin color picker style contract present.\n" );
