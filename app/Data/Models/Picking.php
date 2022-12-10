<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class Picking extends Model
{
    //

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'picking';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;


	/**
	 * Picking SLA
	 * average time taken for picking from time of order confirmation
	 * @return orders
	 *
	 */

	static function deliveryTimelineOrderPicked($date) {

		$customers = SalesFlatOrder::join('sales_flat_order_status_history', 'sales_flat_order.entity_id', '=', 'sales_flat_order_status_history.parent_id')
			->whereRaw("sales_flat_order_status_history.status IN ('order_confirm')")
			->whereRaw("date(sales_flat_order.created_at) between '" . $date['startDate'] . "' AND '" . $date['endDate'] . "'")
			->selectRaw("count(DISTINCT sales_flat_order.entity_id) AS orders, sales_flat_order_status_history.status, 
						 DATEDIFF( (SELECT picking.created_time from picking WHERE picking.orderid = sales_flat_order.increment_id AND picking.pickingstatus = '1'), sales_flat_order_status_history.created_at ) AS days")
			->orderBy("days", "ASC")
			->groupBy("days")
			->get();


		$data = array();
		
		$data['0-1']         = 0;
		$data['2-3']         = 0;
		$data['4-5']         = 0;
		$data['6-7']         = 0;
		$data['7+']          = 0;
		$data['totalOrders'] = 0;

		if (!empty($customers)) {
			foreach ($customers as $value) {

				if ($value['days'] >= 8) {
					$data['7+'] += $value['orders'];
				} elseif ($value['days'] < 2) {
					$data['0-1'] += $value['orders'];
				} elseif ($value['days'] < 4) {
					$data['2-3'] += $value['orders'];
				} elseif ($value['days'] < 6) {
					$data['4-5'] += $value['orders'];
				} elseif ($value['days'] < 8) {
					$data['6-7'] += $value['orders'];
				}

				$data['totalOrders'] += $value['orders'];
			}
		}

		return $data;
	}

	/**
	 * Packed SLA
	 * average time taken for packed from time of order confirmation
	 * @return orders
	 *
	 */

	static function deliveryTimelineOrderPacked($date) {

		$customers = SalesFlatOrder::join('sales_flat_order_status_history', 'sales_flat_order.entity_id', '=', 'sales_flat_order_status_history.parent_id')
			->whereRaw("sales_flat_order_status_history.status IN ('order_confirm')")
			->whereRaw("date(sales_flat_order.created_at) between '" . $date['startDate'] . "' AND '" . $date['endDate'] . "'")
			->selectRaw("count(DISTINCT sales_flat_order.entity_id) AS orders, sales_flat_order_status_history.status, 
						 DATEDIFF(  (SELECT picking.created_time from picking WHERE picking.orderid = sales_flat_order.increment_id AND picking.pickingstatus = '2'), sales_flat_order_status_history.created_at ) AS days")
			->orderBy("days", "ASC")
			->groupBy("days")
			->get();

		//dd($customers);

		$data = array();
		
		$data['0-1']         = 0;
		$data['2-3']         = 0;
		$data['4-5']         = 0;
		$data['6-7']         = 0;
		$data['7+']          = 0;
		$data['totalOrders'] = 0;

		if (!empty($customers)) {
			foreach ($customers as $value) {

				if ($value['days'] >= 8) {
					$data['7+'] += $value['orders'];
				} elseif ($value['days'] < 2) {
					$data['0-1'] += $value['orders'];
				} elseif ($value['days'] < 4) {
					$data['2-3'] += $value['orders'];
				} elseif ($value['days'] < 6) {
					$data['4-5'] += $value['orders'];
				} elseif ($value['days'] < 8) {
					$data['6-7'] += $value['orders'];
				}

				$data['totalOrders'] += $value['orders'];
			}
		}

		return $data;
	}

}
