<?php

namespace Dashboard\Classes\Helpers;
use GuzzleHttp;
use Dashboard\Data\Models\EavAttributeOptionValue;
use Dashboard\Data\Models\CatalogProductEntityInt;
use Dashboard\Data\Models\SalesFlatOrderAddress;
use Dashboard\Data\Models\ShippingPincodeInfo;
use Dashboard\Data\Models\Picking;
class Utility {
	/*
	Sorting

	 */

	static function compareByKey($a, $b, $key) {
		return strcmp($a[$key], $b[$key]);
	}

	/* returns the shortened url */
	public static function get_bitly_short_url($url, $login, $appkey, $format = 'txt') {
		$connectURL = 'http://api.bit.ly/v3/shorten?login='.$login.'&apiKey='.$appkey.'&uri='.urlencode($url).'&format='.$format;
		return self::curl_get_result($connectURL);
	}

	/* returns the shortened url */
	public static function get_bitly_edited_url($url, $login, $appkey, $format = 'txt') {
		http://api.bit.ly/v3/user/link_edit?edit=note&note=News+from+a+great+record+label+%23music&access_token=ACCESS_TOKEN&link=http%3A%2F%2Fbit.ly%2FJGVkUk;
		$connectURL = 'https://api.bit.ly/v3/user/link_edit?login='.$login.'&apiKey='.$appkey.'&uri='.urlencode($url).'&title=bhagat&format='.$format;
		return self::curl_get_result($connectURL);
	}

	/* returns expanded url */
	public static function get_bitly_long_url($url, $login, $appkey, $format = 'txt') {
		$connectURL = 'http://api.bit.ly/v3/expand?login='.$login.'&apiKey='.$appkey.'&shortUrl='.urlencode($url).'&format='.$format;
		return self::curl_get_result($connectURL);
	}

