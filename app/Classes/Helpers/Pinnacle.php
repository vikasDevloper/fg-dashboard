<?php

namespace Dashboard\Classes\Helpers;

class Pinnacle {

	protected static $username;
	protected static $pass;
	protected static $senderid;
	protected static $url;
	protected static $balanceurl;

	static function sendSmsViaPinnacle($mobile, $smsBody, $date = '', $msgType = 'TXT') {
		//echo 'pinnacle';
		self::$username = config('sms.pinnacle.username');
		self::$pass     = config('sms.pinnacle.pass');
		self::$senderid = config('sms.pinnacle.senderid');
		self::$url      = config('sms.pinnacle.url');

		//Prepare you post parameters
		$postData = array(

			'username'      => self::$username,
			'pass'          => self::$pass,
			'senderid'      => self::$senderid,
			'dest_mobileno' => '91'.$mobile,
			'message'       => urlencode($smsBody),
			'MTYPE'         => $msgType,
			'dt'            => $date,
			'response'      => 'Y',
		);

		return $sent = self::callApi($postData, 'send_sms');

	}

	static function checkBalancePinnacle() {
		self::$username = config('sms.pinnacle.username');
		self::$pass     = config('sms.pinnacle.pass');
		self::$url      = config('sms.pinnacle.balanceurl');

		$createUrl = self::$url
		.'?username='.self::$username
		.'&pass='.self::$pass;

		$output = self::callApiGet($createUrl);

		if (!empty($output)) {
			$outputArray = explode(":", $output);

			if (!empty($outputArray)) {
				if (is_numeric($outputArray[1])) {
					return $outputArray[1];
				} else {
					return false;
				}
			}
		}
	}

	static function callApiGet($url) {

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$curl_scraped_page = curl_exec($ch);
		curl_close($ch);

		if (empty($curl_scraped_page)) {
			return false;
		}
		return $curl_scraped_page;
	}

	static function callApi($params) {

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

			$outputArray = explode("-", $output);

			if (!empty($outputArray)) {
				if (is_numeric($outputArray[0])) {
					return true;
				} else {
					return false;
				}
			}

			return false;
		}

		return false;

	}

}