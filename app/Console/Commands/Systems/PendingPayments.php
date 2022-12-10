<?php

namespace Dashboard\Console\Commands\Systems;

use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SalesFlatOrderGrid;
use Dashboard\Data\Models\SalesFlatOrderStatusHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PendingPayments extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'pendingPayments:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Pending payments update if received';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */

	public function paytmCurlCall($transactionURL, $postData) {
		// header("Pragma: no-cache");
		// header("Cache-Control: no-cache");
		// header("Expires: 0");
		$connection = curl_init();
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_URL, $transactionURL);
		curl_setopt($connection, CURLOPT_POST, true);
		curl_setopt($connection, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$responseReader = curl_exec($connection);

		return $responseReader;

	}

	public function payuCurlCall($qs, $wsUrl) {
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $wsUrl);
		curl_setopt($c, CURLOPT_POST, 1);
		curl_setopt($c, CURLOPT_POSTFIELDS, $qs);
		curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
		$o = json_decode(curl_exec($c), true);

		return $o;
	}

	public function pkcs5_pad_e($text, $blocksize) {
		$pad = $blocksize-(strlen($text)%$blocksize);
		return $text.str_repeat(chr($pad), $pad);
	}

	public function encrypt_e($input, $ky) {
		$key  = html_entity_decode($ky);
		$iv   = "@@@@&&&&####$$$$";
		$data = openssl_encrypt($input, "AES-128-CBC", $key, 0, $iv);
		return $data;
	}

	public function decrypt_e($crypt, $ky) {
		$key  = html_entity_decode($ky);
		$iv   = "@@@@&&&&####$$$$";
		$data = openssl_decrypt($crypt, "AES-128-CBC", $key, 0, $iv);
		return $data;
	}

	public function generateSalt_e($length) {
		$random = "";
		srand((double) microtime()*1000000);

		$data = "AbcDE123IJKLMN67QRSTUVWXYZ";
		$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
		$data .= "0FGH45OP89";

		for ($i = 0; $i < $length; $i++) {
			$random .= substr($data, (rand()%(strlen($data))), 1);
		}

		return $random;
	}

	public function checkString_e($value) {
		$myvalue = ltrim($value);
		$myvalue = rtrim($myvalue);
		if ($myvalue == 'null') {
			$myvalue = '';
		}

		return $myvalue;
	}

	public function getChecksumFromArray($arrayList, $key) {
		ksort($arrayList);
		$str         = $this->getArray2Str($arrayList);
		$salt        = $this->generateSalt_e(4);
		$finalString = $str."|".$salt;
		$hash        = hash("sha256", $finalString);
		$hashString  = $hash.$salt;
		$checksum    = $this->encrypt_e($hashString, $key);
		return $checksum;
	}

	public function verifychecksum_e($arrayList, $key, $checksumvalue) {
		$arrayList = $this->removeCheckSumParam($arrayList);
		ksort($arrayList);
		$str        = $this->getArray2StrForVerify($arrayList);
		$paytm_hash = $this->decrypt_e($checksumvalue, $key);
		$salt       = substr($paytm_hash, -4);

		$finalString = $str."|".$salt;

		$website_hash = hash("sha256", $finalString);
		$website_hash .= $salt;

		$validFlag = "FALSE";
		if ($website_hash == $paytm_hash) {
			$validFlag = "TRUE";
		} else {
			$validFlag = "FALSE";
		}
		return $validFlag;
	}

	public function getArray2StrForVerify($arrayList) {
		$paramStr = "";
		$flag     = 1;
		foreach ($arrayList as $key => $value) {
			if ($flag) {
				$paramStr .= $this->checkString_e($value);
				$flag = 0;
			} else {
				$paramStr .= "|".$this->checkString_e($value);
			}
		}
		return $paramStr;
	}
	public function getArray2Str($arrayList) {
		$findme     = 'REFUND';
		$findmepipe = '|';
		$paramStr   = "";
		$flag       = 1;
		foreach ($arrayList as $key => $value) {
			$pos     = strpos($value, $findme);
			$pospipe = strpos($value, $findmepipe);
			if ($pos !== false || $pospipe !== false) {
				continue;
			}

			if ($flag) {
				$paramStr .= $this->checkString_e($value);
				$flag = 0;
			} else {
				$paramStr .= "|".$this->checkString_e($value);
			}
		}
		return $paramStr;
	}

	public function redirect2PG($paramList, $key) {
		$hashString = $this->getchecksumFromArray($paramList);
		$checksum   = $this->encrypt_e($hashString, $key);
	}

	public function removeCheckSumParam($arrayList) {
		if (isset($arrayList["CHECKSUMHASH"])) {
			unset($arrayList["CHECKSUMHASH"]);
		}
		return $arrayList;
	}

	public function PayUCheck($pendingPayments){
		$merchant_key = config('payment-gateway.payu.merchant_key');
		$salt         = config('payment-gateway.payu.salt');
		$command      = config('payment-gateway.payu.command');
		$wsUrl        = config('payment-gateway.payu.url');

		$payment_status = 0;

		try {
			$txnid = $pendingPayments['increment_id'];
			$hash  = strtolower(hash('sha512', $merchant_key.'|'.$command.'|'.$txnid.'|'.$salt));
			//$hash = SHA512($merchant_key.'|'.$command.'|'.$txnid.'|'.$salt);
			$r = array(
				'key'     => $merchant_key,
				'hash'    => $hash,
				'var1'    => $txnid,
				'command' => $command,
			);

			$qs       = http_build_query($r);
			$response = $this->payuCurlCall($qs, $wsUrl);

			if (!empty($response)) {
				if (!empty($response['transaction_details'][$txnid])) {

					$transaction_details = $response['transaction_details'][$txnid];

					if ($transaction_details['status'] == 'success') {

						$transaction_detail = 'Transaction ID::'.$txnid.' Amount::'.$transaction_details['transaction_amount'];
						$transaction_msg    = $transaction_detail.' Order Confirmed';

						$sfo_object = SalesFlatOrder::where('increment_id', '=', $txnid)->update(array(
								"state"  => 'new',
								"status" => 'order_confirm',
							));

						$insertData                         = array();
						$insertData['parent_id']            = $pendingPayments['orderid'];
						$insertData['is_customer_notified'] = 0;
						$insertData['is_visible_on_front']  = 1;
						$insertData['comment']              = $transaction_msg;
						$insertData['status']               = 'order_confirm';
						$insertData['entity_name']          = 'order';

						SalesFlatOrderStatusHistory::insert($insertData);

						$sfog_object = SalesFlatOrderGrid::where('entity_id', '=', $pendingPayments['orderid'])->update(array(
								"status" => 'order_confirm',
							));

						$payment_status = 1;

					} 

					Log::info('PayU Pending Payment', ['Comments' => 'Transaction Id::'.$txnid.' Status::'.$transaction_details['status']]);
				}
			}
		} catch (Exception $e) {

			Log::error('Error::'.$e->getMessage());
		}

		return $payment_status;
	}


	public function paytmCheck($pendingPayments){
		$const           = config('payment-gateway.paytm.const');
		$merchant_key    = config('payment-gateway.paytm.merchant_key');
		$merchant_id     = config('payment-gateway.paytm.merchant_id');
		$transaction_url = config('payment-gateway.paytm.transaction_url');
		$txnid           = $pendingPayments['increment_id'];

		$payment_status = 0;

		try {

			$checkSum = "";
			// below code snippet is mandatory, so that no one can use your checksumgeneration url for other purpose .
			$paramList                     = array();
			$paramList["MID"]              = $merchant_id;//Provided by Paytm
			$paramList["ORDER_ID"]         = $txnid;//unique OrderId for every request
			$paramList["CUST_ID"]          = $pendingPayments['customer_id'];// unique customer identifier
			$paramList["INDUSTRY_TYPE_ID"] = config('payment-gateway.paytm.industry_type');//Provided by Paytm
			$paramList["CHANNEL_ID"]       = config('payment-gateway.paytm.channel_id');//Provided by Paytm
			$paramList["TXN_AMOUNT"]       = $pendingPayments['grand_total'];// transaction amount
			$paramList["WEBSITE"]          = config('payment-gateway.paytm.website');//Provided by Paytm
			$paramList["CALLBACK_URL"]     = config('payment-gateway.paytm.callback_url');//Provided by Paytm
			$paramList["EMAIL"]            = $pendingPayments['customer_email'];// customer email id
			$paramList["MOBILE_NO"]        = '9999999999';// customer 10 digit mobile no.
			$checkSum                      = $this->getChecksumFromArray($paramList, $merchant_key);
			$paramList["CHECKSUMHASH"]     = $checkSum;

			$postData = "JsonData=".json_encode($paramList, JSON_UNESCAPED_SLASHES);
			$url      = config('payment-gateway.paytm.url');

			$server_output = $this->paytmCurlCall($url, $postData);
			$response      = json_decode($server_output, true);

			if (!empty($response)) {

				if (!empty($response['TXNID'])) {

					if ($response['STATUS'] == 'TXN_SUCCESS') {

						$transaction_detail = 'Transaction ID::'.$response['TXNID'].' Amount::'.$response['TXNAMOUNT'];
						$transaction_msg    = $transaction_detail.' Order Confirmed';

						$sfo_object = SalesFlatOrder::where('increment_id', '=', $txnid)->update(array(
								"state"  => 'new',
								"status" => 'order_confirm',
							));

						$insertData                         = array();
						$insertData['parent_id']            = $pendingPayments['orderid'];
						$insertData['is_customer_notified'] = 0;
						$insertData['is_visible_on_front']  = 1;
						$insertData['comment']              = $transaction_msg;
						$insertData['status']               = 'order_confirm';
						$insertData['entity_name']          = 'order';

						SalesFlatOrderStatusHistory::insert($insertData);

						$sfog_object = SalesFlatOrderGrid::where('entity_id', '=', $pendingPayments['orderid'])->update(array(
								"status" => 'order_confirm',
							));

						$payment_status = 1;

					}

					Log::info('Paytm Pending Payment', ['Comments' => 'Transaction Id::'.$txnid.' Status::'.$response['STATUS']]);
				}
			}

		} catch (Exception $e) {
			Log::error('Error::'.$e->getMessage());
		}

		return $payment_status;
	}
    
    public function RazorPayCheck(){
    	$link ="https://api.razorpay.com/v1/payments/pay_DCB7GU76xyjfaA";
		$curl=curl_init(); 
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl,CURLOPT_URL,$link);
		curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'GET');
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("accept: application/json"));  
		curl_setopt($curl, CURLOPT_USERPWD, 'rzp_test_xsWw2XnpEF06Te:bTRI1sidZXsCM1DuMXCRK6t9');
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,1);
		$out = curl_exec($curl); 
		curl_close($curl);
		var_dump($out);
    }

	public function handle() {
		
		set_time_limit(0);

		Log::info('Pending Payment update started');
        $this->RazorPayCheck();
        dd('razorpay');
		$pendingPaymentsArray = SalesFlatOrder::getAllPendingPayments();

		foreach ($pendingPaymentsArray as $pendingPayments) {

			if($this->PayUCheck($pendingPayments) != 1){
				$this->paytmCheck($pendingPayments);
			}

		}
	}
}
