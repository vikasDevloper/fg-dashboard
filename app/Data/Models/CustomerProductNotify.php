<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProductNotify extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'customer_product_notify';

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

	static function notifyMeCustomers() {

		$customers = CustomerProductNotify::whereRaw("status = '1' AND customer_mobile != '' AND product_id != ''")
			->select("customer_name AS name", "customer_mobile AS mobile", "product_id", "product_name")
			->groupBy("product_id", "customer_mobile")
			->get();

		$data = '';

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	static function customersToSendNotify() {

		$customers = CustomerProductNotify::join('cataloginventory_stock_item', 'customer_product_notify.product_id', '=', 'cataloginventory_stock_item.product_id')
			->whereRaw("customer_product_notify.status = '1' AND customer_product_notify.customer_mobile != '' AND customer_product_notify.product_id != '' AND ROUND(cataloginventory_stock_item.qty) > 0")
			->select("customer_product_notify.customer_name AS name", "customer_product_notify.customer_mobile AS mobile", "customer_product_notify.product_id", "product_name")
			->groupBy("customer_product_notify.product_id", "customer_product_notify.customer_mobile")
			->get();

		// print_r($customers);
		// exit;

		$data = '';

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	static function notifyMeOpenStatusByEmail() {

		$customers = CustomerProductNotify::whereRaw("status = '1' AND customer_email != ''")
			->select("customer_email")
			->groupBy("customer_email")
			->get();

		$data = '';

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer['customer_email'];
			}
		}

		return $data;
	}

	static function notifyMeOpenStatusByMobile() {

		$customers = CustomerProductNotify::whereRaw("status = '1' AND customer_mobile != ''")
			->select("customer_mobile")
			->groupBy("customer_mobile")
			->get();

		$data = '';

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer['customer_mobile'];
			}
		}

		return $data;
	}
}
