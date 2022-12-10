<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class EmailUpdatesLog extends Model {
	//
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'email_updates_log';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;

	/**
	 * Get all the orders group by status
	 *
	 * @return array
	 */

	/**
	 * all customers who ordered
	 * @return customers
	 *
	 */

	static function getUsersGotEmailToday() {

		$customers = EmailUpdatesLog::whereRaw("date(created_at) = date(now())")
			->select("email")
			->orderBy('id', "asc")
			->get();
		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer['email'];
			}
		}

		return $data;
	}

}
