<?php
/**
 * User: Komal Bhagat
 * Date: 17/03/18
 * Time: 12:10 PM
 */
namespace Dashboard\Classes\Helpers;

class CreateSmsTemplates {

	//put your code here
	private $site;
	private $signature;

	public function __construct() {
		$this->site          = config('app.site_url');
		$this->signature     = config('sms.signature.without_no');
		$this->stealUrl      = config('app.fg_steal_url');
		$this->newArrivalUrl = config('app.new_arrivals_url');
		$this->topSellersUrl = config('app.top_sellers_url');
		$this->number        = config('sms.supportNo.support_no');

	}

	/*
	 *  truncate Sms Update table
	 */

	public function clearSmsUpdates() {
		SmsUpdates::truncate();
	}

	/*
	 *  create Fgsteal sms
	 */

	public function createSmsFgSteal($utm_campaign) {
		$shortUrl = $this->stealUrl."?utm_source=sms&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;
		$url      = Utility::get_bitly_short_url($shortUrl, 'vaibhav15', 'R_eb5ff082a32747059c8bfb39223f0615');

		$smsBody = "Hi [NAME],\n\nUp to 50% off on FG Products. Seen our Steals section yet? New products added every week.\n\nShop – ".$url."\n\n".$this->signature;

		return $smsBody;
	}

	/*
	 *  create Seasonal collection sms
	 */

	public function createSmsSeasonalCollection($utm_campaign) {
		$shortUrl = $this->newArrivalUrl."?utm_source=sms&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;
		$url      = Utility::get_bitly_short_url($shortUrl, 'vaibhav15', 'R_eb5ff082a32747059c8bfb39223f0615');

		$smsBody = "Hi [NAME],\n\nOur new collection is now live! Hurry, before stocks run out.\n\nShop – ".$url."\n\n".$this->signature;

		return $smsBody;
	}

	/*
	 *  create Top Sellers sms
	 */

	public function createSmsTopSellers($utm_campaign) {
		$shortUrl = $this->topSellersUrl."?utm_source=sms&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;
		$url      = Utility::get_bitly_short_url($shortUrl, 'vaibhav15', 'R_eb5ff082a32747059c8bfb39223f0615');

		$smsBody = "Hi [NAME],\n\nTake a look at the top sellers from FG this week. Have you got yours yet?\n\nShop – ".$url."\n\n".$this->signature;

		return $smsBody;
	}

	/*
	 * create product notify me SMS
	 */

	public function createSmsProductNotifyMe() {

		//$smsBody = "Hi [NAME],\n\nGuess what?\n[PRODUCT_NAME] in [PRODUCT_SIZE] is back in stock!\nBuy it before it's sold out again - [PRODUCT_LINK]\n\n".$this->signature;
        //changed in lockdown phase 
        $smsBody = "Farida Gupta | Stock Update\n\nHi [NAME],\n\nGood News!\n\nOur [PRODUCT_NAME] in size [PRODUCT_SIZE] is back in stock. Limited quantity available.\n\nShop now: [PRODUCT_LINK]\n\nLove,\nTeam FG\n".$this->number;
		return $smsBody;
	}

	/*
	 * create product notify me SMS without size
	 */

	public function createSmsProductNotifyMeWithoutSize() {

		//$smsBody = "Hi [NAME],\n\nGuess what?\n[PRODUCT_NAME] is back in stock!\nBuy it before it's sold out again - [PRODUCT_LINK]\n\n".$this->signature;

        $smsBody = "Farida Gupta | Stock Update\n\nHi [NAME],\n\nGood News!\n\nOur [PRODUCT_NAME] is back in stock. Limited quantity available.\n\nShop now: [PRODUCT_LINK]\n\nLove,\nTeam FG\n".$this->number;


		return $smsBody;
	}

}