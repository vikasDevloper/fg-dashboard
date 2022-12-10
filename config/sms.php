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

	'driver' => env('SMS_DRIVER', 'ibulk'),

	'ibulk'      => [
		'url'       => env('SMS_URL_IBULK'),
		'user'      => env('SMS_USERNAME_IBULK'),
		'pwd'       => env('SMS_PASSWORD_IBULK'),
		'sender_id' => env('SMS_SENDER_ID')
	],

	'msg91'      => [
		'url'       => 'https://control.msg91.com/api/sendhttp.php',
		'auth_key'  => env('MSG91_AUTHKEY'),
		'sender_id' => env('SMS_SENDER_ID'),
		'route'     => 4
	],

	'netcore'   => [
		'url'      => 'https://bulkpush.mytoday.com/BulkSms/SingleMsgApi',
		'feedid'   => env('NETCORE_FEEDID'),
		'senderid' => env('NETCORE_SENDERID'),
		'password' => env('NETCORE_PASSWORD')
	],

	'pinnacle'    => [
		'url'        => 'http://www.smsjust.com/sms/user/urlsms.php',
		'balanceurl' => 'http://www.smsjust.com/sms/user/balance_check.php',
		'username'   => env('PINNACLE_USERNAME'),
		'pass'       => env('PINNACLE_PASSWORD'),
		'senderid'   => env('PINNACLE_SENDERID')
	],

	'signature'   => [
		'without_no' => "Love,\nTeam Farida Gupta",

	],

	'smallsignature' => [
		'without_no'    => "Love,\nTeam FG",

	],

	'supportNo'   => [
		'support_no' => "8287 567 567",

	],

];
