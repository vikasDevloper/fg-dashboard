<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerEntity extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'customer_entity';

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

	static function getOnlineCustomers() {
 		 $customers[] = CustomerEntity::distinct()->count('email');
		 $customers[] = SalesFlatOrder::whereRaw("status in ('delivered', 'partial_refund')")->distinct()->count('customer_email');
		 return $customers;
	}

	/**
	 *  get all the customers
	 *  @return all customers
	 */

	static function getAllOnlineCustomers() {
		$allCustomers = CustomerEntity::join('customer_entity_varchar', 'customer_entity_varchar.entity_id', '=', ' customer_entity.entity_id')
			->selectRaw("email, value as name, '' AS mobile, customer_entity.entity_id AS customer_id");
	}

	static function getAllEmails() {

		$customers = CustomerEntity::whereRaw("email != '' OR email IS NOT NULL")
			->select("email")
			->groupBy("email")
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
