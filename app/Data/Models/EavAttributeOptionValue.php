<?php
/**
 * User: Komal Bhagat
 */
namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class EavAttributeOptionValue extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'eav_attribute_option_value';

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

	static function getSizeBySizeId($size_id) {

		$sizeArray = EavAttributeOptionValue::whereRaw("eav_attribute_option_value.option_id = '".$size_id."'")
			->select('eav_attribute_option_value.value AS size')
			->get();

		$data = array();

		if (!empty($sizeArray)) {
			foreach ($sizeArray as $size) {
				$data = $size['size'];
			}
		}

		return $data;

	}
	/**
 * customers who purchased in last 15 days
 * @return customers
 *
 */
}
