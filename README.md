# jQuery Currency fixer.io

Simple, unobtrusive currency converting and formatting. Uses PHP & fixer.io API for conversions.

## Requirements

To use jQuery Currency fixer.io you will need the following:

* jQuery Version 1.5 - Version 2.1.3
* PHP and a fixer.io account (free or paid) to perform foreign exchange conversions

## Configuration

Edit the `config.inc.php` file and set your fixer.io **API key**. Change the URL to `https` if you use a paid account.

## Example Usage

Format an element on a page, using Default Settings
```js
$(document).ready(function() {
	$("#basic").currency();
});
```
For more examples [see the demo](demo/index.html).

## Default Settings

The following list outlines the settings and their defualt values:
```js
$("#number").currency({
	region: "USD", // The 3 digit ISO code you want to display your currency in
	thousands: ",", // Thousands separator
	decimal: ".",   // Decimal separator
	decimals: 2, // How many decimals to show
	hidePrefix: false, // Hide any prefix
	hidePostfix: false, // Hide any postfix
	convertFrom: "", // If converting, the 3 digit ISO code you want to convert from,
	convertLoading: "(Converting...)", // Loading message appended to values while converting
	convertLocation: "convert.php" // Location of convert.php file
});
```
