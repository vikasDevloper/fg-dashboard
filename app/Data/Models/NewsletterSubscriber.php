<?php
/**
 * Created By: Komal Bhagat
 */
namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'newsletter_subscriber';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;

	/**
	 * Get all the orders group by status
	 *
	 */

	static function getSubscribers($date) {
		$orders = NewsletterSubscriber::whereRaw("date(created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count(subscriber_id) AS Subscribers, date(created_at) AS DateCreated")
			->groupBy("dateCreated")
			->orderBy("dateCreated", "desc")
			->get();
		$data             = array();
		$totalSubscribers = 0;

		if (!empty($orders)) {
			foreach ($orders as $value) {
				$data[$value['DateCreated']] = $value['Subscribers'];
				$totalSubscribers += $value['Subscribers'];
			}
			$data['totalSubscribers'] = $totalSubscribers;
		}

		return $data;
	}

	static function getNewsletterSubscribers() {
		$customers = NewsletterSubscriber::selectRaw("customer_id AS customerId, subscriber_email AS email, subscriber_name AS name, mobile")
			->groupBy("email")
			->orderBy("subscriber_id", "desc")
			->get();

		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer->toArray();
			}
		}
		return $data;
	}

	static function getTotalSubscribers() {
		return $customers = NewsletterSubscriber::distinct()->count('subscriber_email');
	}

	static function getTotalSubscribersMobile() {
		return $customers = NewsletterSubscriber::distinct()->count('mobile');
	}

	static function getMobileUnsubscribers() {

		$unsubs = NewsletterSubscriber::where('mobile_sub_status', '2')->select('mobile')->get();
		$data   = array();
		if (!empty($unsubs)) {
			foreach ($unsubs as $unsub) {
				$data[] = $unsub['mobile'];
			}
		}

		return $data;
	}

	static function getEmailUnsubscribers() {

		$unsubs = NewsletterSubscriber::where('subscriber_status', '3')
			->select('subscriber_email')	->get();
		$data = array();
		if (!empty($unsubs)) {
			foreach ($unsubs as $unsub) {
				$data[] = $unsub['subscriber_email'];
			}
		}

		return $data;
	}

	static function getNewsletterCitySubscribers($cityid) {

		// $customers = NewsletterSubscriber::whereRaw("FIND_IN_SET(subscriber_cities, '".$cityid."')")
		// 	->whereRaw("mobile RLIKE '[0-9]{10}'")
		// 	->select("subscriber_email AS email", "subscriber_name AS name", "mobile")
		// 	->groupBy("mobile")
		// 	->get();
		$customers = NewsletterSubscriber::whereRaw("FIND_IN_SET(subscriber_cities, '".$cityid."')")
			->select("subscriber_email AS email", "subscriber_name AS name", "mobile")
			->get();

		$data = array();
		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	static function getAllEmails() {

		$customers = NewsletterSubscriber::whereRaw("subscriber_email != '' OR subscriber_email IS NOT NULL")->select("subscriber_email")->get();

		$data = array();
		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer['subscriber_email'];
			}
		}

		return $data;
	}

	static function getAllMobiles() {

		$customers = NewsletterSubscriber::whereRaw("mobile != '' OR mobile IS NOT NULL")->select("mobile")->get();

		$data = array();
		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer['mobile'];
			}
		}

		return $data;
	}

	static function updatedNewsletterSuscriber($date){
      $results = \DB::select("SELECT  NS.subscriber_id,NS.customer_id, NS.subscriber_email as email, NS.subscriber_cities,NS.city,NS.subscriber_name, NS.mobile, NS.country_code,NS.source,NS.mobile_sub_status,NS.subscriber_status, NS.created_at, NS.updated_at
		FROM `newsletter_subscriber` AS NS
		LEFT OUTER JOIN sales_flat_order_address AS SFO on telephone = mobile
		WHERE  mobile != ''
		AND SFO.email is null AND SFO.telephone is null
		AND subscriber_email != ''
		AND NS.updated_at >= '".$date."' or NS.created_at >= '".$date."'  or NS.created_at >= '".$date."' GROUP BY NS.subscriber_id "); 
		
		return $results;
	}

}
