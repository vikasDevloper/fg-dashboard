<?php

namespace Dashboard\Console\Commands;

use Dashboard\Classes\Helpers\Falconide;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\NotificationSend;
use Dashboard\Mail\SalePromotions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Dashboard\Data\Models\Covid19Purchaser;


class SendCovidPurchaserEmails extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'covidPurchaserEmail:send {file}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send promotional mails to Farida Gupta Customer Base during covin -19';

	protected $launchDate;

	protected $subject;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->launchDate = '16apr20';
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
		$file       = $this->argument('file');
		$start      = 100*$file;
		$limit      = 10;
		$totalUsers = 10;

		$mailPurpose = 'email_'.$this->launchDate.'_b_global_covid_purchaser';

		Log::info('Promotional Email:: '.$mailPurpose.' Started');

		// $data['tag']     = 'eoss-sale-22';
		//Buyer
	
		$this->subject       = "Your Discount Voucher 😇";
        $data['previewText'] = "You're making a difference.";

		// //Non Buyer
		// $this->subject       = "😍 Introducing Nausheen Collection";
		// $data['previewText'] = "Handcrafted styles in soothing soft cotton fabric, explore the Art of block prints.";

		$data['url'] = "https://goo.gl/N16BLE";
		//$data['template'] = 'emails.promotions.NewArrival_25Apl19_nb';
		//purchaser
		//$data['template'] = 'emails.promotions.9April20_LaunchMailer';
		//buyer
		$data['template'] = 'emails.promotions.17Apr_discount_coupon';

		// $users[0]['email']       = 'sandeep@faridagupta.com';
		// $users[0]['firstname']   = 'Sandeep';
		// $users[0]['customer_id'] = '00001';
		// $users[0]['purpose']     = 'email_'.$this->launchDate.'_20t';
		// $users[0]['city']        = 'M,M,M,M,M,M,M,M,M,M,M,S,M,M,M,M,M,M,M,XS,M,M,M,S,M';
		// //$users[0]['city'] = '';

		// $users[1]['email']       = 'komal@faridagupta.com';
		// $users[1]['firstname']   = 'Komal';
		// $users[1]['customer_id'] = '11111';
		// $users[1]['purpose']     = 'email_'.$this->launchDate.'_20pt';
		// $users[1]['city']        = 'M,M,M,M,M,M,M,M,M,M,M,S';
		// //$users[1]['city'] = '';

		// $users[2]['email']       = 'sahil@faridagupta.com';
		// $users[2]['firstname']   = 'Sahil';
		// $users[2]['customer_id'] = '2222';
		// $users[2]['purpose']     = 'email_'.$this->launchDate.'_19t';
		// $users[2]['city']        = 'XS,XL,XL';
		// //$users[2]['city'] = '';

		// $users[3]['email']       = 'monu@faridagupta.com';
		// $users[3]['firstname']   = 'Monu';
		// $users[3]['customer_id'] = '3333';
		// $users[3]['purpose']     = 'email_'.$this->launchDate.'_18t';
		// $users[3]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		// //$users[3]['city'] = '';

		// $users[4]['email']       = 'komalktr15@gmail.com';
		// $users[4]['firstname']   = 'Komal';
		// $users[4]['customer_id'] = '8888';
		// $users[4]['purpose']     = 'email_'.$this->launchDate.'_13t';
		// $users[4]['city']        = 'S,XL,XL,XL,XL,XL,XXL,XL,XL,XL,XXL';
		// //$users[4]['city'] = '';

		// $users[5]['email']       = 'nitin@faridagupta.com';
		// $users[5]['firstname']   = 'Nitin';
		// $users[5]['customer_id'] = '8888';
		// $users[5]['purpose']     = 'email_'.$this->launchDate.'_11t';
		// $users[5]['city']        = 'M,M,M,M,M,M,M,M,M,M,M,S,M,M,M,M,M,M,M,XS,M,M';
		// //$users[5]['city'] = '';

		// $users[6]['email']       = 'sana@faridagupta.com';
		// $users[6]['firstname']   = 'Sana';
		// $users[6]['customer_id'] = '44444';
		// $users[6]['purpose']     = 'email_'.$this->launchDate.'_9t';
		// $users[6]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		// //$users[6]['city'] = '';

		// $users[7]['email']       = 'sanjay@faridagupta.com';
		// $users[7]['firstname']   = 'Sanjay';
		// $users[7]['customer_id'] = '5555';
		// $users[7]['purpose']     = 'email_'.$this->launchDate.'_6t';
		// $users[7]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		// //$users[7]['city'] = '';

		// $users[9]['email']       = 'ritu@faridagupta.com';
		// $users[9]['firstname']   = 'Ritu';
		// $users[9]['customer_id'] = '7777';
		// $users[9]['purpose']     = 'email_'.$this->launchDate.'_4t';
		// $users[9]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		// //$users[9]['city'] = '';

		// $users[10]['email']       = 'adnan@faridagupta.com';
		// $users[10]['firstname']   = 'Adnan';
		// $users[10]['customer_id'] = '8888';
		// $users[10]['purpose']     = 'email_'.$this->launchDate.'_1t';
		// $users[10]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		// //$users[10]['city'] = '';

		// $users[11]['email']       = 'sushant@faridagupta.com';
		// $users[11]['firstname']   = 'Sushant';
		// $users[11]['customer_id'] = '9999';
		// $users[11]['purpose']     = 'email_'.$this->launchDate.'_1t';
		// $users[11]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		// //$users[11]['city'] = '';

		// $users[12]['email']       = 'shad@faridagupta.com';
		// $users[12]['firstname']   = 'Shad';
		// $users[12]['customer_id'] = '9999';
		// $users[12]['purpose']     = 'email_'.$this->launchDate.'_1t';
		// $users[12]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		// //$users[11]['city'] = '';

		$users[13]['email']       = 'vikas@faridagupta.com';
		$users[13]['firstname']   = 'Vikas';
		$users[13]['customer_id'] = '9999';
		$users[13]['purpose']     = 'email_'.$this->launchDate.'_b_global_covid_purchaser';
		//$users[13]['purpose']     = 'email_'.$this->launchDate.'_b_purchaser';
		$users[13]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		$users[13]['coupon_code'] = 'FGARTISANS24863970';
		//$users[13]['city'] = '';

		// $users[8]['email'] = 'rajan@faridagupta.com';
		// //$users[8]['email']       = 'rajan13215@gmail.com';
		// $users[8]['firstname']   = 'Rajan';
		// $users[8]['customer_id'] = '66666';
		// //$users[8]['purpose']     = 'email_'.$this->launchDate.'_b_purchaser';
	 //    $users[8]['purpose']     = 'email_'.$this->launchDate.'_b_1t';
		// $users[8]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		// $users[8]['coupon_code'] = 'FGARTISANS24863970';

		//$users[8]['city'] = '';
		
		// $users[14]['email']       = 'vinay@faridagupta.com';
		// $users[14]['firstname']   = 'Vinay';
		// $users[14]['customer_id'] = '9999';
		// $users[14]['purpose']     = 'email_'.$this->launchDate.'_1t';
		// $users[14]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		// //$users[11]['city'] = '';

		// $users[15]['email']       = 'shiv@faridagupta.com';
		// $users[15]['firstname']   = 'Shiv';
		// $users[15]['customer_id'] = '9999';
		// $users[15]['purpose']     = 'email_'.$this->launchDate.'_1t';
		// $users[15]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
  //       $users[15]['coupon_code'] = 'FGARTISANS24863970';

		// // // //$users[11]['city'] = '';

		// $users[16]['email']       = 'apoorv@faridagupta.com';
		// $users[16]['firstname']   = 'Apoorv';
		// $users[16]['customer_id'] = '9999';
		// $users[16]['purpose']     = 'email_'.$this->launchDate.'_1t';
		// $users[16]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
  //       $users[16]['coupon_code'] = 'FG200VOWQR';

		// //$users[11]['city'] = '';
 	   
 	//     $users[17]['email']       = 'shivam.vashishtha@faridagupta.com';
		// $users[17]['firstname']   = 'shivam';
		// $users[17]['customer_id'] = '9999';
		// $users[17]['purpose']     = 'email_'.$this->launchDate.'_1t';
		// $users[17]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
  //       $users[17]['coupon_code'] = 'FG200VOWQR';

  //       $users[18]['email']       = 'sneha@faridagupta.com';
		// $users[18]['firstname']   = 'sneha';
		// $users[18]['customer_id'] = '9999';
		// $users[18]['purpose']     = 'email_'.$this->launchDate.'_1t';
		// $users[18]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
  //       $users[18]['coupon_code'] = 'FGARTISANS24863970';






		// $users[4]['email']     = 'web-reax8@mail-tester.com';
		// $users[4]['firstname'] = 'Mail Tester';

		$u = NotificationSend::getCustomers($start, $limit, $mailPurpose);

		echo "\n\n".$totalUsers = count($u);

		if ($totalUsers > 0) {

			$this->sendEmails($users, $data);
			//exit;//for test mails

			$i = 0;

			while ($totalUsers > 0) {

				$users                  = NotificationSend::getCustomers($start, $limit, $mailPurpose);
				echo "\n\n".$totalUsers = count($users);

				if (!empty($users)) {
					$this->sendEmails($users, $data);
					$i = $i+count($users);
					if ($i >= 1000) {
						exit;
					}
				}

			}
		}

	}

	public function sendEmails($users, $data) {
		// get all the user who have unsubscribed
		$unsubscribedEmailUser = NewsletterSubscriber::getEmailUnsubscribers();
        //$buyersremove = NotificationSend::getAllBuyersEmailByDate($this->date);
		foreach ($users as $user) {

			// if ($user['purpose'] == 'email_'.$this->launchDate.'_nb_ExDi') {
			// 	exit;
			// }

			echo $data['to'] = strtolower(trim($user['email']));
			echo $coupon_code = Covid19Purchaser::getCouponByMail($user['email']);

			if (empty($data['to'])) {
				$user['status'] = -1;
				NotificationSend::updateStatus($user);
				continue;
			} else if (filter_var($data['to'], FILTER_VALIDATE_EMAIL) == false) {
				$user['status'] = -1;
				NotificationSend::updateStatus($user);
				continue;
			} else if (in_array($data['to'], $unsubscribedEmailUser)) {
				$user['status'] = -1;
				NotificationSend::updateStatus($user);
				continue;
			}else if($coupon_code == "")
			{
				$user['status'] = -1;
				NotificationSend::updateStatus($user);
				continue;
			}

			$firstname         = explode(' ', $user['firstname']);
			$data['firstname'] = ucfirst(strtolower(trim($firstname['0'])));

			if (strtolower($data['firstname']) == 'unknown' || strtolower($data['firstname']) == 'test') {
				$data['firstname'] = '';
			}

			$data['subject'] = str_replace(array('[NAME]'), array($data['firstname']), $this->subject);

			$url     = '';
			$sizeids = '';

			if (!empty($user['city'])) {
				//Log::error('Mobile::'.$mobile.' City::'.$user['city']);
				$size_array   = array_unique(explode(',', $user['city']));
				$sizeid_array = array();

				foreach ($size_array as $value) {

					switch ($value) {
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
				$size_campaign   = '';

				switch ($user['purpose']) {
					case 'email_'.$this->launchDate.'_b_20pt':

						$size_campaign = $this->launchDate.'_20pt_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_20t':

						$size_campaign = $this->launchDate.'_20t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_19t':

						$size_campaign = $this->launchDate.'_19t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_18t':

						$size_campaign = $this->launchDate.'_18t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_17t':

						$size_campaign = $this->launchDate.'_17t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_16t':

						$size_campaign = $this->launchDate.'_16t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_15t':

						$size_campaign = $this->launchDate.'_15t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_14t':

						$size_campaign = $this->launchDate.'_14t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_13t':

						$size_campaign = $this->launchDate.'_b_13t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_12t':

						$size_campaign = $this->launchDate.'_b_12t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_11t':

						$size_campaign = $this->launchDate.'_b_11t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_10t':

						$size_campaign = $this->launchDate.'_10t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_9t':

						$size_campaign = $this->launchDate.'_9t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_8t':

						$size_campaign = $this->launchDate.'_8t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_7t':

						$size_campaign = $this->launchDate.'_7t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_6t':

						$size_campaign = $this->launchDate.'_6t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_5t':

						$size_campaign = $this->launchDate.'_5t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_4t':

						$size_campaign = $this->launchDate.'_4t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_3t':

						$size_campaign = $this->launchDate.'_3t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_2t':

						$size_campaign = $this->launchDate.'_2t_'.$sizein_campaign;
						break;

					case 'email_'.$this->launchDate.'_b_1t':

						$size_campaign = $this->launchDate.'_1t_'.$sizein_campaign;
						break;
                    case 'email_'.$this->launchDate.'_b_purchaser':

						$size_campaign = $this->launchDate.'_b_purchaser_'.$sizein_campaign;
						break;
					case 'email_'.$this->launchDate.'_nb_last30':

						$size_campaign = $this->launchDate.'_nb_last30';
						break;

					case 'email_'.$this->launchDate.'_nb_last90-30':

						$size_campaign = $this->launchDate.'_nb_last90-30';
						break;

					case 'email_'.$this->launchDate.'_nb_last180-90':

						$size_campaign = $this->launchDate.'_nb_last180-90';
						break;

					case 'email_'.$this->launchDate.'_nb_last320-180':

						$size_campaign = $this->launchDate.'_nb_last320-180';
						break;

					case 'email_'.$this->launchDate.'_nb_last640-320':

						$size_campaign = $this->launchDate.'_nb_last640-320';
						break;

					case 'email_'.$this->launchDate.'_nb_all-last640':

						$size_campaign = $this->launchDate.'_nb_all-last640';
						break;

					case 'email_'.$this->launchDate.'_nb_ExDi':

						$size_campaign = $this->launchDate.'_nb_ExDi';
						break;
						case 'email_'.$this->launchDate.'_b_global_covid_purchaser':

						$size_campaign = $this->launchDate.'_b_global_covid_purchaser';
						break;
						// case 'email_'.$this->launchDate.'_nb':

						// 	$size_campaign = 'nb';
						// 	break;
				}

			} else {
				switch ($user['purpose']) {

					case 'email_'.$this->launchDate.'_nb_last30':

						$size_campaign = $this->launchDate.'_nb_last30';
						break;

					case 'email_'.$this->launchDate.'_nb_last90-30':

						$size_campaign = $this->launchDate.'_nb_last90-30';
						break;

					case 'email_'.$this->launchDate.'_nb_last180-90':

						$size_campaign = $this->launchDate.'_nb_last180-90';
						break;

					case 'email_'.$this->launchDate.'_nb_last320-180':

						$size_campaign = $this->launchDate.'_nb_last320-180';
						break;

					case 'email_'.$this->launchDate.'_nb_last640-320':

						$size_campaign = $this->launchDate.'_nb_last640-320';
						break;

					case 'email_'.$this->launchDate.'_nb_all-last640':

						$size_campaign = $this->launchDate.'_nb_all-last640';
						break;

					    case 'email_'.$this->launchDate.'_nb_ExDi':

						$size_campaign = $this->launchDate.'_nb_ExDi';
						break;

						case 'email_'.$this->launchDate.'_Exh_buyer':

						$size_campaign = $this->launchDate.'_Exh_buyer';
							break;
						case 'email_'.$this->launchDate.'_b_purchaser':

						$size_campaign = $this->launchDate.'_b_purchaser_';
						break;
							case 'email_'.$this->launchDate.'_b_global_covid_purchaser':

						$size_campaign = $this->launchDate.'_b_global_covid_purchaser';
						break;
				}

			}
			$email = '"'.$data['firstname'].'" <'.$data['to'].'>';
			$data['tag'] = $user['purpose'];
			if (config('mail.through') == 'Falconide') {

				$data['message'] = (string) View::make($data['template'])->with([
						'subject'       => $data['subject'],
						'previewText'   => $data['previewText'],
						'firstname'     => $data['firstname'],
						'customer_id'   => $user['customer_id'],
						'size_id'       => $sizeids,
						'size_campaign' => $size_campaign,
                        'coupon'        => $coupon_code

					]);

				$data["replyTo"] = config('mail.reply-to.address');
				$data["from"]    = config('mail.from.address');

				$falconideObj = new Falconide();
				try {

					$res = $falconideObj->createMail($data);
					if ($res->message == 'SUCCESS') {
						$user['status'] = 1;
					} else {
						print_r($res);
						continue;
					}
					//$user['status'] = 1;
				} catch (\Exception $e) {
					echo "mail not sent".$e->getMessage();
					$user['status'] = -1;
				}

			} else {
				try {
					Mail::to($data['to'])->send(new SalePromotions($data));
					$user['status'] = 1;
				} catch (\Exception $e) {
					echo "mail not sent".$e->getMessage();
					$user['status'] = -1;
				}
			}

			NotificationSend::updateStatus($user);
			Covid19Purchaser::updateEmailCouponStatus($user,$coupon_code);
		}

	}
}
