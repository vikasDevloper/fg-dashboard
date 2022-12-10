<?php
/**
 * User: Komal Bhagat
 */
namespace Dashboard\Console\Commands\Users;

use Dashboard\Data\Models\EmailSmsCronLog;

use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\SalesFlatOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;

class ThreeTimeCustomers30daysSms extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'threeTimeCustomers30daysSms:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Three Time Customers Sms (last 30 days)';

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

		$customers = SalesFlatOrder::threeTimeCustomers30daysSms();

		if (!empty($customers)) {

			echo 'Total Customers::'.count($customers)."\n";

			$smsContentData = $this->createSms();
			//dd($emailContentData);
			// get all the user who have unsubscribed
			$unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();

			if (!empty($smsContentData)) {

				$customerCount = 0;

				foreach ($customers as $customer) {

					if (empty($customer['mobile'])) {
						continue;
					} else if (strlen($customer['mobile']) != 10) {
						continue;
					} else if (in_array($customer['mobile'], $unsubscribedMobileUser)) {
						continue;
					}

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
		$smsContentData['tag_sms'] = $smsContentData['utm_campaign'] = $utm_campaign = 'U30-D-3T';
		$lastSendData              = EmailSmsCronLog::lastSmsSend($utm_campaign);

		foreach ($lastSendData as $lastSend) {
			$lastSms = $lastSend['message_type'];

			switch ($lastSms) {
				case "fg_steal":
					//for newLaunch Message1
					$smsContentData['sms_type']   = 'new_launch';
					$smsContentData['smsContent'] = 'New launch sms';
					break;
				case "new_launch":
					//for topSeller Message2
					$smsContentData['sms_type']   = 'top_seller';
					$topSellingObj                = new TopSellingPromotions;
					$smsContentData['smsContent'] = $topSellingObj->createSMs();
					break;
				case "top_seller":
					//for FgSteal Message3
					$smsContentData['sms_type']   = 'fg_steal';
					$fgStealObj                   = new PromoteFgSteal;
					$smsContentData['smsContent'] = $fgStealObj->createSMs();
					break;
				default:
					//for newLaunch Message1
					$smsContentData['sms_type']   = 'new_launch';
					$smsContentData['smsContent'] = 'New launch sms';
			}
			break;
		}

		return $smsContentData;
	}

	/*
	 *	create template from blade file
	 */

	public function createTemplateView($template_name, $template_data) {
		$emailContent = '';
		try {
			$emailContent = View::make($template_name)->with($template_data);
		} catch (\Exception $e) {
			return false;
		}

		return $emailContent;
	}
}
