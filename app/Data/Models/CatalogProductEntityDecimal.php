<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogProductEntityDecimal extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'catalog_product_entity_decimal';

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

	static function getProductPriceByProductId($product_id) {
		$price_attribute_id = 75;

		$productPriceData = CatalogProductEntityDecimal::whereRaw("entity_id = '".$product_id."'")
			->whereRaw("attribute_id = '".$price_attribute_id."'")
			->select('value AS price')
			->get();

		$data = '';

		if (!empty($productPriceData)) {
			foreach ($productPriceData as $price) {
				$data = $price['price'];
			}
		}

		return $data;
	}
}
