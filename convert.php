<?php
/**
 * Convert currency from, to using amount.
 *
 * @see config.inc.php for configuration.
 */

require_once 'config.inc.php';
require_once 'functions.inc.php';

$rates = get_currency_rates_from_file( CURRENCY_FILE_PATH );

$date = $rates['date'];

if ( ! $rates
	|| ( $date < date( 'Y-m-d' ) && ! empty( CURRENCY_API_KEY ) ) )
{
	// No file yet or rates are not from today. Get them from API.
	$rates = get_currency_rates_from_api( CURRENCY_API_KEY );

	save_currency_rates_to_file( $rates, CURRENCY_FILE_PATH );
}

echo json_encode( $rates );
