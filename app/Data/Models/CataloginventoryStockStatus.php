<?php

namespace Dashboard\Data\Models;
use DB;
use Illuminate\Database\Eloquent\Model;


class CataloginventoryStockStatus extends Model
{
    
		/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'cataloginventory_stock_status';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;

	
	static function ProductStockInformation() {
		$enabled_attribute_id = 96;
		
		// $query = CataloginventoryStockStatus::join("catalog_product_entity_int", "catalog_product_entity_int.entity_id", "=", "cataloginventory_stock_status.product_id")

		// ->join("cataloginventory_stock_item", "cataloginventory_stock_item.product_id", "=", "cataloginventory_stock_status.product_id")

		// ->whereRaw("cataloginventory_stock_status.stock_status = 1 AND cataloginventory_stock_status.qty > 0 AND catalog_product_entity_int.attribute_id = ".$enabled_attribute_id)		

		// ->update(["catalog_product_entity_int.value" => 1, "cataloginventory_stock_item.is_in_stock" => 1, "cataloginventory_stock_status.stock_status" => 1]);

		$query = CataloginventoryStockStatus::join("catalog_product_entity_int", "catalog_product_entity_int.entity_id", "=", "cataloginventory_stock_status.product_id")

		 ->join("cataloginventory_stock_item", "cataloginventory_stock_item.product_id", "=", "cataloginventory_stock_status.product_id")

		 ->join("catalog_product_entity", "catalog_product_entity.entity_id", "=", "cataloginventory_stock_status.product_id")

		 ->whereRaw("catalog_product_entity_int.value = 2 AND catalog_product_entity.type_id = 'simple' AND TRUNCATE(cataloginventory_stock_status.qty,0) > 0 AND catalog_product_entity_int.attribute_id = ".$enabled_attribute_id)

		// ->whereRaw("cataloginventory_stock_status.product_id = 6733")

		 ->update(["catalog_product_entity_int.value" => 1, "cataloginventory_stock_item.is_in_stock" => 1]);

	}


	static function ProductDisableInformation() {

		$enabled_attribute_id = 96;

		 $query = CataloginventoryStockStatus::join("catalog_product_entity_int", "catalog_product_entity_int.entity_id", "=", "cataloginventory_stock_status.product_id")

		 ->join("cataloginventory_stock_item", "cataloginventory_stock_item.product_id", "=", "cataloginventory_stock_status.product_id")

		 ->join("catalog_product_entity", "catalog_product_entity.entity_id", "=", "cataloginventory_stock_status.product_id")

		 ->whereRaw("catalog_product_entity_int.value = 1 AND catalog_product_entity.type_id = 'simple' AND TRUNCATE(cataloginventory_stock_status.qty,0) <= 0 AND catalog_product_entity_int.attribute_id = ".$enabled_attribute_id)

		 //->whereRaw("cataloginventory_stock_status.product_id = 8853")

		 ->update(["catalog_product_entity_int.value" => 2, "cataloginventory_stock_item.is_in_stock" => 0]);				
	}

}
