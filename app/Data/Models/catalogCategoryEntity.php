<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class catalogCategoryEntity extends Model
{
    protected $table = "catalog_category_entity";
    
   static function getChildCategoryList()
   {
    $category = catalogCategoryEntity::join('catalog_category_entity_varchar', 'catalog_category_entity.entity_id', '=', 'catalog_category_entity_varchar.entity_id')
			->whereRaw("catalog_category_entity_varchar.`attribute_id` = 41 and catalog_category_entity.`level` = 4")
			->selectRaw("catalog_category_entity.entity_id as cat_id,catalog_category_entity_varchar.value as cat_name")
			->get();
        $data                = array();
		
		if (!empty($category)) {
			foreach ($category as $value) {
				$data[$value['cat_id']] = $value['cat_name'];
			}
		}

		return $data;


   }

}
