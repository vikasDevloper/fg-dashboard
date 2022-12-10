<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class ProductManufacturing extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'product_manufacturing';

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

	static function getManufDetailsByStyle($styleNo) {

		// $dataArrays = ProductManufacturing::join("catalog_product_entity_varchar", "product_manufacturing.product_style_number", "=", "catalog_product_entity_varchar.value")
		// 	->join("catalog_product_entity", "catalog_product_entity_varchar.entity_id", "=", "catalog_product_entity.entity_id")
		// 	->whereRaw('catalog_product_entity_varchar.attribute_id = 163 AND catalog_product_entity.type_id = "configurable"')
		// 	->whereRaw('product_style_number = "'.$styleNo.'" OR product_style_number LIKE "'.$styleNo.'_CH%" AND approval_status != 0 AND product_mrp IS NOT NULL')
		// 	->selectRaw('product_name, product_manufacturing_cost AS manf_cost, product_manufacturing_qty_planned AS planned_qty, product_mrp, catalog_product_entity_varchar.entity_id')
		// 	->get();

		$dataArrays = ProductManufacturing::whereRaw('product_style_number = "'.$styleNo.'"
    		OR product_style_number LIKE "'.$styleNo.'_CH%" AND approval_status != 0 AND product_mrp IS NOT NULL')
			->selectRaw('product_name, product_manufacturing_cost AS manf_cost, product_manufacturing_qty_planned AS planned_qty, product_mrp, product_style_number AS style_no,date(created_at) as created_at')
			->get();

		//dd($dataArrays);

		$data = array();

		if (!empty($dataArrays)) {
			foreach ($dataArrays as $dataArray) {
				$data[] = $dataArray;
			}
		}

		return $data;

	}

	static function getStyle() {
		$dataArrays = ProductManufacturing::select('product_style_number')
			->get();

		$data = array();
		if (!empty($dataArrays)) {
			foreach ($dataArrays as $dataArray) {
				$data[] = $dataArray;
			}
		}

		return $data;

	}
}
