<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderStatus extends Model
{
    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'sales_order_status';

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

	static function statusVal($commentStatus) {

	$statusVal = SalesOrderStatus::whereRaw("status ='".$commentStatus."'")
			->selectRaw("sales_order_status.label AS Label")
			->GroupBy('Label')
			->get();
			
		if (!empty($statusVal)) {
			foreach ($statusVal as $status) {
				$data = $status['Label'];
			}
		}

		return $data;

	}
}
