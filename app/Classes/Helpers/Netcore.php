<?php

namespace Dashboard\Classes\Helpers;
use Parser;

class Netcore {

	protected static $feedid;
	protected static $password;
	protected static $senderid;
	protected static $url;
	protected static $username;

	static function sendSmsViaNetcore($mobile, $smsBody, $date = '', $msgType = 1) {

		self::$feedid   = config('sms.netcore.feedid');
		self::$password = config('sms.netcore.password');
		self::$senderid = config('sms.netcore.senderid');
		self::$url      = config('sms.netcore.url');
		self::$username = '9873621245';

		//$msgType = 1;

		//Prepare you post parameters
		$postData = array(
			'feedid'   => (int) self::$feedid,
			'senderid' => self::$senderid,
			'username' => self::$username,
			'password' => self::$password,
			'mtype'    => $msgType,
			'time'     => $date, //yyyymmddhhmm
			'To'       => '91'.$mobile,
			'Text'     => urlencode($smsBody)
		);

		return $sent = self::callApi($postData);

		// echo self::$url.'?feedid='.self::$feedid.'&senderid='.self::$senderid.'&username='.self::$username.'&password='.self::$password.'&mtype=1&time=&To=91'.$mobile.'&Text='.urlencode($smsBody);
		// exit;

		// $sent = self::callApi(self::$url.'?feedid='.self::$feedid.'&senderid='.self::$senderid.'&username='.$mobile.'&password='.self::$password.'&mtype=1&time=&To=91'.$mobile.'&Text='.urlencode($smsBody));
	}

	// static function callApi($url) {

	// 	$ch = curl_init($url);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 	$curl_scraped_page = curl_exec($ch);
	// 	curl_close($ch);

	// 	if (empty($curl_scraped_page)) {
	// 		return false;
	// 	}
	// 	return true;
	// }

	static function callApi($params) {
		//print_r($params);

		$postData = '';

		//create name value pairs seperated by &

		foreach ($params as $k => $v) {
			$postData .= $k.'='.$v.'&';
		}

		$postData = rtrim($postData, '&');

		$ch = curl_init();

		curl_setopt_array($ch, array(

				CURLOPT_URL            => self::$url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST           => true,
				CURLOPT_POSTFIELDS     => $postData
				//,CURLOPT_FOLLOWLOCATION => true
			));

		//Ignore SSL certificate verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		//get response
		$output = curl_exec($ch);

		if (curl_errno($ch)) {
			echo 'error:'.curl_error($ch);
			return false;
		}

		curl_close($ch);

		if (!empty($output)) {
			$parsed = Parser::xml($output);
			if (!empty($parsed['@REQID']) && !empty($parsed['MID'])) {
				if (isset($parsed['MID']['ERROR'])) {
					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
		}

		
	}

}