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

class SendPromotionalEmails1 extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'promotionalEmail1:send {file}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send promotional mails to Farida Gupta Customer Base';

	protected $launchDate;

	protected $subject;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->launchDate = '18june20';
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

		$mailPurpose = 'email_'.$this->launchDate.'_nb_all';

		Log::info('Promotional Email:: '.$mailPurpose.' Started');

		// $data['tag']     = 'eoss-sale-22';

		//Buyer
 
        $this->subject       = "Introducing FG Face Masks";
        $data['previewText'] = "Safety Comes First. Shop Now.";

		// //Non Buyer
		// $this->subject       = "😍 Introducing Nausheen Collection";
		// $data['previewText'] = "Handcrafted styles in soothing soft cotton fabric, explore the Art of block prints.";

		$data['url'] = "https://goo.gl/N16BLE";
		//$data['template'] = 'emails.promotions.NewArrival_25Apl19_nb';
		$data['template'] = 'emails.promotions.14June_launch_mailer';
		

		//$data['template']      = 'emails.promotions.eoss-sale-9jan';

		// $users[0]['email']       = 'sandeep@faridagupta.com';
		// $users[0]['firstname']   = 'Sandeep';
		// $users[0]['customer_id'] = '00001';
		// $users[0]['purpose']     = 'email_'.$this->launchDate.'_Global_cust';
		// $users[0]['city']        = '';

		// $users[1]['email']       = 'komal@faridagupta.com';
		// $users[1]['firstname']   = 'Komal';
		// $users[1]['customer_id'] = '11111';
		// $users[1]['purpose']     = 'email_'.$this->launchDate.'_Global_cust';
		// $users[1]['city']        = '';

		$users[8]['email'] = 'rajan@faridagupta.com';
		//$users[8]['email']       = 'rajan13215@gmail.com';
		$users[8]['firstname']   = 'Rajan';
		$users[8]['customer_id'] = '66666';
		$users[8]['purpose']     = 'email_'.$this->launchDate.'_b_cod';
		$users[8]['city']        = 'M,L';

		// $users[2]['email']       = 'sahil@faridagupta.com';
		// $users[2]['firstname']   = 'Sahil';
		// $users[2]['customer_id'] = '2222';
		// $users[2]['purpose']     = 'email_'.$this->launchDate.'_global_prom';
		// $users[2]['city']        = '';

		// $users[3]['email']       = 'monu@faridagupta.com';
		// $users[3]['firstname']   = 'Monu';
		// $users[3]['customer_id'] = '3333';
		// $users[3]['purpose']     = 'email_'.$this->launchDate.'_global_prom';
		// $users[3]['city']        = '';

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
		// $users[6]['purpose']     = 'email_'.$this->launchDate.'_global_prom';
		// $users[6]['city']        = '';

		// $users[7]['email']       = 'sanjay@faridagupta.com';
		// $users[7]['firstname']   = 'Sanjay';
		// $users[7]['customer_id'] = '5555';
		// $users[7]['purpose']     = 'email_'.$this->launchDate.'_global_prom';
		// $users[7]['city']        = '';

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
		// $users[12]['purpose']     = 'email_'.$this->launchDate.'_global_prom';
		// $users[12]['city']        = '';

		$users[13]['email']       = 'vikas@faridagupta.com';
		$users[13]['firstname']   = 'Vikas';
		$users[13]['customer_id'] = '9999';
		$users[13]['purpose']     = 'email_'.$this->launchDate.'_b_cod';
		$users[13]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		//$users[11]['city'] = '';

		// $users[14]['email']       = 'vinay@faridagupta.com';
		// $users[14]['firstname']   = 'Vinay';
		// $users[14]['customer_id'] = '9999';
		// $users[14]['purpose']     = 'email_'.$this->launchDate.'_1t';
		// $users[14]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
		// //$users[11]['city'] = '';

		// $users[4]['email']     = 'web-reax8@mail-tester.com';
		// $users[4]['firstname'] = 'Mail Tester';

		$u = NotificationSend::getCustomers($start, $limit, $mailPurpose);

		echo "\n\n".$totalUsers = 1;//count($u);

		if ($totalUsers > 0) {

			 $this->sendEmails($users, $data);
			  exit;//for test mails

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

		foreach ($users as $user) {

			// if ($user['purpose'] == 'email_'.$this->launchDate.'_nb_ExDi') {
			// 	exit;
			// }

			echo $data['to'] = strtolower(trim($user['email']));

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
						// case 'email_'.$this->launchDate.'_20pt':

						// 	$size_campaign = '20pt_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_20t':

						// 	$size_campaign = '20t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_19t':

						// 	$size_campaign = '19t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_18t':

						// 	$size_campaign = '18t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_17t':

						// 	$size_campaign = '17t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_16t':

						// 	$size_campaign = '16t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_15t':

						// 	$size_campaign = '15t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_14t':

						// 	$size_campaign = '14t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_13t':

						// 	$size_campaign = '13t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_12t':

						// 	$size_campaign = '12t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_11t':

						// 	$size_campaign = '11t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_10t':

						// 	$size_campaign = '10t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_9t':

						// 	$size_campaign = '9t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_8t':

						// 	$size_campaign = '8t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_7t':

						// 	$size_campaign = '7t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_6t':

						// 	$size_campaign = '6t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_5t':

						// 	$size_campaign = '5t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_4t':

						// 	$size_campaign = '4t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_3t':

						// 	$size_campaign = '3t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_2t':

						// 	$size_campaign = '2t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_1t':

						// 	$size_campaign = '1t_'.$sizein_campaign;
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_last30':

						// 	$size_campaign = 'nb_last30';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_last90-30':

						// 	$size_campaign = 'nb_last90-30';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_last180-90':

						// 	$size_campaign = 'nb_last180-90';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_last320-180':

						// 	$size_campaign = 'nb_last320-180';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_last640-320':

						// 	$size_campaign = 'nb_last640-320';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_all-last640':

						// 	$size_campaign = 'nb_all-last640';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_ExDi':

						// 	$size_campaign = 'nb_ExDi';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb':

						// 	$size_campaign = 'nb';
						// 	break;
				}

			} else {
				switch ($user['purpose']) {

						// case 'email_'.$this->launchDate.'_nb_last30':

						// 	$size_campaign = 'nb_last30';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_last90-30':

						// 	$size_campaign = 'nb_last90-30';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_last180-90':

						// 	$size_campaign = 'nb_last180-90';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_last320-180':

						// 	$size_campaign = 'nb_last320-180';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_last640-320':

						// 	$size_campaign = 'nb_last640-320';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_all-last640':

						// 	$size_campaign = 'nb_all-last640';
						// 	break;

						// case 'email_'.$this->launchDate.'_nb_ExDi':

						// 	$size_campaign = 'nb_ExDi';
						// 	break;

					case 'email_'.$this->launchDate.'_Global_cust':

						$size_campaign = 'Global_cust';
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

		}

	}
}
