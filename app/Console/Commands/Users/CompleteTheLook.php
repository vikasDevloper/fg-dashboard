<?php
/**
 * User: Komal Bhagat
 */
namespace Dashboard\Console\Commands\Users;

use Dashboard\Classes\Helpers\Utility;

use Dashboard\Data\Models\CatalogProductEntityDecimal;
use Dashboard\Data\Models\CatalogProductEntityInt;
use Dashboard\Data\Models\CatalogProductEntityVarchar;
use Dashboard\Data\Models\CatalogProductLink;
use Dashboard\Data\Models\CustomerProductNotify;
use Dashboard\Data\Models\EmailUpdates;
use Dashboard\Data\Models\EmailUpdatesLog;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SalesFlatOrderItem;
use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Data\Models\SmsUpdatesLog;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class CompleteTheLook extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'completeTheLookPromotions:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create complete the look Mail and SMS';

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
		//

		set_time_limit(0);

		$sendEmail = 1;
		$sendSms   = 1;

		$tag          = 'FG_Complete_the_Look';
		$utm_campaign = 'completethelook';
		$site         = config('app.site_url');
		$number       = config('app.support_no');

		$homePageUrl    = config('app.site_url')."?utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;
		$newArrivalsUrl = config('app.new_arrivals_url')."?utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;
		$topSellersUrl  = config('app.top_sellers_url')."?utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;

		$signature     = config('sms.signature.without_no');
		$emailTemplate = 'emails.transactional.complete-the-look';
		$subject       = 'Complete your FG Look';
		$mcPreviewText = '';

		$unsubscribedMobileUser = array();
		$notToSendSmsUsers      = array();
		$unsubscribedEmailUser  = array();
		$notToSendEmailUsers    = array();
		$smsBody                = '';

		Log::info('Complete the Look:: Started');

		if ($sendSms == 1) {

			// get all the user who have unsubscribed
			$unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();
			// get all the user who have register in 'notify me'
			$notifyMeUserMobile = CustomerProductNotify::notifyMeOpenStatusByMobile();
			// get users who got SMS today
			$notToSendSmsUsers = SmsUpdatesLog::getUsersGotSmsToday();
			$smsBody           = "Hi [NAME],\n\nHope you loved your FGs as much as we loved making them for you. Pair them with the matching bottoms and complete the look!\n\n[MATCHINGS]\n\n".$signature;
		}

		if ($sendEmail == 1) {

			// get all the user who have unsubscribed
			$unsubscribedEmailUser = NewsletterSubscriber::getEmailUnsubscribers();
			// get all the user who have register in 'notify me'
			$notifyMeUserEmail = CustomerProductNotify::notifyMeOpenStatusByMobile();
			// get users who got SMS today
			$notToSendEmailUsers = EmailUpdatesLog::getUsersGotEmailToday();
		}

		// get all the users who
		$allCustomers = SalesFlatOrder::getUserGotDeliveredYesterday();

		if (!empty($allCustomers)) {
			foreach ($allCustomers as $customer) {
				$smsThisCustomer   = 1;
				$emailThisCustomer = 1;
				$smsData           = array();
				$emailData         = array();
				$smsData['name']   = '';
				$emailData['name'] = '';

				$mobileregex = "/^[6-9][0-9]{9}$/"; 

				if ((preg_match($mobileregex, $customer['mobile']) == 0) || (strlen($customer['mobile']) != 10)){
				   $smsThisCustomer = 0;
				}

				if ($sendSms == 1) {
					if (in_array($customer['mobile'], $unsubscribedMobileUser) || in_array($customer['mobile'], $notToSendSmsUsers) || in_array($customer['mobile'], $notifyMeUserMobile)) {
						$smsThisCustomer = 0;
					}
				}
				if ($sendEmail == 1) {
					if (in_array($customer['email'], $unsubscribedEmailUser) || in_array($customer['email'], $notToSendEmailUsers) || in_array($customer['mobile'], $notifyMeUserEmail)) {
						$emailThisCustomer = 0;
					}
				}

				if ($smsThisCustomer == 0 && $emailThisCustomer == 0) {
					continue;
				}

				$kurtaProducts = SalesFlatOrderItem::getKurtaPurchased($customer['orderId']);

				if (!empty($kurtaProducts)) {

					if (!empty($customer['name'])) {
						$smsData['name'] = $emailData['name'] = ucfirst(strtolower(trim($customer['name'])));
					}

					$matchings     = array();
					$boughtProduct = array();
					$bought        = array();
					$shopthelook   = array();

					foreach ($kurtaProducts as $kurtas) {

						$enabledKurtaProduct = CatalogProductEntityInt::checkCrosssellEnabled($kurtas['product_id']);

						if (empty($enabledKurtaProduct)) {
							continue;
						}

						$crosssellProducts = CatalogProductLink::getCrosssellProducts($kurtas['product_id']);
						$boughtProductName = CatalogProductEntityVarchar::getProductNameByProductId($kurtas['product_id']);
						$alreadyPurchased  = 0;
						$disabledProduct   = 0;

						if (!empty($crosssellProducts)) {

							$shopthelookForKurta = array();

							foreach ($crosssellProducts as $crosssell) {

								$shopthelookProduct = array();

								$data = SalesFlatOrderItem::checkCrosssellPurchased($customer['customerId'], $crosssell['crosssellProductId']);

								if (!empty($data)) {
									$alreadyPurchased = 1;
									break;
								}

								$enabledProduct = CatalogProductEntityInt::checkCrosssellEnabled($crosssell['crosssellProductId']);
								if (empty($enabledProduct)) {
									$disabledProduct = 1;
									continue;
								}

								if ($sendEmail == 1 && $emailThisCustomer == 1) {
									$shopthelookProduct['productUrl'] = $site.'/'.CatalogProductEntityVarchar::getProductUrlByProductId($crosssell['crosssellProductId'])."?utm_source=email&utm_medium=cps&utm_campaign=".$utm_campaign."&utm_location=-1";

									if (strlen(CatalogProductEntityVarchar::getProductNameByProductId($crosssell['crosssellProductId'])) < 23) {
										$prdName = CatalogProductEntityVarchar::getProductNameByProductId($crosssell['crosssellProductId']);
									} else {
										$prdName = substr(CatalogProductEntityVarchar::getProductNameByProductId($crosssell['crosssellProductId']), 0, 20).'...';
									}

									$shopthelookProduct['productName'] = $prdName;

									$shopthelookProduct['productImageUrl'] = $site.'/media/catalog/product'.CatalogProductEntityVarchar::getProductImageUrlByProductId($crosssell['crosssellProductId']);

									$shopthelookProduct['productPrice'] = (int) CatalogProductEntityDecimal::getProductPriceByProductId($crosssell['crosssellProductId']);
								}

								$shopthelookForKurta[] = $shopthelookProduct;

							}

							if ($alreadyPurchased == 1) {
								$shopthelookProduct = array();
								$alreadyPurchased   = 0;
							}

							if ($alreadyPurchased == 0 && $disabledProduct == 0) {
								$shopthelook[$boughtProductName] = $shopthelookForKurta;
								$shopthelookProduct              = array();
								$alreadyPurchased                = 0;
							}
						}

						if ($alreadyPurchased == 0 && $disabledProduct == 0) {
							if ($sendSms == 1 && $smsThisCustomer == 1) {
								$productSmsUrl = $site.'/'.CatalogProductEntityVarchar::getProductUrlByProductId($kurtas['product_id'])."?utm_source=sms&utm_medium=cps&utm_campaign=".$utm_campaign."&utm_location=-1#shopthelook";
								/* get the short url */
								$short_url = Utility::get_bitly_short_url($productSmsUrl, 'vaibhav15', 'R_eb5ff082a32747059c8bfb39223f0615');
                               	if ($short_url == 'RATE_LIMIT_EXCEEDED' || $short_url == '' || $short_url == 'UNKNOWN_ERROR') {

									Log::error('Non-Attendees URL::'.$short_url);
									continue;
								}

								$matchings[] = "For ".CatalogProductEntityVarchar::getProductNameByProductId($kurtas['product_id'])."- ".$short_url;
							}

							if ($sendEmail == 1 && $emailThisCustomer == 1) {
								$boughtProduct['productUrl'] = $site.'/'.CatalogProductEntityVarchar::getProductUrlByProductId($kurtas['product_id'])."?utm_source=email&utm_medium=cps&utm_campaign=".$utm_campaign."&utm_location=-1";

								$boughtProduct['productImageUrl'] = $site.'/media/catalog/product'.CatalogProductEntityVarchar::getProductImageUrlByProductId($kurtas['product_id']);

								$boughtProduct['productName'] = $boughtProductName;
								if (!empty($shopthelook[$boughtProduct['productName']])) {
									array_push($bought, $boughtProduct);
								}
								$boughtProduct = array();
							}
						}
					}

					$smsData['customer_id'] = $emailData['customer_id'] = $customer['customerId'];

					if ($sendSms == 1 && $smsThisCustomer == 1) {
						if (!empty($bought) && !empty($shopthelook)) {
							$smsText                = str_replace(array("[NAME]", "[MATCHINGS]"), array($smsData['name'], implode("\n", $matchings)), $smsBody);
							$smsData['mobile']      = $customer['mobile'];
							$smsData['sms_type']    = $tag;
							$smsData['sms_content'] = $smsText;

							if (!empty($smsData['sms_content']) || !empty($matchings)) {
								$insertedSms = SmsUpdates::insert($smsData);

								if ($insertedSms) {
									$matchings = array();
									unset($smsData['sms_content']);
									unset($smsData['name']);
									$smsData['user_type'] = 'Customer';
									$insertedSms          = SmsUpdatesLog::insert($smsData);
								}
							}
						}
					}

					$boughtProductCount = count($bought);

					if ($sendEmail == 1 && $emailThisCustomer == 1 && !empty($customer['email'])) {
						if (!empty($bought) && !empty($shopthelook)) {
							$emailData['email']      = $customer['email'];
							$emailData['email_type'] = $tag;
							$emailData['subject']    = $subject;

							try {
								$emailData['email_content'] = View::make($emailTemplate)->with([
										'subject'            => $emailData['subject'],
										'mcPreviewText'      => $mcPreviewText,
										'firstname'          => $emailData['name'],
										'boughtProduct'      => $bought,
										'boughtProductCount' => $boughtProductCount,
										'shopthelook'        => $shopthelook,
										'homePageUrl'        => $homePageUrl,
										'newArrivalsUrl'     => $newArrivalsUrl,
										'topSellersUrl'      => $topSellersUrl
									]);
							} catch (\Exception $e) {
								continue;
							}

							// echo $emailData['email_content'];
							// exit;

							$insertedEmail = EmailUpdates::insert($emailData);

							if ($insertedEmail) {
								$matchings = array();
								unset($emailData['email_content']);
								unset($emailData['name']);
								$emailData['user_type'] = 'Customer';
								$insertedEmail          = EmailUpdatesLog::insert($emailData);
							}
						}
					}
				}
			}

			Log::info('Complete the Look:: SMS/Email sent');
		}
	}
}