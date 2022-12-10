<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class SalesFlatCreditMemoItem extends Model
{
    protected $table = "sales_flat_creditmemo_item";


    static function getItemDetail($orderID){

      $itemDtail = SalesFlatCreditMemoItem::whereRaw("parent_id = '".$orderID."'")
						->selectRaw("qty, price_incl_tax, row_total_incl_tax AS total, discount_amount,order_item_id,sku,name,base_price")
						->groupBy("order_item_id")
						->get();

		$data = array();
		$sumval = 0;

		if (!empty($itemDtail)) {
			foreach ($itemDtail as $value) {
                $item = $value['order_item_id'];
                $sku = $value['sku']; 
               // $type = ((int)$value['base_price'] ==0 ? 'simple' : 'configurable');
                
                $type = (preg_match('/DUPATTA|dupatta|Dupatta|Saree|saree|SAREE|Kaftan|kaftan|KAFTAN/i', $value['name']) ? 'simple' : ((int)$value['base_price'] ==0 ? 'simple' : 'configurable'));
				$data[$sku][$type]['qty']            = $value['qty'];
				$data[$sku][$type]['original_price'] = $value['price_incl_tax'];
				$data[$sku][$type]['total']         = $value['total'];
				$data[$sku][$type]['discount']       = $value['discount_amount'];
				$data[$sku][$type]['product_type']   = $type;
				$data[$sku][$type]['product_name']   = $value['name'];
				$data[$sku][$type]['sku']            = $value['sku'];
				 
			}
		}

      return $data;
	}
}
