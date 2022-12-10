<?php

namespace Dashboard\Console\Commands\Users;

use Dashboard\Classes\Helpers\CreateSmsTemplates;
use Dashboard\Classes\Helpers\Utility;
use Dashboard\Data\Models\EmailSmsCronLog;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Data\Models\SmsUpdatesLog;
use Illuminate\Console\Command;

class TwoTimeCustomersSms extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'twoTimeCustomersSms:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Two Time Customers SMS (Exclude 30 days)';

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

		$last30Days   = SalesFlatOrder::last30DaysCustomers();
		$allCustomers = SalesFlatOrder::twoTimeCustomers();
		$customers    = array_diff($allCustomers, $last30Days);

		if (!empty($customers)) {

			$customers = Utility::uniqueMultidimArray($customers, 'mobile');
			echo 'Total Customers::'.count($customers)."\n";

			$smsContentData = $this->createSms();
			//dd($emailContentData);
			// get all the user who have unsubscribed
			$unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();

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
					}

					//$customer['mobile'] = '9555506619';

					$smsData['name'] = $name = ucfirst(strtolower(trim(explode(" ", $customer['name'])[0])));

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
	 *  create topseller mail
	 */

	public function createSms() {
		$site                      = config('app.site_url');
		$smsContentData            = array();
		$smsContentData['tag_sms'] = $smsContentData['utm_campaign'] = $utm_campaign = 'All-Time-2T';
		$lastSendData              = EmailSmsCronLog::lastSmsSend($utm_campaign);

		$lastSms = 'fg_steal';
		if (!empty($lastSendData)) {
			foreach ($lastSendData as $lastSend) {
				$lastSms = $lastSend['message_type'];
				break;
			}
		}

		switch ($lastSms) {
			case "fg_steal":
				//for seasonalCollection Message1
				$smsContentData['sms_type']    = 'seasonal_collection';
				$createSmsObj                  = new CreateSmsTemplates;
				$smsContentData['sms_content'] = $createSmsObj->createSmsSeasonalCollection($utm_campaign);
				break;
			case "seasonal_collection":
				//for under1500 Message2
				$smsContentData['sms_type']   = 'under_1500';
				$smsContentData['smsContent'] = 'Under 1500 sms content';
				break;
			case "under_1500":
				//for FgSteal Message3
				$smsContentData['sms_type']   = 'fg_steal';
				$fgStealObj                   = new PromoteFgSteal;
				$smsContentData['smsContent'] = $fgStealObj->createSms();
				break;
			default:
				//for seasonalCollection Message1
				$smsContentData['sms_type']   = 'seasonal_collection';
				$smsContentData['smsContent'] = 'Seasonal sms content';
		}

		return $smsContentData;
	}
}
