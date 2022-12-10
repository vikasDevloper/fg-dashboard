<?php
/**
 * Created By: Komal Bhagat
 * Date: 7th April 18, 5:29 PM 
 */
namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddressEntityVarchar extends Model
{
    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'customer_address_entity_varchar';

	protected $primaryKey = 'value_id';// or null

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
	 * all customers id 
	 * @return customers
	 *
	 */


	static function getPostCode() {

		$attribute_id = 30;

		$userPinCode  = CustomerAddressEntityVarchar::whereRaw("attribute_id = '".$attribute_id."'")
					->selectRaw('DISTINCT value as postCode')
					->get();

		$data = array();

		if (!empty($userPinCode)) {
			foreach ($userPinCode as $pcode) {
				$data[] = $pcode['postCode'];
			}
		}

		return $data;
	}

	/**
	 * all customers id 
	 * @return customers
	 *
	 */


	static function getCustomerId($postCode) {

		$attribute_id = 30;
		$pinCode 	  = "'$postCode'";
		$userId 	  = CustomerAddressEntityVarchar::whereRaw("attribute_id = '".$attribute_id."' AND value = " .$pinCode)
					->selectRaw('entity_id as custId')->get();
					//->get()->take(5);
//dd($userId);
		$data = array();

		if (!empty($userId)) {
			foreach ($userId as $pcode) {
				$data[] = $pcode['custId'];
			}
		}

		return $data;
	}

	static function getCustomerCities($custId) {

		$attribute_id = 26;
		// $userId 	= "'$custId'";
		$cityName = CustomerAddressEntityVarchar::whereRaw("attribute_id = '".$attribute_id."' AND entity_id = " .$custId)
					->selectRaw('value')
					->get();
//dd($userId);
		$data = array();

		if (!empty($cityName)) {
			foreach ($cityName as $cityval) {
				$data = $cityval['value'];
			}
		}

		return $data;
	}


}
