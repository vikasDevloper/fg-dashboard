<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class OfflineCustomerEntity extends Model {
	//
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'offline_customer_entity';

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

	static function getOfflineCustomers() {
		return $customers = OfflineCustomerEntity::distinct('mobile')->count();
	}

	static function getOfflineCityCustomerData($city, $cityid) {
		$customers = OfflineCustomerEntity::whereRaw("city like '%".$city."%' OR city_id = '".$cityid."'")
			->select("email", "name", "mobile")
			->groupBy("mobile")
			->get();

		$data = array();
		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	// static function getOfflineCityCustomerData1($city, $cityid) {
	// 	$customers = OfflineCustomerEntity::whereRaw("city = '".$city."' OR city_id = '".$cityid."'")
	// 		->whereRaw("mobile RLIKE '[0-9]{10}'")
	// 		->select("email", "name", "mobile")
	// 		->groupBy("mobile")
	// 		->get();

	// 	$data = array();
	// 	if (!empty($customers)) {
	// 		foreach ($customers as $customer) {
	// 			$data[] = $customer;
	// 		}
	// 	}

	// 	return $data;
	// }

	static function getGalleryCityCustomerData($cityid, $exhibition_ids) {
		$customers = OfflineCustomerEntity::join('offline_order_details', 'offline_customer_entity.entity_id', '=', 'offline_order_details.customer_id')
			->whereRaw("mobile RLIKE '[0-9]{10}'")
			->whereRaw("offline_order_details.order_place = '".$cityid."' AND offline_order_details.exhibitions_id IN (".$exhibition_ids.")")
			->select("email", "name", "mobile")
			->groupBy("mobile")
			->get();

		$data = array();
		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	static function getAttendeesCityCustomerData($city, $cityid, $startDate, $endDate) {
		$customers = OfflineCustomerEntity::join('offline_order_details', 'offline_customer_entity.entity_id', '=', 'offline_order_details.customer_id')
			->whereRaw("(city = '".$city."' OR city_id = '".$cityid."')")
			->whereRaw("mobile RLIKE '[0-9]{10}'")
			->whereRaw("offline_order_details.order_date BETWEEN '".$startDate."' AND '".$endDate."'")
			->select("email", "name", "mobile")
			->groupBy("mobile")
			->get();

		$data = array();
		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	static function updatedOfflineBuyers($date){

		$results = DB::select( DB::raw("select OOD.`order_id`,OOD.customer_id, OOD.order_bill_number, OOD.exhibitions_id, OOD.order_total, OOD.order_qty, GROUP_CONCAT(OID.item_name) AS name,GROUP_CONCAT(OID.item_size) AS size, OCE.`mobile`,OCE.`email`,OCE.`name`,OCE.`city`, OCE.`city_id` , OOD.created_at from `offline_customer_entity` as OCE 
        	left join `offline_order_details` as OOD on `OCE`.`entity_id` = `OOD`.`customer_id` 
        	left join `offline_item_details` as OID on `OOD`.`order_bill_number` = `OID`.`bill_number` 
        	where OOD.created_at>= '".$date."' and OOD.created_at IS NOT NULL group by `OCE`.`mobile`,`OCE`.`email`") );

             return $results;
	}
}
