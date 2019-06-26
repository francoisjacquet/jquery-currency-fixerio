<?php
/**
 * Functions
 */

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
