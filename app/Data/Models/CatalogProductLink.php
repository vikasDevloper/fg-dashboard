<?php
/**
 * User: Komal Bhagat
 */
namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogProductLink extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'catalog_product_link';

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

	static function getCrosssellProducts($product_id) {

		$linkTypeCrosssell = 5;

		$crosssellProducts = CatalogProductLink::whereRaw("catalog_product_link.product_id = '".$product_id."'")
			->whereRaw("catalog_product_link.link_type_id = '".$linkTypeCrosssell."'")
			->select('catalog_product_link.linked_product_id AS crosssellProductId')
			->get();

		$data = array();

		if (!empty($crosssellProducts)) {
			foreach ($crosssellProducts as $crosssell) {
				$data[] = $crosssell->toArray();
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
