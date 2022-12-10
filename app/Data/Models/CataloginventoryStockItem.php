<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CataloginventoryStockItem extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'cataloginventory_stock_item';

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

	static function getProductQuantity($product_id) {

		$atyProduct = CataloginventoryStockItem::whereRaw("product_id = '".$product_id."'")
			->selectRaw('ROUND(qty) AS quantity')
			->get();

		$data = '';

		if (!empty($atyProduct)) {
			foreach ($atyProduct as $qty) {
				$data = $qty['quantity'];
			}
		}

		return $data;
	}

	static function getTotalProductsQuantities() {

		$atyProduct = CataloginventoryStockItem::where("is_in_stock", "1")
			->join("catalog_product_entity", "catalog_product_entity.entity_id", "=", "cataloginventory_stock_item.product_id")
			->join("catalog_product_entity_int", "catalog_product_entity_int.entity_id", "=", "cataloginventory_stock_item.product_id")
			->whereRaw("sku like '%FGR%'")
			->where("qty", ">", "0")
			->where("catalog_product_entity_int.attribute_id", "96")
			->where("catalog_product_entity_int.value", "1")
			->selectRaw('qty, product_id, sku')
			->GroupBy("sku")
			->get();

  //  dd($atyProduct);

		$data               = '';
		$data['quantity']   = 0;
		$data['totalValue'] = 0;

		if (!empty($atyProduct)) {
			foreach ($atyProduct as $prod) {
				$data['quantity'] += $prod['qty'];
				$data['totalValue'] += $prod['qty']*CatalogProductEntityDecimal::getProductPriceByProductId($prod['product_id']);
			}
			$data['skus'] = count($atyProduct);
		}

		return $data;
	}

	/* get product list
	have inventoy but disabled */

	static function getDisabledProductsHavingInventory() {

		$atyProduct = CataloginventoryStockItem::join("catalog_product_entity_int", "catalog_product_entity_int.entity_id", "=", "cataloginventory_stock_item.product_id")
			->join("catalog_product_entity_varchar", "catalog_product_entity_varchar.entity_id", "=", "cataloginventory_stock_item.product_id")
			->join("catalog_product_entity", "catalog_product_entity.entity_id", "=", "cataloginventory_stock_item.product_id")
			->whereRaw("sku like '%FGR%'")
			->where("catalog_product_entity_int.attribute_id", "96")
			->where("catalog_product_entity_varchar.attribute_id", "97")
			->where("catalog_product_entity_int.value", "2")
			->where("qty", ">", "0")
			->selectRaw('qty, cataloginventory_stock_item.product_id, catalog_product_entity_varchar.value, sku')
			->get();

		$data = array();

		if (!empty($atyProduct)) {
			foreach ($atyProduct as $prod) {
				$mrp                = CatalogProductEntityDecimal::getProductPriceByProductId($prod['product_id']);
				$d['total_pricing'] = $prod['qty']*$mrp;
				$d['mrp']           = $mrp;
				$d['qty']           = $prod['qty'];
				$d['product_id']    = $prod['product_id'];
				$d['name']          = $prod['value'];
				$d['sku']           = $prod['sku'];
				$data[]             = $d;
			}
		}

		return $data;
	}

	/* get product list
	have inventoy but disabled */

	static function getVisibleSimpleProductsHavingInventory() {

		$atyProduct = CataloginventoryStockItem::leftjoin("catalog_product_relation", "cataloginventory_stock_item.product_id", "=", "catalog_product_relation.child_id")
			->where("cataloginventory_stock_item.qty", ">=", "1")
			->selectRaw('cataloginventory_stock_item.qty, cataloginventory_stock_item.product_id, parent_id')
			->orderBy("cataloginventory_stock_item.product_id", "DESC")
			->get();

		// $atyProduct = CataloginventoryStockItem::join("catalog_product_entity_int", "cataloginventory_stock_item.product_id", "=", "catalog_product_entity_int.entity_id")
		// 	->leftjoin("catalog_product_relation", "cataloginventory_stock_item.product_id", "=", "catalog_product_relation.child_id")
		// 	->where("cataloginventory_stock_item.qty", ">=", "1")
		// 	->whereRaw("catalog_product_entity_int.attribute_id = 96 AND catalog_product_entity_int.value = 2")
		// 	->selectRaw('cataloginventory_stock_item.qty, cataloginventory_stock_item.product_id, parent_id')
		// 	->orderBy("cataloginventory_stock_item.product_id", "DESC")
		// 	->toSql();

		// print_r($atyProduct);

		$data = array();

		$prevProduct = 0;
		$d           = array();
		if (!empty($atyProduct)) {
			foreach ($atyProduct as $prod) {

				if (!empty($prod['parent_id'])) {
					$productId = $prod['parent_id'];
				} else {
					$productId = $prod['product_id'];
				}

				$confProduct = CatalogProductEntity::join("catalog_product_entity_varchar", "catalog_product_entity_varchar.entity_id", "=", "catalog_product_entity.entity_id")
					->join("catalog_product_entity_int", "catalog_product_entity_int.entity_id", "=", "catalog_product_entity.entity_id")
					->where("catalog_product_entity.entity_id", $productId)
					->where("catalog_product_entity_int.attribute_id", "102")
					->where("catalog_product_entity_varchar.attribute_id", "97")
					->where("catalog_product_entity_int.value", "1")
				//->whereRaw("catalog_product_entity.sku LIKE '%FGR%'")
					->whereRaw("catalog_product_entity.sku NOT LIKE '%assorted%'")
					->selectRaw('catalog_product_entity.entity_id, catalog_product_entity_varchar.value, sku')
					->get();

				//print_r($confProduct);

				if (!empty($confProduct)) {

					foreach ($confProduct as $prcd) {
						if ($prevProduct != $prcd['entity_id']) {
							$prevProduct = $prcd['entity_id'];
							if (!empty($d)) {
								$data[] = $d;
							}
							$d['mrp']        = CatalogProductEntityDecimal::getProductPriceByProductId($prcd['entity_id']);
							$d['product_id'] = $prcd['entity_id'];
							$d['name']       = $prcd['value'];
							$d['sku']        = $prcd['sku'];
							$d['qty']        = $prod['qty'];
							// $d['gt'] = '';

						} else {
							$d['qty'] += $prod['qty'];
						}
					}
				}

			}

		}

		return $data;
	}

	/*
	 * Product withouth category
	 */

	static function getProductWithoutCategory() {
		$atyProduct = CataloginventoryStockItem::leftjoin("catalog_product_relation", "cataloginventory_stock_item.product_id", "=", "catalog_product_relation.child_id")
			->where("is_in_stock", "1")
			->where("qty", ">", "0")
			->selectRaw('qty, cataloginventory_stock_item.product_id, parent_id')
			->orderBy("cataloginventory_stock_item.product_id", "DESC")
			->get();

		$data = array();

		$prevProduct = 0;
		$d           = array();

		if (!empty($atyProduct)) {

			foreach ($atyProduct as $prod) {

				if (!empty($prod['parent_id'])) {
					$productId = $prod['parent_id'];
				} else {
					$productId = $prod['product_id'];
				}

				$confProduct = CatalogCategoryProduct::whereRaw("category_id in ( 5, 6, 7, 8, 10, 11, 12, 19, 29, 30, 31, 32, 33, 34, 35, 36)")
					->where("product_id", "'".$productId."'")
					->selectRaw('product_id, category_id')
					->get();

				if (!$confProduct->isEmpty()) {

					if ($prevProduct != $productId) {

						$prevProduct = $productId;

						if (!empty($d)) {
							$data[]          = $d;
							$d['product_id'] = '';
							$d['name']       = '';
							$d['sku']        = '';
							$d['qty']        = 0;
						}

						$parentProduct = CatalogProductEntity::join("catalog_product_entity_varchar", "catalog_product_entity_varchar.entity_id", "=", "catalog_product_entity.entity_id")
							->join("catalog_product_entity_int", "catalog_product_entity_int.entity_id", "=", "catalog_product_entity.entity_id")
							->where("catalog_product_entity.entity_id", $productId)
							->where("catalog_product_entity_int.attribute_id", "96")
							->where("catalog_product_entity_varchar.attribute_id", "97")
							->where("catalog_product_entity_int.value", "1")
							->whereRaw("sku like '%FGR%'")
							->selectRaw('catalog_product_entity.entity_id, catalog_product_entity_varchar.value, sku')
							->first();

						if (!empty($parentProduct)) {
							$d['mrp']        = CatalogProductEntityDecimal::getProductPriceByProductId($parentProduct['entity_id']);
							$d['product_id'] = isset($parentProduct['entity_id'])?$parentProduct['entity_id']:0;
							$d['name']       = isset($parentProduct['value'])?$parentProduct['value']:0;
							$d['sku']        = isset($parentProduct['sku'])?$parentProduct['sku']:0;
							$d['qty']        = $prod['qty'];
						}
					} else {
						$d['qty'] += $prod['qty'];
					}

				}
			}
		}

		return $data;
	}

	static function getWithoutFilterProduct() {
		$producIdVal = CataloginventoryStockItem::leftjoin("catalog_product_relation", "cataloginventory_stock_item.product_id", "=", "catalog_product_relation.child_id")
			->whereRaw("qty > 0")
			->whereRaw("parent_id IS NULL")
			->selectRaw('SUM(qty) as qty, cataloginventory_stock_item.product_id AS pid, null AS id')
			->GroupBy('pid')
			->orderBy("cataloginventory_stock_item.product_id", "DESC")
			->get();

		$parentIdVal = CataloginventoryStockItem::leftjoin("catalog_product_relation", "cataloginventory_stock_item.product_id", "=", "catalog_product_relation.child_id")
			->whereRaw("qty > 0")
			->whereRaw("parent_id IS NOT NULL")
			->selectRaw('SUM(qty) as qty, cataloginventory_stock_item.product_id AS pid, parent_id AS id')
			->GroupBy('id')
			->orderBy("cataloginventory_stock_item.product_id", "DESC")
			->get();

		$a = array();
		$b = array();

		foreach ($producIdVal as $value) {
			$a[] = $value->toArray();
		}

		foreach ($parentIdVal as $value1) {
			$b[] = $value1->toArray();
		}

		$atyProduct = array_merge($a, $b);

		$data        = array();
		$prevProduct = 0;
		$qty         = 0;
		$filter      = 0;
		if (!empty($atyProduct)) {
			foreach ($atyProduct as $prod) {

				$qty = $prod['qty'];

				if (!empty($prod['id'])) {

					$productId = $prod['id'];

				} else {
					$productId = $prod['pid'];

				}

				$prevProduct = $productId;

				$confProduct = CatalogProductEntityInt::whereRaw("catalog_product_entity_int.attribute_id in (178 , 179, 183, 184, 185 ) AND catalog_product_entity_int.entity_id=".$productId)
					->join("catalog_product_entity_varchar", "catalog_product_entity_varchar.entity_id", "=", "catalog_product_entity_int.entity_id")
					->join("catalog_product_entity", "catalog_product_entity.entity_id", "=", "catalog_product_entity_int.entity_id")
					->where("catalog_product_entity_varchar.attribute_id", "97")
					->whereRaw("catalog_product_entity_varchar.value not like '%assorted%'")
					->whereRaw("sku like '%FGR%'")
					->selectRaw('catalog_product_entity_int.entity_id, catalog_product_entity_int.value, catalog_product_entity_varchar.value As name, catalog_product_entity_int.attribute_id')
					->get();

				if (!empty($confProduct)) {

					$filter = 0;
					$d      = array();
					foreach ($confProduct as $val) {

						if (empty($val['value'])) {
							$filter++;
							$d['entity_id']    = isset($val['entity_id'])?$val['entity_id']:0;
							$d['value']        = isset($val['value'])?$val['value']:0;
							$d['name']         = isset($val['name'])?$val['name']:0;
							$d['attribute_id'] = isset($val['attribute_id'])?$val['attribute_id']:0;
							$pricing           = CatalogProductEntityDecimal::getProductPriceByProductId($val['entity_id']);
							$d['mrp']          = $pricing;

						}

					}
					if ($filter > 0) {

						$d["not_assigned"]  = $filter;
						$d['total_pricing'] = $pricing*$qty;
						$d['qty']           = $qty;

						$data[] = $d;

					}

				}
			}
		}

		return $data;
	}

}
