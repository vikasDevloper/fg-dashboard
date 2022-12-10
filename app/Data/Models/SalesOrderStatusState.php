<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderStatusState extends Model
{
    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'sales_order_status_state';

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
	static function statusLabel() {
	
	$statusArrange = array('pending', 'order_confirm', 'holded', 'processing', 'payment_review', 'pending_payment' , 'exchange_order', 'shipped');

	$statusVal = SalesOrderStatusState::join('sales_order_status', 'sales_order_status_state.status', '=', 'sales_order_status.status')
			->selectRaw("sales_order_status.label AS Label,sales_order_status.status As status_code")
			->GroupBy('Label')
			->get();

		$data = array();
		$data1 = array();
		$data2 = array();

		if (!empty($statusVal)) {
			foreach ($statusVal as $status) {
				if(in_array($status['status_code'], $statusArrange)) {
					$data1[] = $status;
				} else {
					$data2[] = $status;
				}
				
			}
			$data[] = array_merge($data1, $data2);	

		}			
		return $data;

	}

}
