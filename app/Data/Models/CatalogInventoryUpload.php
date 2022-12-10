<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogInventoryUpload extends Model
{
    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'catalog_inventory_upload';

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


	/**
	 * Get Upload Quantity On basis of Style Number
	 *
	 * 
	 */

	static function getUploadProductQuantity($styleNumber) {

		$styleType 	=	'product_style';
		// if($productName) {
		// 	$styleNumber  	= $productName;
		// 	$styleType 		= 'product_name';		
		// }

		$Quantity = CatalogInventoryUpload::whereRaw($styleType ." = '".$styleNumber."'")
						->selectRaw('product_style, sku, Sum(qty) as total')
						->GroupBy('sku')
						->get();

						//dd($Quantity);

		$data = '';

		if (!empty($Quantity)) {
			foreach ($Quantity as $val) {
				$data[] = $val->toArray();
			}
		}
		
		return $data;
	}
}
