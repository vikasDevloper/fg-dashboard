<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerEntityDecimal extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'customer_entity_decimal';

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

	static function getTotalCreditBalance() {

		$attribute_id = 141;

		$creditBalance = CustomerEntityDecimal::whereRaw("attribute_id = '".$attribute_id."'")
			->selectRaw('ROUND(SUM(value), 2) AS credit_balance')
			->get();

		//dd($creditBalance);

		$data = '';

		if (!empty($creditBalance)) {
			foreach ($creditBalance as $credit) {
				$data = $credit['credit_balance'];
			}
		}

		return $data;
	}
}
