<?php
/**
 * User: Komal Bhagat
 */
namespace Dashboard\Console\Commands\Users;

use Dashboard\Classes\Helpers\CreateMailTemplates;
use Dashboard\Classes\Helpers\CreateSmsTemplates;
use Dashboard\Data\Models\EmailUpdates;
use Dashboard\Data\Models\EmailUpdatesLog;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SalesFlatOrderAddress;

use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Data\Models\SmsUpdatesLog;
use Illuminate\Console\Command;

class TopSellingPromotions extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'topSellingPromotions:create';

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

		$sendEmail = 1;
		$sendSms   = 1;

		$tag          = 'Top_Selling';
		$signature    = config('sms.signature.without_no');
		$site         = config('app.site_url');
		$number       = config('app.support_no');
		$subject      = 'FG Top Sellers';
		$utm_campaign = 'top_sellers';

		$unsubscribedMobileUser = array();
		$notToSendSmsUsers      = array();
		$unsubscribedEmailUser  = array();
		$notToSendEmailUsers    = array();
		$smsBody                = '';
		$emailContent           = '';

		if ($sendSms == 1) {
			// get all the user who have unsubscribed
			$unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();
			// get users who got SMS today
			$notToSendSmsUsers = SmsUpdatesLog::getUsersGotSmsToday();

			$createSmsObj = new CreateSmsTemplates;
			$smsBody      = $createSmsObj->createSmsTopSellers($utm_campaign);
		}

		if ($sendEmail == 1) {
			// get all the user who have unsubscribed
			$unsubscribedEmailUser = NewsletterSubscriber::getEmailUnsubscribers();
			// get users who got SMS today
			$notToSendEmailUsers = EmailUpdatesLog::getUsersGotEmailToday();
			//email content top sellers
			$createMailObj = new CreateMailTemplates;
			$emailContent  = $createMailObj->createMailTopSeller($utm_campaign);
			if (empty($emailContent)) {
				$sendEmail = 0;
			}

		}

		$customersPurchased = SalesFlatOrder::getPurchansedUsers();
		$customers          = SalesFlatOrderAddress::getAllUsers();
		$subscriber         = NewsletterSubscriber::getNewsletterSubscribers();

		$allCustomers = array_merge($customers, $subscriber);

		$i = 0;

		$customers[0]['mobile']     = '9873621245';
		$customers[0]['name']       = 'Sanjay';
		$customers[0]['customerId'] = '123';
		$customers[0]['email']      = 'sanjay@faridagupta.com';
		$customers[1]['mobile']     = '813010643';
		$customers[1]['name']       = 'Chandan';
		$customers[1]['customerId'] = '124';
		$customers[1]['email']      = 'chandan@faridagupta.com';
		$customers[2]['mobile']     = '9818137346';
		$customers[2]['name']       = 'Sahil';
		$customers[2]['customerId'] = '125';
		$customers[2]['email']      = 'sahil@faridagupta.com';
		$customers[3]['mobile']     = '9716834689';
		$customers[3]['name']       = 'Vaibhav';
		$customers[3]['customerId'] = '126';
		$customers[3]['email']      = 'vaibhav@faridagupta.com';

		if (!empty($allCustomers)) {
			foreach ($allCustomers as $customer) {

				$smsThisCustomer   = 1;
				$emailThisCustomer = 1;
				$smsDetails        = array();
				$emailData         = array();
				$smsData['name']   = '';
				$emailData['name'] = '';

				$smsDetails = array();

				// echo " ".$mobile        = $customer['mobile'];
				// echo " ".$email         = $customer['email'];

				if ($sendSms == 1) {
					if (isset($customer['mobile']) && strlen($customer['mobile']) != 10) {
						$smsThisCustomer = 0;
					}

					if (!isset($customer['mobile']) || in_array($customer['mobile'], $unsubscribedMobileUser) || in_array($customer['mobile'], $notToSendSmsUsers) || in_array($customer['mobile'], $customersPurchased)) {
						$smsThisCustomer = 0;
					}
				}

				if ($sendEmail == 1) {
					if (empty($customer['email']) || $customer['email'] == null) {
						$emailThisCustomer = 0;
					} else if (in_array($customer['email'], $unsubscribedEmailUser) || in_array($customer['email'], $notToSendEmailUsers) || in_array($customer['email'], $customersPurchased)) {
						$emailThisCustomer = 0;
					}
				}

				if (isset($customer['name'])) {
					$name = ucfirst(strtolower(trim($customer['name'])));

					if (strtolower($name) == 'unknown' || strtolower($name) == 'test' || empty($customer['name']) || $customer['name'] == NULL) {
						$name = '';
					}
				} else {
					$name = '';
				}

				// echo $smsThisCustomer;
				// echo $sendSms;
				if ($sendSms == 1 && $smsThisCustomer == 1) {

					$smsText = str_replace(array("[NAME]"), array($name), $smsBody);

					$smsDetails['mobile']      = $customer['mobile'];
					$smsDetails['sms_type']    = $tag;
					$smsDetails['customer_id'] = $customer['customerId'];
					$smsDetails['name']        = $name;
					$smsDetails['sms_content'] = $smsText;

					$inserted = SmsUpdates::insert($smsDetails);

					if ($inserted) {
						unset($smsDetails['sms_content']);
						unset($smsDetails['name']);
						$smsDetails['user_type'] = 'Customer';
						$inserted                = SmsUpdatesLog::insert($smsDetails);
					}
				}

				if ($sendEmail == 1 && $emailThisCustomer == 1 && !empty($customer['email'])) {
					// $name                       = 'Vaibhav';
					// $customer['email']          = 'vaibhav@faridagupta.com';
					$emailData['customer_id']   = $customer['customerId'];
					$emailData['email']         = $customer['email'];
					$emailData['name']          = $name;
					$emailData['email_type']    = $tag;
					$emailData['subject']       = $subject;
					$emailData['email_content'] = str_replace(array("[NAME]", "[SUBJECT]"), array($name, $subject), $emailContent);

					//dd($emailData);

					$insertedEmail = EmailUpdates::insert($emailData);

					if ($insertedEmail) {
						unset($emailData['email_content']);
						unset($emailData['name']);
						$emailData['user_type'] = 'Customer';
						$insertedEmail          = EmailUpdatesLog::insert($emailData);
					}
					//exit;
				}

				$i++;
				// if ($i == 3) {
				// 	exit;
				// }
			}
		}
	}

}
