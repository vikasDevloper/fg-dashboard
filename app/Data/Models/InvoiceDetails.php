<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    protected $table = "invoice_details";

     	static function invoiceSummary($startDate, $endDate) {
 		$invoicedetail = InvoiceDetails::whereRaw("Date(invoice_date) between '".$startDate."' and '".$endDate."' ")
		->select('id', 'invoice_date', 'invoice_no', 'order_no', 'orderinc_no', 'item_name', 'sku', 'hsn', 'total_qty', 'total_value', 'taxable_val', 'igst', 'cgst', 'sgst', 'state', 'created_at')
 		->get();
         $data ="";
 		if (!empty($invoicedetail)) {
			
			foreach ($invoicedetail as $invoice) {

				$data .= $invoice['id'].','.$invoice['invoice_date'].','.$invoice['invoice_no'].','.$invoice['order_no'].','.$invoice['orderinc_no'].','.$invoice['item_name'].','. $invoice['sku'].','.$invoice['hsn'].','.$invoice['total_qty'].','.$invoice['total_value'].','.$invoice['taxable_val'].','.$invoice['igst'].','.$invoice['cgst'].','.$invoice['sgst'].','.$invoice['state']. "\n";

			}
		}
           return $data;
        } 


}
