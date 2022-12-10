<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class SalesFlatCreditMemo extends Model
{
    protected $table = 'sales_flat_creditmemo';

     static function getCrMemoDetail($sdate, $edate){
     	  $orderNo = SalesFlatCreditMemo::join("jtd_invoice", "jtd_invoice.magentocreditnote", "=", "sales_flat_creditmemo.increment_id")
		->selectRaw('sales_flat_creditmemo.created_at , jtd_invoice.entity_id,jtd_invoice.order_id, jtd_invoice.invoice_no,jtd_invoice.creditmemonumber, jtd_invoice.magentocreditnote')
		->whereRaw("jtd_invoice.cr_filename is NULL and jtd_invoice.magentocreditnote != '' and Year(sales_flat_creditmemo.created_at) >='2019' ORDER BY `jtd_invoice`.`entity_id` DESC")
		->get(); 
	 
         $data = array();
 
		if (!empty($orderNo)) {
			foreach ($orderNo as $value) {
				$data[] = $value->toArray();

			}
		}
 		return $data;
    }
}
