<?php
/**
 * User: Komal Bhagat
 */
namespace Dashboard\Console\Commands\Users;

use Dashboard\Classes\Helpers\CreateSmsTemplates;
use Dashboard\Data\Models\CustomerProductNotify;
use Dashboard\Data\Models\EmailSmsCronLog;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Data\Models\SmsUpdatesLog;
use Illuminate\Console\Command;

class OneTimeCustomers30daysSms extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'oneTimeCustomers30daysSms:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'One Time Customers Sms (last 30 days)';

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

		$signature = config('sms.signature.without_no');

		$number = config('app.support_no');

		$exclude30Days = SalesFlatOrder::exclude30DaysCustomers();
		$allCustomers  = SalesFlatOrder::oneTimeCustomers();

		$customers = array_diff($allCustomers, $exclude30Days);

		if (!empty($customers)) {

			echo 'Total Customers::'.count($customers)."\n";

			$smsContentData = $this->createSms();
			//dd($emailContentData);
			// get all the user who have unsubscribed
			$unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();
			// get all the user who have register in 'notify me'
			$notifyMeUser = CustomerProductNotify::notifyMeOpenStatusByMobile();

			if (!empty($smsContentData)) {

				$customerCount = 0;

				foreach ($customers as $customer) {
					$smsData = array();

					if (empty($customer['mobile'])) {
						continue;
					} else if (strlen($customer['mobile']) != 10) {
						continue;
					} else if (in_array($customer['mobile'], $unsubscribedMobileUser)) {
						continue;
					} else if (in_array($customer['mobile'], $notifyMeUser)) {
						continue;
					}

					//$customer['mobile'] = '7533061241';

					$smsData['name'] = $name = ucfirst(strtolower(trim(explode(" ", $customer['name'])[0])));
					if (strtolower($name) == 'unknown' || strtolower($name) == 'test') {
						$name = '';
					}

					$smsText                = str_replace(array("[NAME]"), array($smsData['name']), $smsContentData['sms_content']);
					$smsData['mobile']      = $customer['mobile'];
					$smsData['sms_type']    = $smsContentData['tag_sms'];
					$smsData['sms_content'] = $smsText;

					$insertedSms = SmsUpdates::insert($smsData);

					if ($insertedSms) {
						$customerCount++;
						unset($smsData['sms_content']);
						unset($smsData['name']);
						$smsData['user_type'] = 'Customer';
						$insertedSms          = SmsUpdatesLog::insert($smsData);
					}

					//break;

				}

			}

			$lastSmsLog                      = array();
			$lastSmsLog['message_type']      = $smsContentData['sms_type'];
			$lastSmsLog['subject']           = '';
			$lastSmsLog['users_count']       = $customerCount;
			$lastSmsLog['communiction_type'] = 'sms';
			$lastSmsLog['utm_campaign']      = $smsContentData['utm_campaign'];
			EmailSmsCronLog::insert($lastSmsLog);

			echo 'Customers to send sms::'.$customerCount."\n";
		}
	}

	/*
	 *  create sms
	 */

	public function createSms() {
		$site                      = config('app.site_url');
		$smsContentData            = array();
		$smsContentData['tag_sms'] = $smsContentData['utm_campaign'] = $utm_campaign = 'U30-D-1T';
		$lastSendData              = EmailSmsCronLog::lastSmsSend($utm_campaign);

		$lastSms = 'seasonal_collection';
		if (!empty($lastSendData)) {
			foreach ($lastSendData as $lastSend) {
				$lastSms = $lastSend['message_type'];
				break;
			}
		}

		switch ($lastSms) {
			case "seasonal_collection":
				//for FgSteal Message1
				$smsContentData['sms_type']    = 'fg_steal';
				$createSmsObj                  = new CreateSmsTemplates;
				$smsContentData['sms_content'] = $createSmsObj->createSmsFgSteal($utm_campaign);
				break;
			case "fg_steal":
				//for Top Sellers Message2
				$smsContentData['sms_type']    = 'top_seller';
				$createSmsObj                  = new CreateSmsTemplates;
				$smsContentData['sms_content'] = $createSmsObj->createSmsTopSellers($utm_campaign);
				break;
			case "top_seller":
				//for seasonalCollection Message3
				$smsContentData['sms_type']    = 'seasonal_collection';
				$createSmsObj                  = new CreateSmsTemplates;
				$smsContentData['sms_content'] = $createSmsObj->createSmsSeasonalCollection($utm_campaign);
				break;
			default:
				//for FgSteal Message1
				$smsContentData['sms_type']    = 'fg_steal';
				$createSmsObj                  = new CreateSmsTemplates;
				$smsContentData['sms_content'] = $createSmsObj->createSmsFgSteal($utm_campaign);
				break;
		}

		return $smsContentData;
	}

}
