<?php
/**
 * User: Komal Bhagat
 */
namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Dashboard\Data\Models\CatalogProductEntity;
use Dashboard\Data\Models\CatalogProductSuperLink;

class CatalogProductEntityVarchar extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'catalog_product_entity_varchar';

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


	static function getProductNameByProductId($product_id) {

		$name_attribute_id = 71;

		$crosssellProductName = CatalogProductEntityVarchar::whereRaw("entity_id = '".$product_id."'")
			->whereRaw("attribute_id = '".$name_attribute_id."'")
			->select('value AS crosssellName')
			->get();

		$data = '';

		if (!empty($crosssellProductName)) {
			foreach ($crosssellProductName as $name) {
				$data = $name['crosssellName'];
			}
		}

		return $data;
	}

	static function getProductUrlByProductId($product_id) {

		$url_attribute_id = 98;

		$crosssellProductUrl = CatalogProductEntityVarchar::whereRaw("entity_id = '".$product_id."'")
							   ->whereRaw("attribute_id = '".$url_attribute_id."'")
							   ->select('value AS crossselUrl')
			                   ->get();

		//dd($crosssellProductUrl);

		$data = '';

		if (!empty($crosssellProductUrl)) {
			foreach ($crosssellProductUrl as $url) {
				$data = $url['crossselUrl'];
			}
		}

		return $data;
	}

	static function getProductImageUrlByProductId($product_id) {

		$imageurl_attribute_id = 86;

		$crosssellProductUrl = CatalogProductEntityVarchar::whereRaw("entity_id = '".$product_id."'")
			->whereRaw("attribute_id = '".$imageurl_attribute_id."'")
			->select('value AS imageUrl')
			->get();

		$data = '';

		if (!empty($crosssellProductUrl)) {
			foreach ($crosssellProductUrl as $url) {
				$data = $url['imageUrl'];
			}
		}

		return $data;
	}

	/**
	 * Get Style Number
	 *
	 * 
	 */
	static function getStyleNumber() {

		$style_attribute_id = 163;

		$productStyleID = CatalogProductEntityVarchar::whereRaw("attribute_id = '".$style_attribute_id."' AND value IS NOT NULL ")
						->whereRaw("value Not like '%Test%'")
						->selectRaw('DISTINCT value as styleNumber')
						->get();

		$data = '';

		if (!empty($productStyleID)) {
			foreach ($productStyleID as $styleNumber) {
				$data[] = $styleNumber['styleNumber'];
			}
		}

		return $data;
	}
	/**
	 * Get Style, Entity Id
	 *
	 * 
	 */
	static function getStyleByProductId($prodID) {

		$style_attribute_id = 163;

		$productStyleID = CatalogProductEntityVarchar::whereRaw("attribute_id = '".$style_attribute_id."' AND value IS NOT NULL ")
						->whereRaw("value Not like '%Test%' and entity_id = '".$prodID."' ")
						->selectRaw('DISTINCT value as styleNumber,entity_id')
						->get();

		$data = '';

		if (!empty($productStyleID)) {
			foreach ($productStyleID as $styleNumber) {
				$data = $styleNumber['styleNumber'];
			}
		}

		return $data;
	}


	/**
	 * Get Style Number
	 *
	 * 
	 */
	static function getProductName() {

		$style_attribute_id = 71;

					$productName = CatalogProductEntityVarchar::whereRaw("attribute_id = '".$style_attribute_id."' AND value IS NOT NULL ")
						->leftjoin("catalog_product_relation", "catalog_product_entity_varchar.entity_id", "=", "catalog_product_relation.child_id")
						->whereRaw("value Not like '%ass%'")
						->whereRaw("parent_id IS NULL")
						->selectRaw('DISTINCT value as productName')
						->orderBy("catalog_product_entity_varchar.entity_id", "DESC")
						->get();

		if (!empty($productName)) {
			foreach ($productName as $prodName) {
				$data[] = $prodName['productName'];
			}
		}

		return $data;
	}


	/**
	 * Get Product Id On basis of Style Number AND Product Name
	 *
	 * 
	 */

	static function getsalethroughStyleNumber($productName){

		 $productId = CatalogProductEntityVarchar::whereRaw("value like '%".$productName."%' AND attribute_id = 71")
					->selectRaw('entity_id as product_id')
					->get();

		$data = '';

		if (!empty($productId)) {
			foreach ($productId as $val) {
				$product_id = $val['product_id'];
			
			}
		}			


		$styleNumber = CatalogProductEntityVarchar::whereRaw("entity_id = ".$product_id." AND attribute_id = 163")
					->selectRaw('value as style_number')
					->get();

		if (!empty($styleNumber)) {
			foreach ($styleNumber as $val) {
				$styleNumber = $val['style_number'];
			
			}
		}	

		return $styleNumber;

	}
	static function getProductIdByStyle($style){
     $style_attribute_id = 163;
     $productId =  CatalogProductEntityVarchar::whereRaw("attribute_id = '".$style_attribute_id."' AND value = '".$style."'")
						->join("catalog_product_entity", "catalog_product_entity.entity_id", "=", "catalog_product_entity_varchar.entity_id")
						->selectRaw('catalog_product_entity.entity_id, catalog_product_entity_varchar.value')
						->get();
        $data = '';
		if (!empty($productId)) {
			foreach ($productId as $prodName) {
				$data[] = $prodName['entity_id'];
			}
		}
		return $data;
	}

	static function getProductId($styleNumber) {

		$whereparam 	  = '';
		// if($productName) {
		// 	$styleNumber  = $productName;
		// 	$whereparam   = 'AND attribute_id = 71';
		// }

		$productId = CatalogProductEntityVarchar::whereRaw("value like '".$styleNumber."'")
				->selectRaw('entity_id as product_id')
				->get();


		//$productId[0]['product_id'];
		$data = '';

		if (!empty($productId)) {

			if(count($productId) == 1) {

				$configurablePrductId = CatalogProductEntity::checkProductIsConfigurable($productId[0]['product_id']);

				if($configurablePrductId == 'configurable') { 

					$productId  = CatalogProductSuperLink::getAssociatedProducts($productId[0]['product_id']);

					foreach ($productId as $val) {
						$data[] = $val['associatedProduct'];

					}

				} else {
					$data[] = $productId[0]['product_id'];
				} 
				
			} else {

				foreach ($productId as $val) {
				$data[] = $val['product_id'];
				
				}

			} 

		}

		return $data;
	}
}
