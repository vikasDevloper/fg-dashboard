<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogCategoryEntityVarchar extends Model
{
    protected $table = 'catalog_category_entity_varchar';

    static function getCategoryByCategoryId($catid){
   	
   	$category = CatalogCategoryEntityVarchar::whereRaw("catalog_category_entity_varchar.`attribute_id` = 41 and `catalog_category_entity_varchar`.`entity_id` = '".$catid."' ")
			->selectRaw(" catalog_category_entity_varchar.`value` as cat_name")
			->get();
        $data                = array();
		
		if (!empty($category)) {
			foreach ($category as $value) {
				$data = $value['cat_name'];
			}
		}
        return $data;
   }
}
