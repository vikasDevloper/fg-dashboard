<?php
/**
 * User: Komal Bhagat
 */
namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogProductSuperLink extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'catalog_product_super_link';

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

	static function getAssociatedProducts($product_id) {
		$associatedProducts = CatalogProductSuperLink::whereRaw("catalog_product_super_link.parent_id = '".$product_id."'")
			->select('catalog_product_super_link.product_id AS associatedProduct')
			->get();

		$data = array();

		if (!empty($associatedProducts)) {
			foreach ($associatedProducts as $associated) {
				$data[] = $associated->toArray();
			}
		}

		return $data;
	}

	static function getProductsInventryBySize($product_id) {
		$associatedProducts = CatalogProductSuperLink::join("cataloginventory_stock_item", "catalog_product_super_link.product_id", "=", "cataloginventory_stock_item.product_id")
			->join("catalog_product_entity_int", "catalog_product_super_link.product_id", "=", "catalog_product_entity_int.entity_id")
			->whereRaw("catalog_product_super_link.parent_id = '".$product_id."' AND catalog_product_entity_int.attribute_id = 133 AND catalog_product_entity_int.value > 0")
			->selectRaw("catalog_product_super_link.product_id AS productId, ROUND(cataloginventory_stock_item.qty) AS quantity, catalog_product_entity_int.value AS size")
			->get();

		//dd($associatedProducts);

		$data = array();

		if (!empty($associatedProducts)) {
			foreach ($associatedProducts as $associated) {
				$data[$associated['size']]['qty'] = $associated['quantity'];
			}
		}

		return $data;
	}

	static function getConfigurableProductId($product_id) {
		$configurableProducts = CatalogProductSuperLink::whereRaw("catalog_product_super_link.product_id = '".$product_id."'")
			->select('catalog_product_super_link.parent_id AS configurableProductId')
			->get();

		//dd($configurableProducts);

		$data = array();

		if (!empty($configurableProducts)) {
			foreach ($configurableProducts as $configurable) {
				$data = $configurable['configurableProductId'];
			}
		}

		return $data;
	}

}
