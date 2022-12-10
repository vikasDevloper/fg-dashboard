<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class ProductManufacturingInfo extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'product_manufacturing_info';

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

	static function getProductCategoryList() {
		$dataArrays = ProductManufacturingInfo::selectRaw('product_manufacturing_info.product_child_category AS cat_id, (SELECT value FROM catalog_category_entity_varchar WHERE catalog_category_entity_varchar.entity_id = product_manufacturing_info.product_child_category AND catalog_category_entity_varchar.attribute_id = 41 LIMIT 1) AS cat_name')
			->whereRaw('product_manufacturing_info.product_child_category NOT IN (1,18,34,35,49,64,72,73,74,87)')
			->groupBy('product_child_category')
			->orderBy('cat_name', 'ASC')
			->get();

		$data = array();

		if (!empty($dataArrays)) {
			foreach ($dataArrays as $dataArray) {
				$data[] = $dataArray;
			}
		}

		return $data;
	}

	static function getProductReleaseDate($styleNumber){

		$dataArrays = ProductManufacturingInfo::selectRaw('prod_plan_release_date')
		                                        ->whereRaw("product_style_number = '".$styleNumber."'")
		                                        ->get();

		$data = '';

		if (!empty($dataArrays)) {
			foreach ($dataArrays as $dataArray) {
				$data = $dataArray['prod_plan_release_date'];
			}
		}

		return $data;                                 
	}
}
