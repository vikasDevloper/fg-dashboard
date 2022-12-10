<?php

namespace Dashboard\Classes\Helpers;

class Falconide {

	//put your code here
	private $apiUrl = "https://api.falconide.com/api/web.send.rest";
	private $apiKey = "ae9a99f1f760088fa7f94114f4639f88";

	function callApi($api_input = '') {
		$result = $this->http_post_form($this->apiUrl, $api_input);
		return $result;
	}

	function createMail($mailComponents) {
		$data = array();

		$data['subject'] = $mailComponents['subject'];
		;
		$data['recipients'] = $mailComponents["to"];

		$data['api_key'] = $this->apiKey;

		if (!isset($mailComponents["replytoid"])) {
			$data['replytoid'] = config('mail.reply-to.address');
		} else {
			$data['replytoid'] = $mailComponents["replytoid"];
		}

		if (!isset($mailComponents["from"])) {
			$data['from'] = config('mail.from.address');
		} else {
			$data['from'] = $mailComponents["from"];
		}

		if (!isset($mailComponents["fromname"])) {
			$data['fromname'] = config('mail.from.name');
		} else {
			$data['fromname'] = $mailComponents["fromname"];
		}

		$data['tags']    = $mailComponents["tag"];
		$data['content'] = $mailComponents["message"];

		$isSent = json_decode($this->callApi($data));

		return $isSent;
	}

     function check_balance(){
          
          $content="application/x-www-form-urlencoded";
          $date=date("Y-m-d", strtotime(' -1 day'));
          $url="http://api.falconide.com/v4/credits?startdate=$date";
 	 
 		  $get_response=$this->http_get_email_balance($url,$content) ;
 		  //echo $get_response;exit; 
		  
          return $get_response;
     	 
    }
    function http_get_email_balance($url,$content = ''){

    	  $api_key = $this->apiKey;
          $ch = curl_init();
 		  curl_setopt_array($ch, array(
		  CURLOPT_URL => "$url",
		  CURLOPT_RETURNTRANSFER => true,
 		  CURLOPT_HTTPHEADER => array(
		    "api_key: $api_key",
		    "content-type: $content"
		  ),
		));

 		$result = curl_exec($ch);
		$result = curl_error($ch)?curl_error($ch):$result;
		curl_close($ch);
		return json_decode($result);
    }
   
	function http_post_form($url, $data, $timeout = 20) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RANGE, "1-2000000");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_REFERER, @$_SERVER['REQUEST_URI']);

		$result = curl_exec($ch);
		$result = curl_error($ch)?curl_error($ch):$result;
		curl_close($ch);
		return $result;
	}
}