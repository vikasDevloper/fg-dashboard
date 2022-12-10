<?php
/**
 * Created By: Komal Bhagat
 */
namespace Dashboard\Console\Commands\Users;

use Dashboard\Classes\Helpers\Utility;
use Dashboard\Data\Models\CustomerProductNotify;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\OfflineCustomerEntity;
use Dashboard\Data\Models\SalesFlatOrderAddress;

use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Data\Models\SmsUpdatesLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AttendeesNonattendees extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'attendeesNonattendees:create';

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

		$tag             = '';
		$signature       = config('sms.signature.without_no');
		$small_signature = config('sms.smallsignature.without_no');
		$site            = config('app.site_url');
		$number          = config('app.support_no');

		$cityname       = 'Gurugram';//Show in message
		$exhibitionCity = 'Gurugram';//for getting data
		$cityid         = '57';
		$pincodeLike    = "'^(12)'";
		// $cityLike = "'Ahemdabad|Ahmadabad|Ahmadabad City|Ahmedabad|Ahmedabadedabad|Ahmedavad|Khanpur|Anand|Dharmaj|Gandhingr|Gandhi Nagar|Gandhinagar|Gidhra|Karamsad|Kheda|Nadiad|Opp Dena bank Naranpura Naranpura AHMEDABAD - Gujarat|Petlad|Satelite|Thaltej|Vallabh Vidyanaghar'";

		$cityLike = '';
		//$cityLike = "'Bengaluru|Bengaluru|Banagalore|Bangalor|Bangalore|Bangalore Urban|Bangaluru|Banglore|Bengaluri|Bengaluru|bamgalore'";

		$startDate = "2020-01-17";
		$endDate   = "2020-01-19";

		Log::info($exhibitionCity.' Attendees and Non-Attendees SMS:: Started');

		// get all the user who have unsubscribed
		$unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();
		// get all the user who have register in 'notify me'
		$notifyMeUser = CustomerProductNotify::notifyMeOpenStatusByMobile();
		// get users who got SMS today
		$notToSendSmsUsers = SmsUpdatesLog::getUsersGotSmsToday();

		$offlineCityCustomers = OfflineCustomerEntity::getOfflineCityCustomerData($exhibitionCity, $cityid);

		$cityNewsletterCustomers = NewsletterSubscriber::getNewsletterCitySubscribers($cityid);

		if (!empty($pincodeLike)) {
			$onlineCityCustomers = SalesFlatOrderAddress::getCustomersByPincode($pincodeLike);
		} else {
			$onlineCityCustomers = SalesFlatOrderAddress::getCustomersByCity($cityLike);
		}
		//$onlineCityCustomers = SalesFlatOrderAddress::getCustomersByCity($cityLike);

		echo 'Online Customers::'.count($onlineCityCustomers)."\n";
		echo 'Offline Customers::'.count($offlineCityCustomers)."\n";
		echo 'Subscribers::'.count($cityNewsletterCustomers)."\n";

		$allCustomers = array_merge($offlineCityCustomers, $cityNewsletterCustomers, $onlineCityCustomers);

		$attendeesCustomers = OfflineCustomerEntity::getAttendeesCityCustomerData($exhibitionCity, $cityid, $startDate, $endDate);

		$nonAttendeesCustomers = array_diff($allCustomers, $attendeesCustomers);

		$testusers = array();

		$testusers[1]['mobile']      = '7906077429';
		$testusers[1]['name']        = 'Rajan';
		$testusers[1]['customer_id'] = '2';

		$testusers[2]['mobile']      = '8800745258';
		$testusers[2]['name']        = 'Sandeep';
		$testusers[2]['customer_id'] = '1';

		$testusers[3]['mobile']      = '9818137346';
		$testusers[3]['name']        = 'Sahil Gupta';
		$testusers[3]['customer_id'] = '3';

		$testusers[4]['mobile']      = '7533061241';
		$testusers[4]['name']        = 'Komal Bhagat';
		$testusers[4]['customer_id'] = '4';

		$testusers[5]['mobile']      = '9873621245';
		$testusers[5]['name']        = 'Sanjay Singh';
		$testusers[5]['customer_id'] = '5';

		$testusers[3]['mobile']      = '9818137346';
		$testusers[3]['name']        = 'Sahil';
		$testusers[3]['customer_id'] = '3';

		$testusers[4]['mobile']      = '7533061241';
		$testusers[4]['name']        = 'Komal';
		$testusers[4]['customer_id'] = '4';

		$testusers[5]['mobile']      = '9873621245';
		$testusers[5]['name']        = 'Sanjay';
		$testusers[5]['customer_id'] = '5';

		$testusers[6]['mobile']      = '9667913424';
		$testusers[6]['name']        = 'Needhi';
		$testusers[6]['customer_id'] = '6';

		$testusers[7]['mobile']      = '9999973755';
		$testusers[7]['name']        = 'Nitin';
		$testusers[7]['customer_id'] = '7';

		$testusers[8]['mobile']      = '7428266467';
		$testusers[8]['name']        = 'Sushant';
		$testusers[8]['customer_id'] = '7';

		//for test users
		//$nonAttendeesCustomers = $attendeesCustomers = $testusers;

		$attendeesCustomers    = Utility::uniqueMultidimArray($attendeesCustomers, 'mobile');
		$nonAttendeesCustomers = Utility::uniqueMultidimArray($nonAttendeesCustomers, 'mobile');

		echo 'Total Customers::'.count($allCustomers)."\n";
		echo 'Attendees::'.count($attendeesCustomers)."\n";
		echo 'NonAttendees::'.count($nonAttendeesCustomers);

		//exit;

		$mobileregex = "/^[6-9][0-9]{9}$/";

		if (!empty($attendeesCustomers)) {
			$url = $site.'/all-products?utm_source=sms&utm_medium=cps&utm_campaign='.$exhibitionCity.'_attendees&utm_location=-1';
			/* get the short url */
			$attendees_url = Utility::get_bitly_short_url($url, 'vaibhav15', 'R_eb5ff082a32747059c8bfb39223f0615');

			if ($attendees_url == 'RATE_LIMIT_EXCEEDED' || $attendees_url == '' || $attendees_url == 'UNKNOWN_ERROR') {

				Log::error('Attendees URL::'.$attendees_url);
				exit;
			}

			// $attendeesSMS = "Hi [NAME],\n\nThanks for coming to our exhibition and making it extra special.\nUntil next time, shop online – ".$attendees_url."\n\n".$signature;

			$attendeesSMS = "Hi [NAME], Thank you for attending\nour exhibition in ".$cityname.".\n\nUntil next time, shop online - ".$attendees_url."\n".$small_signature;

			foreach ($attendeesCustomers as $attendees) {

				if (preg_match($mobileregex, $attendees['mobile']) == 0) {
					continue;
				} elseif (strlen($attendees['mobile']) != 10) {
					continue;
				} elseif (in_array($attendees['mobile'], $unsubscribedMobileUser)) {
					continue;
				}
				// elseif (in_array($attendees['mobile'], $notifyMeUser)) {
				// 	continue;
				// }

				$name = ucfirst(strtolower(trim(explode(" ", $attendees['name'])[0])));
				//$name = ucfirst(strtolower(trim($attendees['name'])));

				if (strtolower($name) == 'unknown' || strtolower($name) == 'test') {
					$name = '';
				}

				$smsData                = array();
				$smsText                = str_replace('[NAME]', $name, $attendeesSMS);
				$smsData['name']        = $name;
				$smsData['mobile']      = $attendees['mobile'];
				$smsData['sms_type']    = $exhibitionCity.'_attendees';
				$smsData['sms_content'] = $smsText;

				$insertedSms = SmsUpdates::insert($smsData);

				if ($insertedSms) {
					unset($smsData['sms_content']);
					unset($smsData['name']);
					$name                 = '';
					$smsData['user_type'] = 'Customer';
					$insertedSms          = SmsUpdatesLog::insert($smsData);
				}
			}

			Log::info($exhibitionCity.' Attendees SMS:: Sent');
		}
		//exit;
		if (!empty($nonAttendeesCustomers)) {
			$url              = $site.'/all-products?utm_source=sms&utm_medium=cps&utm_campaign='.$exhibitionCity.'_nonattendees&utm_location=-1';
			$nonattendees_url = Utility::get_bitly_short_url($url, 'vaibhav15', 'R_eb5ff082a32747059c8bfb39223f0615');

			if ($nonattendees_url == 'RATE_LIMIT_EXCEEDED' || $nonattendees_url == '' || $nonattendees_url == 'UNKNOWN_ERROR') {

				Log::error('Non-Attendees URL::'.$nonattendees_url);
				exit;
			}

			// $nonattendeesSMS = "Hi [NAME],\n\nWe missed your presence at the exhibition! Wish we could serve you.\nUntil next time, shop online with COD and Easy Returns & Exchanges -\n".$nonattendees_url."\n\n".$signature;

			$nonattendeesSMS = "Hi [NAME], We missed you at\nour ".$cityname." exhibition!\n\nUntil next time, shop online - ".$nonattendees_url."\n".$signature."\n".$number;

			foreach ($nonAttendeesCustomers as $nonAttendees) {
				if (preg_match($mobileregex, $nonAttendees['mobile']) == 0) {
					continue;
				} elseif (strlen($nonAttendees['mobile']) != 10) {
					continue;
				} elseif (in_array($nonAttendees['mobile'], $unsubscribedMobileUser)) {
					continue;
				}
				// elseif (in_array($nonAttendees['mobile'], $notifyMeUser)) {
				// 	continue;
				// }

				$name = ucfirst(strtolower(trim(explode(" ", $nonAttendees['name'])[0])));

				if (strtolower($name) == 'unknown' || strtolower($name) == 'test') {
					$name = '';
				}

				$smsData                = array();
				$smsText                = str_replace('[NAME]', $name, $nonattendeesSMS);
				$smsData['name']        = $name;
				$smsData['mobile']      = $nonAttendees['mobile'];
				$smsData['sms_type']    = $exhibitionCity.'_nonattendees';
				$smsData['sms_content'] = $smsText;

				$insertedSms = SmsUpdates::insert($smsData);

				if ($insertedSms) {
					unset($smsData['sms_content']);
					unset($smsData['name']);
					$name                 = '';
					$smsData['user_type'] = 'Customer';
					$insertedSms          = SmsUpdatesLog::insert($smsData);
				}
			}

			Log::info($exhibitionCity.' Non-Attendees SMS:: Sent');
		}
	}

}
