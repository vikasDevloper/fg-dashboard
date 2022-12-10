<?php
/**
 * User: Komal Bhagat
 */
namespace Dashboard\Console\Commands\Users;

use Dashboard\Data\Models\EmailSmsCronLog;
use Dashboard\Data\Models\EmailUpdates;
use Dashboard\Data\Models\EmailUpdatesLog;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\SalesFlatOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;

class ThreeTimeCustomersEmail extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'threeTimeCustomersEmail:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Two Three Customers Email (Exclude 30 days)';

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
		$allCustomers = SalesFlatOrder::threeTimeCustomers();
		$customers    = array_diff($allCustomers, $last30Days);

		if (!empty($customers)) {

			echo 'Total Customers::'.count($customers)."\n";

			$emailContentData = $this->createMail();
			//dd($emailContentData);
			// get all the user who have unsubscribed
			$unsubscribedEmailUser = NewsletterSubscriber::getEmailUnsubscribers();

			if (!empty($emailContentData)) {

				$customerCount = 0;

				foreach ($customers as $customer) {

					if (empty($customer['email'])) {
						continue;
					} else if (filter_var($customer['email'], FILTER_VALIDATE_EMAIL) == false) {
						continue;
					} else if (in_array($customer['email'], $unsubscribedEmailUser)) {
						continue;
					}

					$name = ucfirst(strtolower(trim(explode(" ", $customer['name'])[0])));
					//$customer['email']          = 'sandeep@faridagupta.com';
					$emailData                  = array();
					$emailData['customer_id']   = $customer['customer_id'];
					$emailData['email']         = $customer['email'];
					$emailData['name']          = $name;
					$emailData['email_type']    = $emailContentData['tag_email'];
					$emailData['subject']       = $emailContentData['subject'];
					$emailData['email_content'] = str_replace(array("[NAME]", "[SUBJECT]"), array($name, $emailContentData['subject']), $emailContentData['emailContent']);

					//dd($emailData);
					$insertedEmail = EmailUpdates::insert($emailData);

					if ($insertedEmail) {
						$customerCount++;
						unset($emailData['email_content']);
						unset($emailData['name']);
						$emailData['user_type'] = 'Customer';
						$insertedEmail          = EmailUpdatesLog::insert($emailData);
					}

					//break;

				}

			}

			$lastEmailLog                      = array();
			$lastEmailLog['message_type']      = $emailContentData['email_type'];
			$lastEmailLog['subject']           = $emailContentData['subject'];
			$lastEmailLog['users_count']       = $customerCount;
			$lastEmailLog['communiction_type'] = 'email';
			$lastEmailLog['utm_campaign']      = $emailContentData['utm_campaign'];
			EmailSmsCronLog::insert($lastEmailLog);

			echo 'Customers to send email::'.$customerCount."\n";
		}
	}

	/*
	 *  create topseller mail
	 */

	public function createMail() {
		$site                          = config('app.site_url');
		$emailContentData              = array();
		$emailContentData['tag_email'] = $emailContentData['utm_campaign'] = $utm_campaign = 'All-Time-3T';
		$lastSendData                  = EmailSmsCronLog::lastEmailSend($utm_campaign);

		$lastEmail = 'top_seller';
		if (!empty($lastSendData)) {
			foreach ($lastSendData as $lastSend) {
				$lastEmail = $lastSend['message_type'];
				break;
			}
		}

		switch ($lastEmail) {
			case "top_seller":
				//for seasonalCollection Message1
				$emailContentData['email_type'] = 'seasonal_collection';
				$emailContentData['subject']    = 'Discover our New Collection!';
				$mcPreviewText                  = "See what's new in Pardeiza Collection";
				$template_name                  = 'emails.promotions.seasonalcollection';
				$homePageUrl                    = config('app.site_url')."?utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;
				$template_data                  = array(
					'siteUrl'       => $site,
					'mcPreviewText' => $mcPreviewText,
					'homePageUrl'   => $homePageUrl,
					'utm_campaign'  => $utm_campaign,
				);
				$emailContentData['emailContent'] = $this->createTemplateView($template_name, $template_data);
				break;
			case "seasonal_collection":
				//for under_1500 Message2
				$emailContentData['email_type'] = 'under_1500';
				$emailContentData['subject']    = 'FG Top Sellers';
				$mcPreviewText                  = '';
				$template_name                  = 'emails.transactional.under_1500';
				$homePageUrl                    = config('app.site_url')."?utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;
				$template_data                  = array(
					'siteUrl'       => $site,
					'mcPreviewText' => $mcPreviewText,
					'homePageUrl'   => $homePageUrl,
				);
				$emailContentData['emailContent'] = $this->createTemplateView($template_name, $template_data);
				break;
			case "under_1500":
				//for FgSteal Message3
				$emailContentData['email_type']   = 'top_seller';
				$emailContentData['subject']      = 'FG Top Sellers';
				$topSellingObj                    = new TopSellingPromotions;
				$emailContentData['emailContent'] = $topSellingObj->createMail();
				break;
			default:
				//for seasonalCollection Message1
				$emailContentData['email_type'] = 'seasonal_collection';
				$emailContentData['subject']    = 'Discover our New Collection!';
				$mcPreviewText                  = "See what's new in Pardeiza Collection";
				$template_name                  = 'emails.promotions.seasonalcollection';
				$homePageUrl                    = config('app.site_url')."?utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;
				$template_data                  = array(
					'siteUrl'       => $site,
					'mcPreviewText' => $mcPreviewText,
					'homePageUrl'   => $homePageUrl,
					'utm_campaign'  => $utm_campaign,
				);
				$emailContentData['emailContent'] = $this->createTemplateView($template_name, $template_data);
		}
		return $emailContentData;
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
