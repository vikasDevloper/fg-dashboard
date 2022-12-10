<?php

namespace Dashboard\Console\Commands;

use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\NotificationSend;
use Dashboard\Helpers\SendSms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPromotionalSms1 extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'promotionalSms1:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send promotional SMS to Faridagupta Customer base to non Buyer';

	protected $launchDate;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->launchDate = '28june20';
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */

	public function handle() {

		//
		set_time_limit(0);

		$smsPurpose = 'sms_'.$this->launchDate.'_nb_all';

		Log::info('Promotional SMS:: '.$smsPurpose.' Started');

		//$signature = config('sms.smallsignature.without_no');
		$signature = config('sms.signature.without_no');
		$site      = config('app.site_url');
		//$number     = config('app.support_no');
		$number     = config('sms.supportNo.support_no');
		$start      = 100*0;
		$limit      = 10;
		$totalUsers = 10;


		//$smsText = "Dear [NAME],\n\nGreetings from Farida Gupta. Thank you for being part of our initiative. Your participation has made a huge difference.\n\nTrack the real-time progress of our initiative now: [URL]\n\nTogether, we're stronger.\n\n Stay safe.\n\nTeam FG\n".$number;

		//$smsText = "Just Arrived | 3 Summer Cotton Kurtas\n\nClick Now - [URL]\n\nEasy Exchanges & Returns\n\n".$signature."\n".$number;

 		//$smsText = "Farida Gupta | New SS '20 Styles\nHandcrafted Kurtas, Kaftans & More!\n\nShop now: [URL]\nLimited Stock.\n\n".$signature."\n".$number;

		$smsText = "Farida Gupta: Cotton Kurtas\n15% OFF on Your First Order*\n\nExplore our latest Summer styles now: [URL]\nCOD Available*\n\nTeam FG\n".$number;

		$i = 0;

		// $users[0]['mobile']      = '9873621245';
		// $users[0]['firstname']   = 'Sanjay Singh';
		// $users[0]['customer_id'] = '1';
		// $users[0]['purpose']     = 'sms_'.$this->launchDate.'_nb_last90-30';
		// //$users[0]['purpose']     = 'sms_'.$this->launchDate.'_b_1t';
		// $users[0]['city']        = '';

		// // $users[1]['mobile']      = '9818137346';
		// $users[1]['firstname']   = 'Sahil Gupta';
		// $users[1]['customer_id'] = '2';
		// $users[1]['purpose']     = 'sms_'.$this->launchDate.'_nb_last30';
		// //$users[1]['purpose']     = 'sms_'.$this->launchDate.'_b_1t';
		// $users[1]['city']        = '';

		// $users[2]['mobile'] = '7533061241';
		// //'8130106434';
		// //'8010258215';
		// $users[2]['firstname']   = 'Komal Bhagat';
		// $users[2]['customer_id'] = '3';
		// //$users[2]['purpose']     = 'sms_'.$this->launchDate.'_b_1t';
		// $users[2]['purpose']     = 'sms_'.$this->launchDate.'_nb_last30';
		// $users[2]['city']        = '';

		// $users[3]['mobile']      = '7906077429';
		// $users[3]['firstname']   = 'Rajan';
		// $users[3]['customer_id'] = '4';
		// $users[3]['purpose']     = 'sms_'.$this->launchDate.'_nb_last90-30';
		// //$users[3]['purpose']     = 'sms_'.$this->launchDate.'_b_1t';
		// $users[3]['city']        = '';

		// $users[5]['mobile']      = '8800745258';
		// $users[5]['firstname']   = 'Sandeep';
		// $users[5]['customer_id'] = '6';
		// $users[5]['purpose']     = 'sms_'.$this->launchDate.'_nb_last320-180';
		// //$users[5]['purpose']     = 'sms_'.$this->launchDate.'_b_1t';
		// $users[5]['city']        = '';

		// $users[6]['mobile']      = '9999973755';
		// $users[6]['firstname']   = 'Nitin';
		// $users[6]['customer_id'] = '7';
		// $users[6]['purpose']     = 'sms_'.$this->launchDate.'_nb_last30';
		// //$users[6]['purpose']     = 'sms_'.$this->launchDate.'_b_1t';
		// $users[6]['city']        = '';

		 /*$users[8]['mobile']      = '8789585604';
		 $users[8]['firstname']   = 'Vinay';
		 $users[8]['customer_id'] = '9';
		 $users[8]['purpose']     = 'sms_'.$this->launchDate.'_nb_all';
		 //$users[8]['purpose']     = 'sms_'.$this->launchDate.'_b_1t';
		 $users[8]['city']        = '';*/

		// $users[9]['mobile']      = '9910067249';
		// $users[9]['firstname']   = 'Adnan';
		// $users[9]['customer_id'] = '10';
		//  $users[9]['purpose']     = 'sms_'.$this->launchDate.'_nb_last30';
		// // $users[9]['purpose']     = 'sms_'.$this->launchDate.'_b_1t';
		// $users[9]['city']        = '';

		// $users[10]['mobile']      = '7428266467';
		// $users[10]['firstname']   = 'Sushant';
		// $users[10]['customer_id'] = '11';
		// $users[10]['purpose']     = 'sms_'.$this->launchDate.'_nb_last30';
		// //$users[10]['purpose']     = 'sms_'.$this->launchDate.'_b_1t';
		// $users[10]['city']        = '';

		$users[11]['mobile']      = '9045682529';
		$users[11]['firstname']   = 'Rajan';
		$users[11]['customer_id'] = '4';
		$users[11]['purpose']     = 'sms_'.$this->launchDate.'_nb_all';
		//$users[11]['purpose']     = 'sms_'.$this->launchDate.'_Exh_buyer';
		$users[11]['city']        = '';
		//$users[11]['coupon_code'] = 'FG200rajan';

		$users[12]['mobile']      = '8076649281';
		$users[12]['firstname']   = 'Vikas';
		$users[12]['customer_id'] = '4';
		$users[12]['purpose']     = 'sms_'.$this->launchDate.'_nb_all';
		//$users[12]['purpose']     = 'sms_'.$this->launchDate.'_Exh_buyer';
		$users[12]['city']        = '';
		// // //$users[12]['coupon_code'] = 'FG200vikas';

		// $users[13]['mobile']      = '9899112842';
		// $users[13]['firstname']   = 'Shiv';
		// $users[13]['customer_id'] = '4';
		// $users[13]['purpose']     = 'sms_'.$this->launchDate.'_nb_last30';
		// $users[13]['city']        = '';


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
				//print_r($users);
				//exit;
			}
		}
	}

	public function sendSms($users, $smsBody) {

		$res     = '';
		$smsText = '';

		$unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();

		foreach ($users as $user) {

			// if ($user['purpose'] == 'sms_9may19_3t') {
			//  Log::info('3t please change sms');
			//  exit;
			// }

			$smsText = $smsBody;

			$mobile      = trim($user['mobile']);
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
			//  $mobile = substr($mobile, 1);
			// }

			// if (strlen($mobile) == 12) {
			//  $mobile = substr($mobile, 2);
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
			$url           = "";
			$generated_url = '';

			/********************For Customized SMS*********************/

			//$smsText = str_replace(array("[NAME]"), array($name), $smsText);

			//$res = SendSms::sendSms($mobile, $smsText);
			// if ($res) {
			//  $user['status'] = 1;
			// } else {
			//  $user['status'] = -1;
			// }

			// // if($mobile == '9873621245' || $mobile == '9818137346' || $mobile == '9716834689')
			// // {
			// //   continue;
			// // }

			// NotificationSend::updateMobileStatus($user);
			// continue;
			//exit;
			/********************For Customized SMS*********************/

			// $url = 'http://bit.ly/launch-13dec-nb';
			// if ($user['customer_id'] == 1) {

			//$sizeUrl      = explode('__', $user['purpose']);

			if (!empty($user['city'])) {
				//Log::error('Mobile::'.$mobile.' City::'.$user['city']);
				$size_array   = array_unique(explode(',', $user['city']));
				$sizeid_array = array();

				foreach ($size_array as $value) {

					switch ($value) {
						case 'XXS':
							$sizeid_array[] = 184;
							break;

						case 'XS':
							$sizeid_array[] = 34;
							break;

						case 'S':
							$sizeid_array[] = 32;
							break;

						case 'M':
							$sizeid_array[] = 31;
							break;

						case 'L':
							$sizeid_array[] = 30;
							break;

						case 'XL':
							$sizeid_array[] = 33;
							break;

						case 'XXL':
							$sizeid_array[] = 35;
							break;

						case '3XL':
							$sizeid_array[] = 29;
							break;
					}
				}

				$sizeids         = implode(',', $sizeid_array);
				$sizein_campaign = implode('_', $size_array);
				// $generated_url   = '';

				//$sizeids = '';//for kaftans also show in all-products

				switch ($user['purpose']) {

		
						case 'sms_'.$this->launchDate.'_nb_last30':

						$url = 'http://fgurl.in/hvhIaM';

						break;

						case 'sms_'.$this->launchDate.'_nb_last90-30':

							$url = 'http://fgurl.in/0F4HzO';

							break;

						case 'sms_'.$this->launchDate.'_nb_last180-90':

							$url = 'http://fgurl.in/ZtiC9d';

							break;

						case 'sms_'.$this->launchDate.'_nb_last320-180':

							$url = 'http://fgurl.in/xVrGHo';

							break;

						case 'sms_'.$this->launchDate.'_nb_last640-320':

							$url = 'http://fgurl.in/PZJSKX';

							break;

						case 'sms_'.$this->launchDate.'_nb_all-last640':

							$url = 'http://fgurl.in/xUmeZV';

							break;

						case 'sms_'.$this->launchDate.'_nb_ExDi':

						    $url = 'http://fgurl.in/OnNQn4';

                        break;

                        case 'sms_'.$this->launchDate.'_b_1t':

						    $url = 'http://bit.ly/FGmakeitcount';
                        break;

					 //   case 'sms_'.$this->launchDate.'_nb_all':

						// $url = 'http://bit.ly/EOSS_FG';

						// break;
 

						// case 'sms_'.$this->launchDate.'_Exh_buyer':

						// 	$url = 'http://bit.ly/2KTMHyr';

						// 	break;

						// case 'sms_'.$this->launchDate.'_nb_chandigarh_buyer':

						// 	$url = 'http://bit.ly/2wOEcOG';

						// 	break;

						// case 'sms_'.$this->launchDate.'_nb_ahmedabad_buyer':

						// 	$url = 'http://bit.ly/2Kdzumg';

						// 	break;

						// case 'sms_'.$this->launchDate.'_nb_surat_buyer':

						// 	$url = 'http://bit.ly/2ReshTW';

						// 	break;

						// case 'sms_'.$this->launchDate.'_nb_vadodara_buyer':

						// 	$url = 'http://bit.ly/2Rcfd1j';

						// 	break;
				}

			} else {

				switch ($user['purpose']) {

				
		
						case 'sms_'.$this->launchDate.'_nb_last30':

						$url = 'http://fgurl.in/hvhIaM';

						break;

						case 'sms_'.$this->launchDate.'_nb_last90-30':

							$url = 'http://fgurl.in/0F4HzO';

							break;

						case 'sms_'.$this->launchDate.'_nb_last180-90':

							$url = 'http://fgurl.in/ZtiC9d';

							break;

						case 'sms_'.$this->launchDate.'_nb_last320-180':

							$url = 'http://fgurl.in/xVrGHo';

							break;

						case 'sms_'.$this->launchDate.'_nb_last640-320':

							$url = 'http://fgurl.in/PZJSKX';

							break;

						case 'sms_'.$this->launchDate.'_nb_all-last640':

							$url = 'http://fgurl.in/xUmeZV';

							break;

						case 'sms_'.$this->launchDate.'_nb_ExDi':

						$url = 'http://fgurl.in/OnNQn4';
                        break;

                          case 'sms_'.$this->launchDate.'_b_1t':

						    $url = 'http://bit.ly/FGmakeitcount';
                           break;
				}

				// $generated_url = "https://www.faridagupta.com/all-products?dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_25apr19_nb_last30&utm_location=-1&nofilter=1";
			}

			/* get the short url */
			// if ($generated_url != '') {
			// 	$url = Utility::get_bitly_short_url($generated_url, 'vaibhav15', 'R_eb5ff082a32747059c8bfb39223f0615');
			// }
			// $checkurl = 'http://bit.ly';
			// if ($url == 'RATE_LIMIT_EXCEEDED' || $url == '' || $url == 'UNKNOWN_ERROR') {

			// 	Log::error('Mobile::'.$mobile.' URL::'.$url);

			// 	if ($url != '') {
			// 		echo $url;
			// 		exit;
			// 	}
			// 	//sleep(5);
			// 	continue;
			// }

			// if (!strstr($url, $checkurl)) {
			// 	echo $url;
			// 	exit;
			// }	
            $url = 'http://fgurl.in/falak_c';
			$smsText = str_replace(array("[URL]"), array($url), $smsText);
			$smsText = str_replace(array("[NAME]"), array($name), $smsText);
			//$smsText = str_replace(array("[XXXX]"), array($user['coupon_code']), $smsText);

			// echo $smsText."    ";
			// exit;

			$res = SendSms::sendSms($mobile, $smsText);

			echo $res;

			if ($res) {
				$user['status'] = 1;
			} else {
				$user['status'] = -1;
			}

			// // if($mobile == '9873621245' || $mobile == '9818137346' || $mobile == '9716834689')
			// // {
			// //   continue;
			// // }

			NotificationSend::updateMobileStatus($user);
		}

		// Log::info('Promotional SMS:: Sent');

	}

}
