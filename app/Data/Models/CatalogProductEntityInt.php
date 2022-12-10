<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogProductEntityInt extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'catalog_product_entity_int';

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

	static function checkCrosssellEnabled($product_id) {
		$enabled_attribute_id = 96;

		$crosssellProductEnabled = CatalogProductEntityInt::whereRaw("entity_id = '".$product_id."'")
			->whereRaw("attribute_id = '".$enabled_attribute_id."'")
			->whereRaw("value = '1'")
			->select('value AS enabled')
			->get();

		$data = '';

		if (!empty($crosssellProductEnabled)) {
			foreach ($crosssellProductEnabled as $enabled) {
				$data = $enabled['enabled'];
			}
		}

		return $data;
	}

	static function getSizeIdbyProductId($product_id) {
		$attribute_id = 133;//size attribute id

		$sizeId = CatalogProductEntityInt::whereRaw("entity_id = '".$product_id."'")
			->whereRaw("attribute_id = '".$attribute_id."'")
			->select('value AS sizeId')
			->get();

		$data = '';

		if (!empty($sizeId)) {
			foreach ($sizeId as $size) {
				$data = $size['sizeId'];
			}
		}

		return $data;
	}

	static function ProductStockInformation() {
		$enabled_attribute_id = 96;
		$query                = CatalogProductEntityInt::join("cataloginventory_stock_status", "catalog_product_entity_int.entity_id", "=", "cataloginventory_stock_status.product_id")
			->whereRaw("cataloginventory_stock_status.stock_status = 0 AND cataloginventory_stock_status.qty > 0 AND catalog_product_entity_int.attribute_id = ".$enabled_attribute_id)
			->update(["catalog_product_entity_int.value" => 1, "cataloginventory_stock_status.stock_status" => 1]);
	}

	static function GetProductCollectionList() {
		$attribute_id = 184;// collection attribute id

		$collections = CatalogProductEntityInt::join("eav_attribute_option_value", "catalog_product_entity_int.value", "=", "eav_attribute_option_value.option_id")
			->whereRaw("catalog_product_entity_int.attribute_id = ".$attribute_id." AND eav_attribute_option_value.store_id = 1")
			->select("catalog_product_entity_int.value AS collectionId", "eav_attribute_option_value.value AS collectionName")
			->groupBy("catalog_product_entity_int.value")
			->orderBy("eav_attribute_option_value.value")
			->get();

		$data = array();

		if (!empty($collections)) {
			foreach ($collections as $collection) {
				$data[] = $collection->toArray();
			}
		}

		return $data;
	}

}
