<?php
/**
 * Regression check for user-type inventory min/max validation.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

define( 'ABSPATH', __DIR__ );

require_once dirname( __DIR__ ) . '/admin/class-event-tickets-manager-for-woocommerce-admin.php';

if ( ! method_exists( 'Event_Tickets_Manager_For_Woocommerce_Admin', 'validate_user_type_inventory_ranges' ) ) {
	fwrite( STDERR, "Missing validate_user_type_inventory_ranges helper.\n" );
	exit( 1 );
}

$reflection = new ReflectionMethod( 'Event_Tickets_Manager_For_Woocommerce_Admin', 'validate_user_type_inventory_ranges' );
$reflection->setAccessible( true );

$invalid = $reflection->invoke(
	null,
	array(
		array(
			'_inventory_min' => '10',
			'_inventory_max' => '1',
		),
	)
);

$valid = $reflection->invoke(
	null,
	array(
		array(
			'_inventory_min' => '1',
			'_inventory_max' => '10',
		),
	)
);

$blank_max = $reflection->invoke(
	null,
	array(
		array(
			'_inventory_min' => '1',
			'_inventory_max' => '',
		),
	)
);

if ( ! is_array( $invalid ) || ! isset( $invalid['is_valid'] ) || true !== isset( $invalid['row'] ) ) {
	fwrite( STDERR, "Validator response shape is invalid.\n" );
	exit( 1 );
}

if ( true !== $valid['is_valid'] ) {
	fwrite( STDERR, "Expected valid min/max pair to pass validation.\n" );
	exit( 1 );
}

if ( true !== $blank_max['is_valid'] ) {
	fwrite( STDERR, "Expected blank inventory max to bypass validation.\n" );
	exit( 1 );
}

if ( false !== $invalid['is_valid'] ) {
	fwrite( STDERR, "Expected inventory min greater than max to fail validation.\n" );
	exit( 1 );
}

fwrite( STDOUT, "User type inventory range validation contract present.\n" );
