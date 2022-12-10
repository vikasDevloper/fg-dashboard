<?php
return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, SparkPost and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	 */

	'payu'          => [
		'merchant_key' => env('PAYU_MERCHANT_KEY'),
		'salt'         => env('PAYU_SALT'),
		'command'      => env('PAYU_COMMAND'),
		'url'          => env('PAYU_URL')
	],

	'paytm'            => [
		'const'           => env('PAYTM_CONST'),
		'merchant_key'    => env('PAYTM_MERCHANT_KEY'),
		'merchant_id'     => env('PAYTM_MERCHANT_ID'),
		'website'         => env('WEBSITE_NAME_FOR_PAYTM'),
		'industry_type'   => env('PAYTM_INDUSTRY_TYPE'),
		'transaction_url' => env('PAYTM_TRANSACTION_URL'),
		'channel_id'      => env('PAYTM_CHANNEL_ID'),
		'callback_url'    => env('PAYTM_CALLBACK_URL'),
		'url'             => env('PAYTM_URL')
	],

];
