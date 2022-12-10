<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class SalesFlatOrderAddress extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'sales_flat_order_address';

	protected $primaryKey = 'entity_id';// or null

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

	static function getAllUsers() {

		$customers = SalesFlatOrderAddress::where("address_type", "shipping")
			->select("customer_id as customerId", "email", "firstname AS name", "telephone as mobile")
			->groupBy('email')
			->orderBy('customer_id', "desc")
			->get();
		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer->toArray();
			}
		}

		return $data;
	}

	static function getCustomersByPincode($pincodeLike) {

		$customers = SalesFlatOrderAddress::whereRaw("postcode REGEXP ".$pincodeLike)
			->whereRaw("telephone RLIKE '[0-9]{10}'")
			//->whereRaw("postcode NOT LIKE '400076'")
			->select("email", "firstname AS name", "telephone as mobile")
			->groupBy('telephone')
			->get();
		// $customers = SalesFlatOrderAddress::whereRaw("postcode Like ".$pincodeLike)
		// 	->select("email", "firstname AS name", "telephone as mobile")
		// 	->groupBy('telephone')
		// 	->toSql();

		//dd($customers);

		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	static function getCustomersByCity($cityLike) {

		// $customers = SalesFlatOrderAddress::whereRaw("city REGEXP ".$cityLike)
		// 	->whereRaw("telephone RLIKE '[0-9]{10}'")
		// 	->select("email", "firstname AS name", "telephone as mobile")
		// 	->groupBy('telephone')
		// 	->get();
		$customers = SalesFlatOrderAddress::whereRaw("city REGEXP ".$cityLike)
			->select("email", "firstname AS name", "telephone as mobile")
			->groupBy('telephone')
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

	static function getCustomersByStreetCity($streetlike, $cityLike) {

		$customers = SalesFlatOrderAddress::whereRaw("city REGEXP ".$cityLike." AND street REGEXP ".$streetlike)
			->select("email", "firstname AS name", "telephone as mobile")
			->groupBy('telephone')
			->get();

		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	//get unique city list
	static function getUniqueCities() {
		$cities = SalesFlatOrderAddress::selectRaw('city, region, count(entity_id) AS cityCount, postcode')
			->groupBy('city')
			->orderBy('city', 'ASC')
			->get();
		//dd($cities);
		$data = array();

		if (!empty($cities)) {
			foreach ($cities as $city) {
				$data[] = $city;
			}
		}

		return $data;
	}

	//Update city name by admin (for city cleaning).
	static function updateCityName($requestParameters) {
		$orderAddressObj = SalesFlatOrderAddress::whereRaw("`city` = '".$requestParameters['select-city']."'")->get();

		//dd($orderAddressObj);

		$customersAddressObj = CustomerAddressEntityVarchar::whereRaw("`attribute_id` = '26' AND `value` = '".$requestParameters['select-city']."'")->get();

		if (!empty($customersAddressObj)) {
			foreach ($customersAddressObj as $customerAddress) {
				$customerAddress->value = $requestParameters['replace-city'];
				if (!$customerAddress->save()) {
					return false;
				}
			}
			if (!empty($orderAddressObj)) {
				foreach ($orderAddressObj as $address) {
					$address->city = $requestParameters['replace-city'];
					if (!$address->save()) {
						return false;
					}
				}
			}

			return true;

		}
	}

	//get Post code
	static function getPostCode() {

		$postCode = SalesFlatOrderAddress::selectRaw('DISTINCT postcode as PinCode')
			->get()	->take(10);
		//dd($cities);
		$data = array();

		if (!empty($postCode)) {
			foreach ($postCode as $pincode) {
				$data[] = trim($pincode['PinCode']);
			}
		}

		return $data;
	}

	static function getAllMobiles() {

		$customers = SalesFlatOrderAddress::whereRaw("telephone != '' OR telephone IS NOT NULL")
			->selectRaw("telephone AS mobile")
			->groupBy("telephone")
			->get();

		$data = array();
		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer['mobile'];
			}
		}
	}

	static function getAddress($parentId){
      $address = SalesFlatOrderAddress::whereRaw("parent_id=".$parentId)
                ->select("firstname","lastname","street","region","postcode","country_id","address_type","region_id")
				->get();

		
		if (!empty($address)) {
			if(isset($address[0]['region_id']))
				$region_code = DB::table('directory_country_region')
				               ->whereRaw("region_id=".$address[0]['region_id'])
                				->select("code")->first();

			foreach ($address as $add) {
				$type = $add['address_type'];
				$data[$type]['name'] = $add['firstname'].$add['lastname'];
				$data[$type]['address'] = $add['street'];
				$data[$type]['state'] = $add['region'];
				$data[$type]['country'] = $add['country_id'];
				$data[$type]['pincode'] = $add['postcode'];
				$data[$type]['region_id'] = $add['region_id'];
				if(isset($region_code->code))
				$data[$type]['region_code'] = $region_code->code;
			    else
			    $data[$type]['region_code'] = "";

			}
		}
		 //dd($data);
      return $data;

	}

    static function updatedOnlineBuyers($date){

       $results = DB::select( "SELECT SFO.entity_id, SFO.status, SFO.base_grand_total,SFO.grand_total, SFO.order_currency_code, SFOA.customer_id, SFOA.region_id,SFOA.region, SFOA.street, SFOA.postcode, SFOA.lastname, SFOA.firstname, SFOA.city, SFOA.email,SFOA.country_id,SFOA.telephone as mobile, SFOA.created_at,SFO.updated_at, GROUP_CONCAT( if(INSTR(SFOI.name,'-')> 0 , SUBSTRING_INDEX(SFOI.name,'-',-1), 'FS')) AS sizes,GROUP_CONCAT(SUBSTRING_INDEX(SFOI.name,'-',1)) AS name FROM `sales_flat_order_address` as SFOA INNER Join `sales_flat_order` as SFO ON SFO.entity_id = SFOA.parent_id inner join `sales_flat_order_item` as SFOI ON SFOI.order_id = SFO.entity_id where SFOA.address_type = 'shipping' and  SFOI.product_type = 'simple'  and SFO.updated_at>= '".$date."' group by SFOI.order_id");

       return $results;
    }

}
