<?php
/**
 * Regression check for themed ticket layout size sliders.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root    = dirname( __DIR__ );
$partial = file_get_contents( $root . '/admin/partials/event-tickets-manager-for-woocommerce-ticket-layout-setting.php' );
$js      = file_get_contents( $root . '/admin/src/js/event-tickets-manager-for-woocommerce-admin.js' );
$css     = file_get_contents( $root . '/admin/src/scss/event-tickets-manager-for-woocommerce-admin-global.css' );

if ( false === $partial || false === $js || false === $css ) {
	fwrite( STDERR, "Unable to read themed size slider sources.\n" );
	exit( 1 );
}

$missing = array();

$partial_needles = array(
	'logo slider shell' => 'wps-etmfw-size-slider wps-etmfw-size-slider--logo',
	'qr slider shell'   => 'wps-etmfw-size-slider wps-etmfw-size-slider--qr',
	'slider range class' => 'wps-etmfw-size-slider__range',
	'slider value class' => 'wps-etmfw-size-slider__value',
	'slider scale class' => 'wps-etmfw-size-slider__scale',
);

foreach ( $partial_needles as $label => $needle ) {
	if ( false === strpos( $partial, $needle ) ) {
		$missing[] = "partial missing {$label}: {$needle}";
	}
}

$js_needles = array(
	'slider sync helper' => 'function wps_etmfw_sync_size_slider($input, valueSelector)',
	'logo input binding' => ".on('input change', function()",
	'progress variable'  => "--etmfw-slider-progress",
);

foreach ( $js_needles as $label => $needle ) {
	if ( false === strpos( $js, $needle ) ) {
		$missing[] = "js missing {$label}: {$needle}";
	}
}

$css_needles = array(
	'slider shell style'   => '.wps-etmfw-size-slider {',
	'slider top row'       => '.wps-etmfw-size-slider__top {',
	'slider range style'   => '.wps-etmfw-size-slider__range {',
	'slider value pill'    => '.wps-etmfw-size-slider__value {',
	'slider scale style'   => '.wps-etmfw-size-slider__scale {',
	'slider thumb webkit'  => '.wps-etmfw-size-slider__range::-webkit-slider-thumb {',
	'slider thumb moz'     => '.wps-etmfw-size-slider__range::-moz-range-thumb {',
);

foreach ( $css_needles as $label => $needle ) {
	if ( false === strpos( $css, $needle ) ) {
		$missing[] = "css missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing themed size slider contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Themed size slider contract present.\n" );
