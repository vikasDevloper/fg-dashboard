<?php
/**
 * User: Komal Bhagat
 */
namespace Dashboard\Console\Commands\Users;

use Dashboard\Classes\Helpers\CreateSmsTemplates;
use Dashboard\Classes\Helpers\Utility;
use Dashboard\Data\Models\CatalogProductEntityInt;
use Dashboard\Data\Models\CatalogProductEntityVarchar;
use Dashboard\Data\Models\CatalogProductSuperLink;
use Dashboard\Data\Models\CustomerProductNotify;
use Dashboard\Data\Models\EavAttributeOptionValue;
use Dashboard\Data\Models\NewsletterSubscriber;

use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Data\Models\SmsUpdatesLog;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProductAvailableNotify extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'productAvailableNotify:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Notify to customer if product in stock';

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

		Log::info('Product Available Notification:: Started');

		$customersToSend = array();
		$customersToSend = CustomerProductNotify::customersToSendNotify();

		// get all the user who have unsubscribed
		$unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();
		if (!empty($customersToSend)) {

			foreach ($customersToSend as $customer) {
				$product_id = CatalogProductSuperLink::getConfigurableProductId($customer['product_id']);
				if (empty($product_id)) {
					$product_id = $customer['product_id'];
				}

				$enabledMaster = CatalogProductEntityInt::checkCrosssellEnabled($product_id);

				$enabledChild = CatalogProductEntityInt::checkCrosssellEnabled($customer['product_id']);
                


				if (empty($enabledMaster) || empty($enabledChild) ) {
					
					continue;
					// for test sms comment continue and enable mobile no and end thid for at end( run only once )
					// $customer['mobile']  = '9045682529';
					// $customer['name']  = 'Rajan';
				}
				$mobileregex = "/^[6-9][0-9]{9}$/";
				if (preg_match($mobileregex, $customer['mobile']) == 0) {

					CustomerProductNotify::where('product_id', $customer['product_id'])
						->where('customer_mobile', $customer['mobile'])
						->update(['status' => -1]);
					continue;
				} elseif (strlen($customer['mobile']) != 10) {

					CustomerProductNotify::where('product_id', $customer['product_id'])
						->where('customer_mobile', $customer['mobile'])
						->update(['status' => -1]);
					continue;
				} elseif (in_array($customer['mobile'], $unsubscribedMobileUser)) {

					CustomerProductNotify::where('product_id', $customer['product_id'])
						->where('customer_mobile', $customer['mobile'])
						->update(['status' => -1]);
					continue;
				}

				$name = ucfirst(strtolower(trim(explode(" ", $customer['name'])[0])));
				if (strtolower($name) == 'unknown' || strtolower($name) == 'test') {
					$name = '';
				}

				if (!empty($customer['product_id'])) {

					$smsText = str_replace('[NAME]', $name, $this->createSms($customer['product_id']));

					if (!empty($smsText)) {
						$smsData                = array();
						$smsData['mobile']      = $customer['mobile'];
						$smsData['sms_type']    = 'notify_me_sms';
						$smsData['sms_content'] = $smsText;

						$insertedSms = SmsUpdates::insert($smsData);

						if ($insertedSms) {

							CustomerProductNotify::where('product_id', $customer['product_id'])
								->where('customer_mobile', $customer['mobile'])
								->update(['status' => 2]);

							unset($smsData['sms_content']);
							unset($smsData['name']);
							$smsData['user_type'] = 'Customer';
							$insertedSms          = SmsUpdatesLog::insert($smsData);

						}
					}

				}

			}

			Log::info('Product Available Notification:: SMS Sent');
		}
	}

	public function createSms($simple_product_id) {
		$site         = config('app.site_url');
		$utm_campaign = 'notify_me_sms';
		$smsText      = '';
		//echo $simple_product_id;

		$product_id = CatalogProductSuperLink::getConfigurableProductId($simple_product_id);

		if (!empty($product_id)) {

			//dd($product_id);

			$link         = $site.'/'.CatalogProductEntityVarchar::getProductUrlByProductId($product_id);
			$product_name = CatalogProductEntityVarchar::getProductNameByProductId($product_id);
			$sizeId       = CatalogProductEntityInt::getSizeIdbyProductId($simple_product_id);
			$product_size = EavAttributeOptionValue::getSizeBySizeId($sizeId);

			$url          = $link."?utm_source=sms&utm_medium=cps&utm_campaign=".$utm_campaign;
			$product_link = Utility::get_bitly_short_url($url, 'vaibhav15', 'R_eb5ff082a32747059c8bfb39223f0615');
            
            if ($product_link == 'RATE_LIMIT_EXCEEDED' || $product_link == '' || $product_link == 'UNKNOWN_ERROR') {

				Log::error('Non-Attendees URL::'.$product_link);
				exit;
			}

			$createSmsObj = new CreateSmsTemplates;
			$notifySMS    = $createSmsObj->createSmsProductNotifyMe();
			$smsText      = str_replace(array('[PRODUCT_NAME]', '[PRODUCT_SIZE]', '[PRODUCT_LINK]'), array($product_name, $product_size, $product_link), $notifySMS);

		} else {

			$product_id = $simple_product_id;

			$link = $site.'/'.CatalogProductEntityVarchar::getProductUrlByProductId($product_id);

			$product_name = CatalogProductEntityVarchar::getProductNameByProductId($product_id);

			$url          = $link."?utm_source=sms&utm_medium=cps&utm_campaign=".$utm_campaign;
			$product_link = Utility::get_bitly_short_url($url, 'vaibhav15', 'R_eb5ff082a32747059c8bfb39223f0615');
            
            if ($product_link == 'RATE_LIMIT_EXCEEDED' || $product_link == '' || $product_link == 'UNKNOWN_ERROR') {

				Log::error('Non-Attendees URL::'.$product_link);
				exit;
			}
            
			$createSmsObj = new CreateSmsTemplates;
			$notifySMS    = $createSmsObj->createSmsProductNotifyMeWithoutSize();

			$smsText = str_replace(array('[PRODUCT_NAME]', '[PRODUCT_LINK]'), array($product_name, $product_link), $notifySMS);
		}

		return $smsText;
	}

}
