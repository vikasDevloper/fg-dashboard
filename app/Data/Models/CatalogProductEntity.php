<?php

namespace Dashboard\Data\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class CatalogProductEntity extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'catalog_product_entity';

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

	//Product not sold in 20 days
	static function productsNotSelling() {

		$dataArrays = CatalogProductEntity::whereRaw("catalog_product_entity.type_id = 'configurable'")

			->whereRaw("catalog_product_entity.entity_id NOT IN (SELECT product_id FROM sales_flat_order_item WHERE product_type = 'configurable' AND date(created_at) >= date(NOW() - INTERVAL 10 DAY))")

			->whereRaw("catalog_product_entity.entity_id IN (select entity_id from catalog_product_entity_int where catalog_product_entity_int.entity_id = catalog_product_entity.entity_id AND catalog_product_entity_int.attribute_id = 96 AND catalog_product_entity_int.value = '1')")

			->whereRaw("catalog_product_entity.entity_id NOT IN (select entity_id from catalog_product_entity_int where catalog_product_entity_int.entity_id = catalog_product_entity.entity_id AND catalog_product_entity_int.attribute_id = 102 AND catalog_product_entity_int.value < '0')")

			->selectRaw("catalog_product_entity.entity_id AS productId,
				(select CPEV.value from catalog_product_entity_varchar AS CPEV where CPEV.entity_id = catalog_product_entity.entity_id AND CPEV.attribute_id = 71 limit 1) AS name,
				(SELECT CPEV.value FROM catalog_product_entity_varchar AS CPEV WHERE CPEV.entity_id = catalog_product_entity.entity_id AND CPEV.attribute_id = 98 limit 1) AS link")
			->groupBy('catalog_product_entity.entity_id')
			->get();

		//dd($dataArrays);

		$data = array();

		if (!empty($dataArrays)) {
			foreach ($dataArrays as $value) {
				$data[$value['name']]['product_id'] = $value['productId'];
				$data[$value['name']]['link']       = $value['link'];
				$data[$value['name']]['sizes']      = CatalogProductSuperLink::getProductsInventryBySize($value['productId']);
			}
		}

		return $data;
	}

	/*
	 * Topwear products under 1500
	 *
	 */
	static function under1500Kurta($limit, $topwearPrice) {

		$dataArrays = CatalogProductEntity::join("catalog_category_product", "catalog_product_entity.entity_id", "=", "catalog_category_product.product_id")
			->join("catalog_product_entity_decimal", "catalog_product_entity.entity_id", "=", "catalog_product_entity_decimal.entity_id")
			->whereRaw("catalog_category_product.category_id IN (3,5,6) AND ROUND(catalog_product_entity_decimal.value) <= ".$topwearPrice)
			->selectRaw("catalog_product_entity.entity_id AS product_id, catalog_product_entity.sku, catalog_category_product.category_id, ROUND(catalog_product_entity_decimal.value) AS price")
			->groupBy('catalog_product_entity.entity_id')
			->limit($limit)
			->get();

		//dd($dataArrays);

		$data = array();

		if (!empty($dataArrays)) {
			foreach ($dataArrays as $value) {
				$data[] = $value->toArray();
			}
		}

		return $data;
	}

	/*
	 * Bottomwear products under 1000
	 *
	 */
	static function under1000Bottom($limit, $bottomwearPrice) {

		$dataArrays = CatalogProductEntity::join("catalog_category_product", "catalog_product_entity.entity_id", "=", "catalog_category_product.product_id")
			->join("catalog_product_entity_decimal", "catalog_product_entity.entity_id", "=", "catalog_product_entity_decimal.entity_id")
			->whereRaw("catalog_category_product.category_id IN (9,10,11,12,19) AND ROUND(catalog_product_entity_decimal.value) <= ".$bottomwearPrice)
			->selectRaw("catalog_product_entity.entity_id AS product_id, catalog_product_entity.sku, catalog_category_product.category_id, ROUND(catalog_product_entity_decimal.value) AS price")
			->groupBy('catalog_product_entity.entity_id')
			->limit($limit)
			->get();

		//dd($dataArrays);

		$data = array();

		if (!empty($dataArrays)) {
			foreach ($dataArrays as $value) {
				$data[] = $value->toArray();
			}
		}

		return $data;
	}

	/*
	 * Product Have Not Relation and cross Selling not available according to type ID
	 *
	 */
	static function productsHaveNotRelation($typeID) {

		$dataArrays = CatalogProductEntity::join("catalog_product_link", "catalog_product_entity.entity_id", "=", "catalog_product_link.product_id")
			->join("catalog_product_entity_decimal", "catalog_product_entity.entity_id", "=", "catalog_product_entity_decimal.entity_id")
			->join("catalog_product_entity_int", "catalog_product_entity_int.entity_id", "=", "catalog_product_entity.entity_id")
			->whereRaw("catalog_product_entity_int.attribute_id = 96 AND catalog_product_entity_int.value = '1' AND catalog_product_entity.sku like '%FGR%' AND catalog_product_link.product_id NOT IN (SELECT `product_id` FROM `catalog_product_link` WHERE link_type_id = '".$typeID."')")
			->selectRaw("catalog_product_link.product_id AS product_id, catalog_product_entity.sku, ROUND(catalog_product_entity_decimal.value,'2') AS price")
			->groupBy('catalog_product_entity.entity_id')
			->get();

		//dd($dataArrays);

		$data = array();
		$d    = array();

		if (!empty($dataArrays)) {
			foreach ($dataArrays as $value) {

				$d                 = $value->toArray();
				$d['product_name'] = CatalogProductEntityVarchar::getProductNameByProductId($value['product_id']);
				$d['link']         = CatalogProductEntityVarchar::getProductUrlByProductId($value['product_id']);
				$data[]            = $d;

			}
		}

		return $data;
	}

	static function checkProductIsConfigurable($productId) {

		$chkConfigurable = CatalogProductEntity::whereRaw("entity_id = ".$productId)
			->selectRaw('type_id')
			->get();

		$data = '';

		if (!empty($chkConfigurable)) {
			foreach ($chkConfigurable as $name) {
				$data = $name['type_id'];
			}
		}

		return $data;

	}

	static function getManufDetailsByDate($date) {
		$qry = "SELECT product_manufacturing.product_name, product_manufacturing.product_manufacturing_cost AS manf_cost, product_manufacturing.product_manufacturing_qty_planned AS planned_qty, product_manufacturing.product_mrp, product_manufacturing.product_style_number AS style_no,product_manufacturing.created_at FROM catalog_product_entity
			INNER JOIN `catalog_product_entity_varchar` ON catalog_product_entity_varchar.entity_id = catalog_product_entity.entity_id
			INNER JOIN product_manufacturing ON product_manufacturing.product_style_number = catalog_product_entity_varchar.value
			WHERE catalog_product_entity_varchar.`attribute_id` = 163
			AND date(catalog_product_entity.created_at) BETWEEN date('".$date."') AND DATE_ADD('".$date."', INTERVAL 3 month) group by product_manufacturing.product_style_number ";

		/*$dataArrays = CatalogProductEntity::join('users', 'users.id', '=', 'shares.user_id')
		->join('followers', 'followers.user_id', '=', 'users.id')
		whereRaw('product_style_number = "'.$styleNo.'"
		OR product_style_number LIKE "'.$styleNo.'_CH%" AND approval_status != 0 AND product_mrp IS NOT NULL')
		->selectRaw('product_name, product_manufacturing_cost AS manf_cost, product_manufacturing_qty_planned AS planned_qty, product_mrp')
		->get();*/

		$data = DB::select($qry);
		return $data;

	}

	static function getManufDetailsByCollection($collection_id) {
		$qry = "SELECT product_manufacturing.product_name, product_manufacturing.product_manufacturing_cost AS manf_cost, product_manufacturing.product_manufacturing_qty_planned AS planned_qty, product_manufacturing.product_mrp, product_manufacturing.product_style_number AS style_no , product_manufacturing.created_at FROM catalog_product_entity
			INNER JOIN `catalog_product_entity_varchar` ON catalog_product_entity_varchar.entity_id = catalog_product_entity.entity_id
			INNER JOIN `catalog_product_entity_int` ON catalog_product_entity_int.entity_id = catalog_product_entity.entity_id
			INNER JOIN product_manufacturing ON product_manufacturing.product_style_number = catalog_product_entity_varchar.value
			WHERE catalog_product_entity_varchar.`attribute_id` = 163 AND catalog_product_entity_int.`attribute_id` = 184 AND catalog_product_entity_int.value = '".$collection_id."' GROUP BY style_no";

		$data = DB::select($qry);
		return $data;
	}

	static function getManufDetailsByCategory($category_id, $limit = '') {
		if ($limit != '') {
			$limit = 'LIMIT '.$limit.',100';
		}
		$qry = "SELECT product_manufacturing.product_name, product_manufacturing.product_manufacturing_cost AS manf_cost, product_manufacturing.product_manufacturing_qty_planned AS planned_qty, product_manufacturing.product_mrp, product_manufacturing.product_style_number AS style_no,product_manufacturing.created_at  FROM catalog_product_entity
			INNER JOIN `catalog_product_entity_varchar` ON catalog_product_entity_varchar.entity_id = catalog_product_entity.entity_id
			INNER JOIN product_manufacturing ON product_manufacturing.product_style_number = catalog_product_entity_varchar.value
			INNER JOIN product_manufacturing_info ON product_manufacturing.manufacturing_id = product_manufacturing_info.parent_id
			WHERE catalog_product_entity_varchar.`attribute_id` = 163 AND product_manufacturing_info.product_child_category = '".$category_id."'
			group by product_manufacturing.product_style_number ".$limit;


		$data = DB::select($qry);
		return $data;
	}

	static function getManufDetailsCountByCategory($category_id) {

		$qry = "SELECT count(DISTINCT(product_manufacturing.product_style_number)) AS dataCount FROM catalog_product_entity
			INNER JOIN `catalog_product_entity_varchar` ON catalog_product_entity_varchar.entity_id = catalog_product_entity.entity_id
			INNER JOIN product_manufacturing ON product_manufacturing.product_style_number = catalog_product_entity_varchar.value
			INNER JOIN product_manufacturing_info ON product_manufacturing.manufacturing_id = product_manufacturing_info.parent_id
			WHERE catalog_product_entity_varchar.`attribute_id` = 163 AND product_manufacturing_info.product_child_category = ".$category_id;

		//dd($qry);

		$data = DB::select($qry);
		return $data;
	}	
}
