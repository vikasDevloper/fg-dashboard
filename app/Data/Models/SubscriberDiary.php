<?php
/**
 * Created By: Komal Bhagat
 */
namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriberDiary extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'subscriber_diary';

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
	static function subscribeFromDiary() {

		$customers = SubscriberDiary::whereRaw("subscribed = 0")
			->limit(300)
			->get();

		//dd($customers);

		$data = array();
		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}
}