	/* returns a result form url */
	public static function curl_get_result($url) {
		$ch      = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public static function uniqueMultidimArray($array, $key) {

		$temp_array = array();
		$i          = 0;
		$key_array  = array();

		foreach ($array as $val) {
			if (!in_array($val[$key], $key_array)) {
				$key_array[$i]  = $val[$key];
				$temp_array[$i] = $val;
			}
			$i++;
		}

		return $temp_array;

	}

	public static function get_access_token() {

		$client = new GuzzleHttp\Client;
		//echo  config('app.url').'===='. config('app.site_url');
		$response = $client->request('POST', config('app.url').'/oauth/token', [
				'form_params'    => [
					'grant_type'    => 'client_credentials',
					'client_id'     => '4',
					'scope'         => '*',
					'client_secret' => 'vL7Rv2c9rRQkRkSexAth1SkQKnXyjFzYzoMatR36',
					// 'username' => 'vikas@faridagupta.com',
					//                   'password' => 'vikas@123',

				],
			]);

		$result = json_decode((string) $response->getBody(), true);
		session(['accessToken' => $result['access_token']]);

		return session('accessToken');
		//return true;
	}

	public static function apiCall($url = "", $type = 'GET', $data = '') {
		$accessToken = session('accessToken');

		$client       = new GuzzleHttp\Client;
		$dashboardUrl = config('app.dashboard_url').'/api/';

		$response = $client->request($type, config('app.url').'/api/'.$url, [
				'headers'        => [
					'Accept'        => 'application/json',
					'Authorization' => 'Bearer '.$accessToken
				],
				'form_params' => [
					'data'       => $data,
				]
			]);

		$response = json_decode((string) $response->getBody(), true);
		return $response;

	}
	public static function apiCallNew($url = "", $type = 'GET', $data = '') {
		$accessToken = session('accessToken');
         
		$client       = new GuzzleHttp\Client;
		$dashboardUrl = config('app.dashboard_url').'/api/';
		$response = $client->request($type, config('app.url').'/api/'.$url, [
				'headers'        => [
					'Accept'        => 'application/json',
					'Authorization' => 'Bearer '.$accessToken
				],
				'form_params' => $data
			]);

		$response = json_decode((string) $response->getBody(), true);
		return $response;

	}
	public static function getYear() {
		$firstYear = (int) date('Y')-5;
		$lastYear  = $firstYear+5;
		for ($i = $firstYear; $i <= $lastYear; $i++) {
			$year[] = $i;
		}
		$years = array_merge($year);
		return $years;
	}

	public static function getMonth() {
		$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		return $months;
	}
	public static function getFinacialMonth() {
		$months = array("April", "May", "June", "July", "August", "September", "October", "November", "December", "January", "February", "March");
		return $months;
	}
	public static function getFinancialYear() {

		return date('Y-04-01').'*'.date('Y-03-31');

	}

	public static function calculateTax($totalmrpprice, $dtotaldistotal) {

		if ($totalmrpprice <= 1050) {
			$totalassable = (($totalmrpprice-$dtotaldistotal)/105)*100;
			$cgstper1     = 2.5;
			$wtgstper1    = 2.5;
			$igstper1     = 2.5;
		} else {
			$totalassable = (($totalmrpprice-$dtotaldistotal)/112)*100;
			$cgstper1     = 6;
			$wtgstper1    = 6;
			$igstper1     = 6;
		}
		return $totalmrpprice;
	}


	public static function numberTowords($number, $currency) {
		if($number<0)
			$number = 0;
		$decimal       = round($number-($no = floor($number)), 2)*100;
		$hundred       = null;
		$digits_length = strlen($no);
		$i             = 0;
		$str           = array();
		if ($currency == 'INR') {
			$curr  = 'Rupees ';
			$paise = ' Paise';
		} else {
			$curr  = 'Dollar ';
			$paise = ' Cent';
		}
		$words = array(0=> '', 1=> 'one', 2=> 'two',
			3              => 'three', 4              => 'four', 5              => 'five', 6              => 'six',
			7              => 'seven', 8              => 'eight', 9              => 'nine',
			10             => 'ten', 11             => 'eleven', 12             => 'twelve',
			13             => 'thirteen', 14             => 'fourteen', 15             => 'fifteen',
			16             => 'sixteen', 17             => 'seventeen', 18             => 'eighteen',
			19             => 'nineteen', 20             => 'twenty', 30             => 'thirty',
			40             => 'forty', 50             => 'fifty', 60             => 'sixty',
			70             => 'seventy', 80             => 'eighty', 90             => 'ninety');
		$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
		while ($i < $digits_length) {
			$divider = ($i == 2)?10:100;
			$number  = floor($no%$divider);
			$no      = floor($no/$divider);
			$i += $divider == 10?1:2;
			if ($number) {
				$plural  = (($counter = count($str)) && $number > 9)?'s':null;
				$hundred = ($counter == 1 && $str[0])?' and ':null;
				$str[]   = ($number < 21)?$words[$number].' '.$digits[$counter].$plural.' '.$hundred:$words[floor($number/10)*10].' '.$words[$number%10].' '.$digits[$counter].$plural.' '.$hundred;
			} else {
				$str[] = null;
			}
		}

		$Rupees = $curr.implode('', array_reverse($str));
		$words = array(0=> 'zero', 1=> 'one', 2=> 'two',
			3               => 'three', 4               => 'four', 5               => 'five', 6               => 'six',
			7               => 'seven', 8               => 'eight', 9               => 'nine',
			10              => 'ten', 11              => 'eleven', 12              => 'twelve',
			13              => 'thirteen', 14              => 'fourteen', 15              => 'fifteen',
			16              => 'sixteen', 17              => 'seventeen', 18              => 'eighteen',
			19              => 'nineteen', 20              => 'twenty', 30              => 'thirty',
			40              => 'forty', 50              => 'fifty', 60              => 'sixty',
			70              => 'seventy', 80              => 'eighty', 90              => 'ninety');
		$paise = ($decimal)?" and ".($words[$decimal/10]." ".$words[$decimal%10]).$paise:'';
		return ($Rupees?$Rupees:'').$paise.' Only ';
    }

    public static function random_num($size) {
		$alpha_key = '';
		$keys      = range('A', 'Z');

		for ($i = 0; $i < 2; $i++) {
		$alpha_key .= $keys[array_rand($keys)];
		}

		$length = $size-2;

		$key  = '';
		$keys = range(0, 9);

		for ($i = 0; $i < $length; $i++) {
		$key .= $keys[array_rand($keys)];
		}

		return $alpha_key.$key;
		}

		public static function encryptdata( $data ) {
   		 
        $encryption_key = base64_decode(env('cryptsalt'));
	    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
	    foreach ($data as $key => $value) {

	    	if($value->mobile != '')
	    	{
			    $encryptedmobile = openssl_encrypt($value->mobile, 'aes-256-cbc', $encryption_key, 0, $iv);
			    $value->mobile   = base64_encode($encryptedmobile . '::' . $iv);
		    }
	        if($value->email != '')
	    	{
			    $encryptedemail = openssl_encrypt($value->email, 'aes-256-cbc', $encryption_key, 0, $iv);
			    $value->email   = base64_encode($encryptedemail . '::' . $iv);
		    }
		}
    	 return $data;	 
   		//  $cryptKey  = env('cryptsalt'); 
    	//  $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    	// return( $qEncoded );
		}	
		public static function getCatalogProductSize($prod_id = ''){
			if($prod_id != ''){

			$res = CatalogProductEntityInt::where('entity_id','=', $prod_id)->where('attribute_id', '=','133')->get()->toArray();
		    $data = array();
			foreach ($res as $key => $val) {
				$size_val = $val['value'];
				$arr_res = EavAttributeOptionValue::select('value')->where('option_id', '=', $size_val)->get();
			
				foreach ($arr_res as $key => $value) {
					 $data = $value['value'];
				}
			}
 
			return $data;
			}
		}

		public static function getPickedOrders( $order_id = '' ){
			if($order_id != ''){
			$res = Picking::where('orderid','=', $order_id)->get()->toArray(); 
			return $res;
			}
		}
		public static function getOrderZipcodes($order_id = '',$shipping_method){
			if($order_id != ''){
				$providerID = '';
				if($shipping_method=='bluedart_bluedart')
					$providerID = 1;
				else if ($shipping_method=='delhivery_delhivery')
					$providerID = 3;

			$res = SalesFlatOrderAddress::where('parent_id','=',$order_id)->select('postcode')->get()->toArray(); 

			if(!empty( $res )){
				foreach ($res as $key => $value) {
					$pincodeArr = ShippingPincodeInfo::where('pincode','=',$value)->where('provider_id','=',$providerID)->where('zoneType','!=','')->select('pincode')->get()->toArray(); 

				}

			}
			return $pincodeArr;
			}

		}

}

?>
