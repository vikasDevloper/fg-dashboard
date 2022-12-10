<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Dashboard\Data\Models\InvoiceTaxLists;

class InvoiceTaxDetails extends Model
{
    protected $table = "invoice_tax_details";


    static function invoiceAdvanceSummary($startDate, $endDate) {
 		$invoicedetail = InvoiceTaxDetails::whereRaw("Date(invoice_date) between '".$startDate."' and '".$endDate."' ")
		->select('invoice_tax_details.id', 'invoice_tax_details.invoice_date', 'invoice_tax_details.particulars', 'invoice_tax_details.voucher_type', 'invoice_tax_details.vch_no', 'invoice_tax_details.order_inc_id', 'invoice_tax_details.gstin', 'invoice_tax_details.value', 'invoice_tax_details.gross_total', /*'invoice_tax_details.local_gst_5_per', 'invoice_tax_details.local_gst_12_per', 'invoice_tax_details.sgst_2_5_per', 'invoice_tax_details.cgst_2_5_per', 'invoice_tax_details.sgst_6_per', 'invoice_tax_details.cgst_6_per', 'invoice_tax_details.central_sale_igst_12', 'invoice_tax_details.igst_12', 'invoice_tax_details.central_sale_igst_5', 'invoice_tax_details.igst_5',*/ 'invoice_tax_details.freight_adjust', 'invoice_tax_details.discount', 'invoice_tax_details.narration', 'invoice_tax_details.locations','invoice_tax_details.country','invoice_tax_details.store_credit','invoice_tax_details.currency_type', 'invoice_tax_details.created_at')
 		->get();
         $data ="";
 		if (!empty($invoicedetail)) {
			
			foreach ($invoicedetail as $invoice) {

                $invoicelist = InvoiceTaxLists::whereRaw(" `order_inc_id` = '".$invoice['order_inc_id']."' ")
                 ->select('order_inc_id','tax','per_tax','tax_value')
                 ->get();
                 $taxlists = array();
                 if (!empty($invoicelist)) {
                foreach ($invoicelist as $array) {
                    $taxdata =  $array['tax'].'_per_'.$array['per_tax'];
                    $invoice[$taxdata] = $array['tax_value'];
                 }
                }
                $invoice['local_gst_per_5'] = (isset($invoice['local_gst_per_5']) ) ? round($invoice['local_gst_per_5'],2)  : 0; 
                $invoice['local_gst_per_12'] = (isset($invoice['local_gst_per_12']) ) ? round($invoice['local_gst_per_12'],2)  : 0; 
                $invoice['sgst_per_2.5'] = (isset($invoice['sgst_per_2.5']) ) ? round($invoice['sgst_per_2.5'],2)  : 0; 
                $invoice['cgst_per_2.5'] = (isset($invoice['cgst_per_2.5']) ) ? round($invoice['cgst_per_2.5'],2)  : 0; 
                $invoice['sgst_per_6'] = (isset($invoice['sgst_per_6']) ) ? round($invoice['sgst_per_6'],2)  : 0; 
                $invoice['cgst_per_6'] = (isset($invoice['cgst_per_6']) ) ? round($invoice['cgst_per_6'],2)  : 0; 
                $invoice['central_sale_igst_per_12']  =  (isset($invoice['central_sale_igst_per_12']) ) ? round($invoice['central_sale_igst_per_12'],2)  : 0; 
                $invoice['igst_per_12']  =  (isset($invoice['igst_per_12']) ) ? round($invoice['igst_per_12'],2)  : 0; 
                $invoice['central_sale_igst_per_5']  =  (isset($invoice['central_sale_igst_per_5']) ) ? round($invoice['central_sale_igst_per_5'],2)  : 0; 
                $invoice['igst_per_5']  =  (isset($invoice['igst_per_5']) ) ? round($invoice['igst_per_5'],2)  : 0; 
                if($invoice['currency_type'] == "INR")
                $invoice['freight_adjust_new'] = round($invoice['gross_total'] - abs($invoice['discount']) -
                  ($invoice['central_sale_igst_per_12'] +$invoice['central_sale_igst_per_5'] +$invoice['local_gst_per_5'] + 
                  $invoice['local_gst_per_12'] + $invoice['sgst_per_2.5'] + $invoice['cgst_per_2.5'] + $invoice['sgst_per_6'] + $invoice['igst_per_12'] + $invoice['cgst_per_6']  + $invoice['igst_per_5']) ,2)   ;
                 else
                  $invoice['freight_adjust_new'] =  $invoice['freight_adjust'];
                
                if(preg_match("/,/", $invoice['locations']))
                 $invoice['locations'] = str_replace("," , "" , $invoice['locations']);
                if(preg_match("/,/", $invoice['country']))
                 $invoice['country'] = str_replace("," , "" , $invoice['country']);

                $creditText = ($invoice['store_credit'] == 0 ? '' : ' Store Credit for Rs.'.$invoice['store_credit']);
                $data .= $invoice['id'].','.$invoice['invoice_date'].','.$invoice['particulars'].','.$invoice['voucher_type'].','.$invoice['vch_no'].','.$invoice['order_inc_id'].','.$invoice['gstin'].','.$invoice['value'].','.($invoice['gross_total'] - abs($invoice['discount'])).','.$invoice['local_gst_per_5'].','.$invoice['local_gst_per_12'].','.$invoice['sgst_per_2.5'].','.$invoice['cgst_per_2.5'].','.$invoice['sgst_per_6'].','.$invoice['cgst_per_6'].','.$invoice['central_sale_igst_per_12'].','.$invoice['igst_per_12'].','.$invoice['central_sale_igst_per_5'].','.$invoice['igst_per_5'].','.$invoice['freight_adjust_new'].','.$invoice['discount'].','.$invoice['narration'].$creditText.','.$invoice['locations'].','.$invoice['country'].','.$invoice['store_credit'].','.$invoice['currency_type']. "\n";
                unset($invoice, $invoicelist);
			}
		}
            
           return $data;
        } 

}
