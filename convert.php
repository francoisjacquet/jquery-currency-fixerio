<?php
/**
 * Convert currency from, to using amount.
 *
 * @see config.inc.php for configuration.
 */

require_once 'config.inc.php';

// Change $_POST to $_GET to test script using URL params:
// @example http://localhost/jQuery-Currency/convert.php?from=COP&to=EUR&amount=100
$amount = empty( $_POST['amount'] ) ? 0 : $_POST['amount'];

if ( ! is_numeric( $amount ) )
{
	$amount = preg_replace( '/[^0-9\.]/', '', $amount );

	// Handle BYR case: symbol is "p.".
	$amount = trim( $amount, '.' );
}

$from = empty( $_POST['from'] ) || strlen( $_POST['from'] ) !== 3 ? '' : $_POST['from'];
$to = empty( $_POST['to'] ) || strlen( $_POST['to'] ) !== 3 ? '' : $_POST['to'];

if ( ! $from
	|| ! $to
	|| ! $amount
	|| $from === $to )
{
	// Missing entry param or from and to currencies are identical.
	return $amount;
}


$rates = get_currency_rates_from_file( CURRENCY_FILE_PATH );

$date = $rates['date'];

if ( ! $rates
	|| ( $date < date( 'Y-m-d' ) && ! empty( CURRENCY_API_KEY ) ) )
{
	// No file yet or rates are not from today. Get them from API.
	$rates = get_currency_rates_from_api( CURRENCY_API_KEY );

	save_currency_rates_to_file( $rates, CURRENCY_FILE_PATH );
}

// var_dump($rates['rates']);

$converted_amount = convert_amount_currency_to( $amount, $from, $to, $rates );

echo $converted_amount;


function get_currency_rates_from_api( $api_key )
{
	$rates = file_get_contents( CURRENCY_API_URL . $api_key );

	return json_decode( $rates, true );
}

function get_currency_rates_from_file( $file )
{
	if ( ! $file
		|| ! file_exists( $file ) )
	{
		return array();
	}

	$rates = file_get_contents( $file );

	return json_decode( $rates, true );
}

function save_currency_rates_to_file( $rates, $file )
{
	if ( ! $file
		|| ! is_writable( dirname( $file ) )
		|| ( file_exists( $file ) && ! is_writable( $file ) )
		|| ! $rates )
	{
		return false;
	}

	$rates_json = json_encode( $rates );

	return file_put_contents( $file, $rates_json );
}

function convert_amount_currency_to( $amount, $from, $to, $rates )
{
	if ( empty( $rates['rates'][ $to ] ) )
	{
		return $amount;
	}

	$rate = $rates['rates'][ $to ];

	if ( $from === $rates['base'] )
	{
		$base = $amount;
	}
	else
	{
		if ( empty( $rates['rates'][ $from ] ) )
		{
			return $amount;
		}

		$base = $amount / $rates['rates'][ $from ];
	}

	return (float) $base * $rate;
}
