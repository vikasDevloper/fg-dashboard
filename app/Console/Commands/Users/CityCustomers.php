<?php
/**
 * User: Komal Bhagat
 */
namespace Dashboard\Console\Commands\Users;

use Dashboard\Classes\Helpers\CreateMailTemplates;
use Dashboard\Classes\Helpers\Falconide;
use Dashboard\Classes\Helpers\Utility;
use Dashboard\Data\Models\BouncedEmails;
use Dashboard\Data\Models\CustomerProductNotify;
use Dashboard\Data\Models\EmailUpdates;
use Dashboard\Data\Models\OfflineCustomerEntity;

use Dashboard\Data\Models\NewsletterSubscriber;

use Dashboard\Data\Models\SalesFlatOrderAddress;
use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Helpers\SendSms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CityCustomers extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'cityCustomers:create {data}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

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

	public function handle() {

		set_time_limit(0);

		$data = $this->argument('data');

		$sendSms      = isset($data['sendsms'])?$data['sendsms']:0;
		$sendEmail    = isset($data['sendemail'])?$data['sendemail']:0;
		$city         = isset($data['city'])?$data['city']:'';
		$cityLike     = isset($data['citylike'])?$data['citylike']:'';
		$emailSubject = isset($data['subject'])?$data['subject']:'';
		$previewText  = isset($data['previewtext'])?$data['previewtext']:'';
		$smsData      = isset($data['smscontent'])?$data['smscontent']:'';
		$templateName = isset($data['templatename'])?$data['templatename']:'';
		$userType     = isset($data['usertype'])?$data['usertype']:'';

		$citydata = explode('-', $city);
		$cityid   = $citydata['0'];
		$cityname = $citydata['1'];

		$AreaLike = $cityLike;

		$pincodes = explode(',', $AreaLike);
		$cityLike = str_replace(',', '|', $cityLike);

		$pincodeLike = '';

		if (is_numeric($pincodes[0])) {
			$pincodeLike = "'^(".$cityLike.")'";
			//$pincodeLike = "'".$pincodes."%'";
		} else {
			$cityLike = "'".$cityLike."'";
		}

		$signature = config('sms.smallsignature.without_no');
		//$signature = config('sms.signature.without_no');
		$site      = config('app.site_url');
		// $number    = config('app.support_no');
		$number = config('sms.supportNo.support_no');

		//$template_name = 'emails.promotions.chandigarh-exhibition';

		$template_name = $templateName;
		//'emails.promotions.hyderabadExhibition';

		$sendSms   = isset($sendSms)?$sendSms:0;
		$sendEmail = isset($sendEmail)?$sendEmail:0;

		if ($sendSms == 1) {
			Log::info($cityname.' Exhibition SMS Started:: Adding');
		} else {
			Log::info($cityname.' Exhibition Email Started:: Adding');
		}

		$link           = config('app.site_url').'/'.'all-category.html';
		$cityname       = $cityname;//show on sms content
		$exhibitionCity = $cityname;//city name in database
		$cityid         = $cityid;//city id in exhibition_city table

		$testEmployee            = array();
		$offlineCityCustomers    = array();
		$cityNewsletterCustomers = array();
		$onlineCityCustomers     = array();

		//"'Hyderabad|Hyderabad'";
		//sales_flat_order table city name
		$subject = $emailSubject;
		//'Reminder: Exhibiting In '.ucfirst(strtolower($cityname));
		//subject for email
		//$subject            = 'Reminder: Exhibition in Juhu Starts Tomorrow';
		$sms_utm_campaign   = $tag_sms   = $exhibitionCity.'_sms';//utm_campaign for SMS
		$email_utm_campaign = $tag_email = $exhibitionCity.'_email';//utm_campaign for Email

		$testEmployee     = array();
		
		$testEmployee[0]  = array("email" => "komal@faridagupta.com", "name" => "Komal Bhagat", "mobile" => "7533061241");
		$testEmployee[1]  = array("email" => "rajan@faridagupta.com", "name" => "Rajan", "mobile" => "9045682529");
		$testEmployee[2]  = array("email" => "sanjay@faridagupta.com", "name" => "Sanjay Singh", "mobile" => "9873621245");
		$testEmployee[3]  = array("email" => "sahil@faridagupta.com", "name" => "Sahil Gupta", "mobile" => "9818137346");
		$testEmployee[4]  = array("email" => "sandeep@faridagupta.com", "name" => "Sandeep", "mobile" => "8800745258");
		$testEmployee[5]  = array("email" => "zeba@faridagupta.com", "name" => "Zeba Aslam", "mobile" => "8130066017");
		$testEmployee[6]  = array("email" => "sushant@faridagupta.com", "name" => "Sushant", "mobile" => "7428266467");
		$testEmployee[7]  = array("email" => "monu@faridagupta.com", "name" => "Monu", "mobile" => "9818648134");
		$testEmployee[0]  = array("email" => "vikas@faridagupta.com", "name" => "Vikas", "mobile" => "8076649281");
		$testEmployee[9]  = array("email" => "nitin@faridagupta.com", "name" => "Nitin", "mobile" => "9999973755");
		$testEmployee[10] = array("email" => "varsha@faridagupta.com", "name" => "Varsha", "mobile" => "9999060387");
		$testEmployee[11] = array("email" => "adnan@faridagupta.com", "name" => "Adnan", "mobile" => "9910067249");
		$testEmployee[12] = array("email" => "shad@faridagupta.com", "name" => "Shad", "mobile" => "8010258215");
		$testEmployee[13] = array("email" => "sandeep@faridagupta.com", "name" => "Sandeep", "mobile" => "8826065309");
		$testEmployee[14] = array("email" => "nainika@faridagupta.com", "name" => "nainika", "mobile" => "8090379664");
		$testEmployee[15] = array("email" => "shiv@faridagupta.com", "name" => "shiv", "mobile" => "9899112842");

		if (strtolower($userType) == strtolower('TestUsers')) {

			$customers = $testEmployee;

		} else {
			 //  $exhibition_ids = '129,104,105,82,81,68,67,51,33,16';
			  //$exhibition_ids = '101,78,46,25,79,47,24,12,10,77,102,65,45,23,11,125,64';
			// //Cymroza-11,23,45,65//Abitare-10//Mithila-78,46,25//Ajivasan-64//Cache-32//Sagun-12,24,47,79
			  //$offlineCityCustomers = OfflineCustomerEntity::getGalleryCityCustomerData($cityid, $exhibition_ids);

			/*if(($smsData!= '') || ($templateName!='')){
				if((preg_match('/[COUPON]/', $smsData)) || 
					(preg_match('/Non-buyer/', $templateName))){
	            	$cityNewsletterCustomers = NewsletterSubscriber::getNewsletterCitySubscribers($cityid);
				//echo "nonbutey"; echo $templateName; echo 'sms'.$smsData;

					if (!empty($pincodeLike)) {
						$onlineCityCustomers = SalesFlatOrderAddress::getCustomersByPincode($pincodeLike);
					} else {
						$onlineCityCustomers = SalesFlatOrderAddress::getCustomersByCity($cityLike);
					}            	
	            } else {
					//echo "butey"; echo $templateName; echo 'sms'.$smsData;
		        	$offlineCityCustomers = OfflineCustomerEntity::getOfflineCityCustomerData($exhibitionCity, $cityid);
				} 	
			} 	
			else {
				echo "No SMS EMail content";
			}
  */
			//buyer
            $offlineCityCustomers = OfflineCustomerEntity::getOfflineCityCustomerData($exhibitionCity, $cityid);
             //nonbuyer start
	    	$cityNewsletterCustomers = NewsletterSubscriber::getNewsletterCitySubscribers($cityid);

			if (!empty($pincodeLike)) {
				$onlineCityCustomers = SalesFlatOrderAddress::getCustomersByPincode($pincodeLike);
			} else {
				$onlineCityCustomers = SalesFlatOrderAddress::getCustomersByCity($cityLike);
			}
             // nonbuyer end
			echo 'Offline::'.count($offlineCityCustomers)."\n";
			echo 'Newsletter::'.count($cityNewsletterCustomers)."\n";
			echo 'Online::'.count($onlineCityCustomers)."\n";
			/***************** For Noida exhibition *********************/
			// $sms_utm_campaign   = $tag_sms   = 'Noida_sms';
			// $email_utm_campaign = $tag_email = 'Noida_email';

			// $cityLike1  = "'Delhi|New Delhi|Uttar Pradesh'";
			// $streetlike = "'preet vihar|vivek vihar|shahdra|mayur vihar|patparganj|lakshmi nagar|laxmi nagar|gandhi nagar|vaishali|GAZIABAD|Ghaziabad|Gaziyabad|Ghaziabad UP|Anand Vihar|laxmimagar|Dilshad Garden|East Delhi'";

			// $streetCustomers = SalesFlatOrderAddress::getCustomersByStreetCity($streetlike, $cityLike1);

			// echo 'Street Customer::'.count($streetCustomers)."\n";

			// $customers = array_merge($testEmployee, $offlineCityCustomers, $cityNewsletterCustomers, $onlineCityCustomers, $streetCustomers);

			/***************** For Noida exhibition *********************/

			$customers = array_merge($testEmployee, $offlineCityCustomers, $cityNewsletterCustomers, $onlineCityCustomers);

			echo 'Total::'.count($customers)."\n";
			//exit;
		}

		if (!empty($customers)) {

			$customers = Utility::uniqueMultidimArray($customers, 'mobile');

			echo 'Total Unique Customers::'.count($customers)."\n";
			//exit;
			//send sms
			if ($sendSms == 1) {

				$sendSmsCount       = 0;
				$invalidNumber      = 0;
				$unsubscriber       = 0;
				$alreadyGotSmsToday = 0;

				$url = $link.'?utm_source=sms&utm_medium=cps&utm_campaign='.$sms_utm_campaign.'&utm_location=-1';
				/* get the short url */
				//$bitlyLink = Utility::get_bitly_short_url($url, 'vaibhav15', 'R_eb5ff082a32747059c8bfb39223f0615');
				$bitlyLink = 'https://goo.gl/4K4R8T';

				//$smsBody = "Hi [NAME],\n\nToday is the last day of Farida Gupta's exhibition of her Summer 2018 collection in Hyderabad.\n\nVenue : Kalinga Cultural Trust, Plot No 1269, Road No 12, Banjara Hills\n\nTime : 10AM - 8PM\n\nDirections : https://goo.gl/YwCkg9\n\nSee you soon!"."\n\n".$signature."\n".$number;

				// $smsBody = "Hi [NAME],\n\n".$smsData."\n\n".$signature."\n".$number;
				$smsBody = $smsData."\n\n".$signature."\n".$number;

				// get all the user who have unsubscribed
				$unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();
				// get all the user who have register in 'notify me'
				//$notifyMeUser = CustomerProductNotify::notifyMeOpenStatusByMobile();

				$notToSendSmsUsers = SmsUpdates::getUsersGotSmsTodayFromSmsUpdates();


				foreach ($customers as $customer) {
					$mobileregex = "/^[6-9][0-9]{9}$/";

					if (preg_match($mobileregex, $customer['mobile']) == 0) {
						$invalidNumber++;
						continue;
					} else if (strlen($customer['mobile']) != 10) {
						$invalidNumber++;
						continue;
					} else if (in_array($customer['mobile'], $unsubscribedMobileUser)) {
						$unsubscriber++;
						continue;
					} elseif (in_array($customer['mobile'], $notToSendSmsUsers)) {
						$alreadyGotSmsToday++;
						continue;
					}
					// else if (in_array($customer['mobile'], $notifyMeUser)) {
					// 	continue;
					// }

					$name = ucfirst(strtolower(trim(explode(" ", $customer['name'])[0])));

					if (strtolower($name) == 'unknown' || strtolower($name) == 'test') {
						$name = '';
					}
 
					//$sendSmsCount++;
					$coupon_code = "";
					$coupon_code = "EXB".Utility::random_num(4);
					$smsData = array();
					$smsText = str_replace('[NAME]', $name, $smsBody);
					//$smsText = str_replace('[COUPON]', $coupon_code, $smsBody);
					//echo " ".$smsData['mobile'] = $customer['mobile'];
					$smsData['mobile']      = $customer['mobile'];
					$smsData['sms_type']    = $tag_sms;
					$smsData['sms_content'] = $smsText;
					$smsData['name']        = $name;

                   
					$insertedSms = 0;
					if (strtolower($userType) == strtolower('TestUsers')) {
						//echo $smsData['mobile'];
						SendSms::sendSms($smsData['mobile'], $smsData['sms_content']);
					} else {
						$insertedSms = SmsUpdates::insert($smsData);
					}

					if ($insertedSms) {
						$sendSmsCount++;
						unset($smsData['sms_content']);
						unset($smsData['name']);
						$smsData['user_type'] = 'Customer';
						//$insertedSms          = SmsUpdatesLog::insert($smsData);

					}
					// if (SmsUpdates::where('mobile', '=', $customer['mobile'])->count() > 0) {
					//  		continue;
					// }

				}

				echo ' Customers to send sms :: '.$sendSmsCount."\n";
				echo ' Invalid Numbers :: '.$invalidNumber."\n";
				echo ' Unsubcriber :: '.$unsubscriber."\n";
				echo ' AlreadyGotSmsToday :: '.$alreadyGotSmsToday."\n";

				Log::info($cityname.' Exhibition SMS :: Added '.$sendSmsCount.' Invalid '.$invalidNumber.' Unsubcriber '.$unsubscriber.' AlreadyGotSmsToday '.$alreadyGotSmsToday);
			}
			//exit;
			//send email
			if ($sendEmail == 1) {

				$sendEmalCount     = 0;
				$emptyEmail        = 0;
				$invalidEmail      = 0;
				$unsubscriberEmail = 0;
				$bounced           = 0;

				$createMailObj                = new CreateMailTemplates;
				$template_data                = array();
				$template_data['subject']     = $subject;
				$template_data['previewText'] = $previewText;
				$emailContent                 = $createMailObj->createTemplateView($template_name, $template_data);

				// get all the user who have unsubscribed
				$unsubscribedEmailUser = NewsletterSubscriber::getEmailUnsubscribers();
				// get all the user who have register in 'notify me'
				$notifyMeUserEmail = CustomerProductNotify::notifyMeOpenStatusByEmail();

				$bouncedEmails = BouncedEmails::getAllEmails();

				if (!empty($emailContent)) {

					foreach ($customers as $customer) {

						if (empty($customer['email'])) {
							$emptyEmail++;
							continue;
						} else if (filter_var($customer['email'], FILTER_VALIDATE_EMAIL) == false) {
							$invalidEmail++;
							continue;
						} else if (in_array($customer['email'], $unsubscribedEmailUser)) {
							$unsubscriberEmail++;
							continue;
						} else if (in_array($customer['email'], $bouncedEmails)) {
							$bounced++;
							continue;
						}
						// else if (in_array($customer['email'], $notifyMeUserEmail)) {
						// 	continue;
						// }

						$name = ucfirst(strtolower(trim(explode(" ", $customer['name'])[0])));

						$emailData                  = array();
						$emailData['customer_id']   = '';
						$emailData['email']         = $customer['email'];
						$emailData['name']          = $name;
						$emailData['email_type']    = $tag_email;
						$emailData['subject']       = $subject;
						$coupon_code = "";
						$coupon_code = "EXB".Utility::random_num(4);
						$emailData['email_content'] = str_replace(array("[NAME]", "[SUBJECT]", "[COUPON]"), array($name, $subject, $coupon_code), $emailContent);

						//dd($emailData);
						$insertedEmail = 0;
						if (strtolower($userType) == strtolower('TestUsers')) {

							$data              = array();
							$data['firstname'] = $name;
							$data['to']        = $emailData['email'];
							$data['subject']   = $emailData['subject'];
							$data['tag']       = $emailData['email_type'];
							$data['message']   = $emailData['email_content'];
							$falconideObj      = new Falconide();
							$msgSent           = $falconideObj->createMail($data);

							// print_r($msgSent);
							// exit;

						} else {
							$insertedEmail = EmailUpdates::insert($emailData);
						}

						if ($insertedEmail) {
							$sendEmalCount++;
							unset($emailData['email_content']);
							unset($emailData['name']);
							$emailData['user_type'] = 'Customer';
							//$insertedEmail          = EmailUpdatesLog::insert($emailData);
						}

					}
				}

				echo 'Customers to send email::'.$sendEmalCount."\n";
				echo ' Empty Emails :: '.$emptyEmail."\n";
				echo ' Invalid Emails :: '.$invalidEmail."\n";
				echo ' Unsubcriber Emails :: '.$unsubscriberEmail."\n";
				echo ' Bounced Emails :: '.$bounced."\n";

				Log::info($cityname.' Exhibition Emails :: Added '.$sendEmalCount.' Empty Emails '.$emptyEmail.'  Invalid '.$invalidEmail.' Unsubcriber '.$unsubscriberEmail.' BouncedEmails '.$bounced);
			}
		}
	}

}
