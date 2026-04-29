<?php
/**
 * Regression check for ticket layout design panel theming.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

$root    = dirname( __DIR__ );
$partial = file_get_contents( $root . '/admin/partials/event-tickets-manager-for-woocommerce-ticket-layout-setting.php' );
$css     = file_get_contents( $root . '/admin/src/scss/event-tickets-manager-for-woocommerce-admin-global.css' );

if ( false === $partial || false === $css ) {
	fwrite( STDERR, "Unable to read ticket layout theme sources.\n" );
	exit( 1 );
}

$missing = array();

$partial_needles = array(
	'layout button class' => 'wps-etmfw-layout-button',
	'layout action row'   => 'wps-etmfw-layout-actions',
);

foreach ( $partial_needles as $label => $needle ) {
	if ( false === strpos( $partial, $needle ) ) {
		$missing[] = "partial missing {$label}: {$needle}";
	}
}

$css_needles = array(
	'tab shell'              => '.wps-etmfw-appearance-nav-tab {',
	'tab button style'       => '.wps-etmfw-appearance-nav-tab a.nav-tab.nav-tab {',
	'tab active style'       => '.wps-etmfw-appearance-nav-tab a.nav-tab.nav-tab-active {',
	'border type select'     => 'select.wps_etmfw_preview_select_border_type',
	'layout button style'    => '.wps-etmfw-layout-button.mdc-button',
	'layout button hover'    => '.wps-etmfw-layout-button.mdc-button:hover',
	'action row layout'      => '.wps-etmfw-layout-actions .wps-form-group__control',
);

foreach ( $css_needles as $label => $needle ) {
	if ( false === strpos( $css, $needle ) ) {
		$missing[] = "css missing {$label}: {$needle}";
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing ticket layout theme contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Ticket layout theme contract present.\n" );
