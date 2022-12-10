<?php

namespace Dashboard\Console\Commands;

use Dashboard\Classes\Helpers\Utility;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\NotificationSend;
use Dashboard\Data\Models\Covid19Purchaser;
use Dashboard\Helpers\SendSms;
use Dashboard\Classes\Helpers\UrlShortener;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendCovidPurchaserSms extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'covidPurchaserSms:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send promotional SMS to Faridagupta Customer base purchase during Covid -19';

	protected $launchDate;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->launchDate = '8june20';
		$this->date = '2020-03-19 06:00:00';
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */

	public function handle() {

		//
		set_time_limit(0);

		$smsPurpose = 'sms_'.$this->launchDate.'_b';

		Log::info('Promotional SMS:: '.$smsPurpose.' Started');

		//$signature = config('sms.smallsignature.without_no');
		$signature = config('sms.signature.without_no');
		$site      = config('app.site_url');
		//$number     = config('app.support_no');
		$number     = config('sms.supportNo.support_no');
		$start      = 100*0;
		$limit      = 10;
		$totalUsers = 10;

		//$smsText = "Hi [NAME],\n\nDue to an overwhelming response to our latest collection, there might be a slight delay (2-3 days) in shipping your order."."\n\nThank you for your patience and understanding.\n\nPlease feel free to reach out to us on 8287-567-567 for any assistance.\n\n".$signature;

		//$smsText = "Just Arrived | 3 Summer Cotton Kurtas\n\nClick Now - [URL]\n\nEasy Exchanges & Returns\n\n".$signature."\n".$number;


		//$smsText = "New Arrivals | 5 Kurtas & 5 Kaftans\nHandcrafted Styles in Cotton Fabric\n\nShop now: [URL]\nLimited Stock \n\n".$signature."\n".$number;

// normal text

		//$smsText = "Wear the Difference You Want to See\nAn Artisan's Appeal: FLAT 30% OFF\n\nOur initiative just got bigger. Read more.\n[URL]\n\n Team FG\n".$number;
//COUPON text"New Launch: 3 Kurtas & 5 Kaftans

	 $smsText = "Farida Gupta | Delivery Update\n\nDear [NAME],\nAs per the latest update from our logistics partners, delivery services have been affected in line with the Govt. guidelines for your Zone. Nevertheless, we are assessing all possible options and your order is being handled on priority.\n\nPlease rest assured your order will be dispatched at the earliest. We will notify you as soon as your order is shipped.\n\nThank you for your patience and understanding.\n\nTeam Farida Gupta\n".$number;

 		
 		//$smsText = "Now Shipping: Green, Orange & Red Zones\nJust In | Kimonos & Tops\n\nThank you participating in our initiative.\nTo express our gratitude, we're offering FLAT 30% OFF on our website.\nShop now: [URL]\n\nContainment Zones deliveries will start as per Govt. Guidelines\n\n Team FG\n".$number;

		$i = 0;

		// $users[0]['mobile']      = '9873621245';
		// $users[0]['firstname']   = 'Sanjay Singh';
		// $users[0]['customer_id'] = '1';
		// $users[0]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[0]['city']        = 'XS,XL,XL';
		// //$users[0]['city'] = '';

		// $users[1]['mobile']      = '9818137346';
		// $users[1]['firstname']   = 'Sahil Gupta';
		// $users[1]['customer_id'] = '2';
		// $users[1]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[1]['city']        = 'M,M,M,M,M,M,M,M,M,M,M,S,M,M,M,M,M,M,M,XS,M,M,M,S,M';
		// //$users[1]['city'] = '';

		// $users[2]['mobile']      = '7533061241';
		// $users[2]['firstname']   = 'Komal Bhagat';
		// $users[2]['customer_id'] = '3';
		// $users[2]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[2]['city']        = 'XS,XL,XL,L,XS,XS,XS';
		// //$users[2]['city'] = '';

		// $users[3]['mobile']      = '7906077429';
		// $users[3]['firstname']   = 'Rajan';
		// $users[3]['customer_id'] = '4';
		// $users[3]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[3]['city']        = 'XS,XL,XL';
		// //$users[3]['city'] = '';

		// $users[5]['mobile']      = '8800745258';
		// $users[5]['firstname']   = 'Sandeep';
		// $users[5]['customer_id'] = '6';
		// $users[5]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[5]['city']        = 'XS,XL,XL,XL,XL,XL,XXL,XL,XL,XL,XXL';
		// //$users[5]['city'] = '';

		// $users[6]['mobile']      = '9999973755';
		// $users[6]['firstname']   = 'Nitin';
		// $users[6]['customer_id'] = '7';
		// $users[6]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[6]['city']        = 'M,M,M,M,M,M,M,M,M,M,M,S,M,M,M,M,M,M,M,XS,M,M,M,S,M';
		// //$users[6]['city'] = '';

		// $users[8]['mobile']      = '9999060387';
		// $users[8]['firstname']   = 'Varsha';
		// $users[8]['customer_id'] = '9';
		// $users[8]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[8]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		// //$users[8]['city'] = '';

		// $users[9]['mobile']      = '9910067249';
		// $users[9]['firstname']   = 'Adnan';
		// $users[9]['customer_id'] = '10';
		// $users[9]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[9]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';

		// $users[10]['mobile']      = '7428266467';
		// $users[10]['firstname']   = 'Sushant';
		// $users[10]['customer_id'] = '11';
		// $users[10]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[10]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';

 
		$users[11]['mobile']      = '7906077429';
		$users[11]['firstname']   = 'Rajan';
		$users[11]['customer_id'] = '4';
		$users[11]['purpose']     = 'sms_'.$this->launchDate.'_b_non_delivrable';
		//$users[11]['purpose']     = 'sms_'.$this->launchDate.'_Exh_buyer';
		$users[11]['city']        = 'XS,XL,XL';
	    //$users[11]['city'] = '';
 
		// $users[12]['mobile']      = '8826065309';
		// $users[12]['firstname']   = 'Sandeep';
		// $users[12]['customer_id'] = '4';
		// $users[12]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[12]['city']        = 'XS,XL,XL';
		// $users[12]['city'] = '';

		$users[13]['mobile']      = '8076649281';
		$users[13]['firstname']   = 'Vikas Dubey';
		$users[13]['customer_id'] = '4';
		$users[13]['purpose']     = 'sms_'.$this->launchDate.'_b_non_delivrable';
		//$users[13]['purpose']     = 'sms_'.$this->launchDate.'_Exh_buyer';
		$users[13]['city']        = 'XXS,XS,XL,XL';
		//$users[13]['city'] = '';
		//$users[13]['coupon_code']      = 'XHSDSDA';
	

		// $users[14]['mobile']      = '8595112842';
		// $users[14]['firstname']   = 'shad';
		// $users[14]['customer_id'] = '4';
		// $users[14]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[14]['city']        = 'XS,XL,XL';
		// //$users[14]['city'] = '';

		
		// $users[15]['mobile']      = '9899112842';
		// $users[15]['firstname']   = 'Shiv';
		// $users[15]['customer_id'] = '4';
		// $users[15]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[15]['city']        =  'XXS,XS,XL,XL';


		// $users[16]['mobile']      = '8130605678';
		// $users[16]['firstname']   = 'apoorv';
		// $users[16]['customer_id'] = '4';
		// $users[16]['purpose']     = 'sms_'.$this->launchDate.'_b_covid_purchaser';
		// $users[16]['city']        =  'XXS,XS,XL,XL';
		



		$u                      = NotificationSend::getCustomersToSendSms($start, $limit, $smsPurpose);

		echo "\n\n".$totalUsers = count($u)." ";

		if ($totalUsers > 0) {

			$this->sendSms($users, $smsText);
			//exit;// for Test Sms

			while ($totalUsers > 0) {

				$users = NotificationSend::getCustomersToSendSms($start, $limit, $smsPurpose);

				echo "\n\n".$totalUsers = count($users)." ";
				// echo "\n\n" . count($users) . "  ";

				if (!empty($users)) {

					$this->sendSms($users, $smsText);
					$i = $i+count($users);

					if ($i >= 1000) {
						exit;
					}
				}
			}
		}
	}

	public function sendSms($users, $smsBody) {

		$res     = '';
		$smsText = '';
		$coupon_code = '';

		$unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();
		//$buyersremove = NotificationSend::getAllBuyersMobileByDate($this->date);
		
		foreach ($users as $user) {

			// if ($user['purpose'] == 'sms_9may19_3t') {
			// 	Log::info('3t please change sms');
			// 	exit;
			// }

			$smsText = $smsBody;

			$mobile      = trim($user['mobile']);
			//$coupon_code = Covid19Purchaser::getCouponByMobile($user['mobile']);


			$mobileregex = "/^[6-9][0-9]{9}$/";

			if (preg_match($mobileregex, $mobile) == 0) {
				$user['status'] = -1;
				NotificationSend::updateMobileStatus($user);
				continue;
			} else if (strlen($mobile) != 10) {
				$user['status'] = -1;
				NotificationSend::updateMobileStatus($user);
				continue;
			} else if (in_array($mobile, $unsubscribedMobileUser)) {
				$user['status'] = -1;
				NotificationSend::updateMobileStatus($user);
				continue;
			}
			

			// if (strlen($mobile) == 11) {
			// 	$mobile = substr($mobile, 1);
			// }

			// if (strlen($mobile) == 12) {
			// 	$mobile = substr($mobile, 2);
			// }

			$name = '';
             
			if (!empty($user['firstname'])) {
				$name = ucfirst(strtolower(trim(explode(" ", $user['firstname'])[0])));
				if (strtolower($name) == 'unknown' || strtolower($name) == 'test') {
					$name = '';
				}
			}
        
			// $name = 'Sanjay';
			echo $mobile." ";
			//$url           = "https://bit.ly/FG-Difference";   //normal link
			$url           = "http://fgurl.in/FGgratitude";    // coupon link
			$generated_url = '';
 
			 
             
			//$smsText = str_replace(array("[URL]"), array($url->result), $smsText);
			$smsText = str_replace(array("[URL]"), array($url), $smsText);
			$smsText = str_replace(array("[NAME]"), array($name), $smsText);
            //$smsText = str_replace(array("[COUPON]"), array($coupon_code), $smsText);
            
			  //echo '\n'.$smsText."    ";
			  //exit;
            
            Log::useFiles(storage_path().'/logs/sendPromotionalSms.log');
          //  Log::info('Mobile::'.$mobile.' URL::'.$url->result);
			$res = SendSms::sendSms($mobile, $smsText);

			if ($res) {
				$user['status'] = 1;
			} else {
				$user['status'] = -1;
			}

			// // if($mobile == '9873621245' || $mobile == '9818137346' || $mobile == '9716834689')
			// // {
			// // 	continue;
			// // }

			NotificationSend::updateMobileStatus($user);
			Covid19Purchaser::updateMobileCouponStatus($user,$coupon_code);
		}

		 Log::info('Promotional SMS:: Sent');

	}

}
