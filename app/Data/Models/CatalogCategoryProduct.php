<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogCategoryProduct extends Model
{
    //
     /**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'catalog_category_product';

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

	 static function getCategoryByProductId($prodid){
   	
   	$category = CatalogCategoryProduct::whereRaw("`catalog_category_product`.`product_id` = '".$prodid."' ")
			->selectRaw(" `catalog_category_product`.category_id as cat_id")
			->get();
	// $category = CatalogCategoryProduct::join('catalog_category_entity_varchar', 'catalog_category_product.category_id', '=', 'catalog_category_entity_varchar.entity_id')
	// 		->whereRaw("catalog_category_entity_varchar.`attribute_id` = 41 and `catalog_category_product`.`product_id` = '".$prodid."' ")
	// 		->selectRaw(" `catalog_category_product`.category_id as cat_id,catalog_category_entity_varchar.value as cat_name")
	// 		->get();
			
        $data                = array();
		
		if (!empty($category)) {
			foreach ($category as $value) {
				$data = $value['cat_id'];
			}
		}
        return $data;
   }

}
