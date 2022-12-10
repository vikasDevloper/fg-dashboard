<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;
use Dashboard\Http\Controllers\Controller;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SalesFlatOrderAddress;
use Dashboard\Data\Models\JtdInvoice;
use Dashboard\Data\Models\SalesFlatOrderItem;
use Dashboard\Data\Models\SalesFlatInvoice;
use Dashboard\Data\Models\CatalogProductEntityVarchar;
use Dashboard\Data\Models\InvoiceDetails;
use Dashboard\Data\Models\InvoiceTaxDetails;
use Dashboard\Data\Models\InvoiceTaxLists;
use Dashboard\Data\Models\CountriesList;
use Dashboard\Data\Models\FedexGlobalAWB;
use Dashboard\Classes\Helpers\Utility;
use Storage;
use Aws\S3\S3Client;
use PDF;
use League\Flysystem\MountManager;
use Illuminate\Support\Facades\Log;
use LynX39\LaraPdfMerger\Facades\PdfMerger;

use Aws\Exception\AwsException;
 

class PdfGenerateController extends Controller
{
   public function getInvoiceDetail($orderid = ""){
    //$data1 = SalesFlatOrder::getlastDayOrders();
    $cdate = date("Y-m-d H:i:s");
    $date = strtotime($cdate); 
    $date = strtotime("-1 day", $date);
    $date = strtotime("+1 second", $date);
    $sdate = date('Y-m-d H:i:s', $date);
    if($orderid == "")
    $data = SalesFlatInvoice::getJtdBydate($sdate,$cdate);
  else
     $data = SalesFlatInvoice::getJtdByorder($orderid);
    
    //$data = JtdInvoice::jtdDetail(); get latest invoice detail
    return $data;
   }

   public function pdfview($id = "")
    {
         if($id== "")
        $datainvoice = $this->getInvoiceDetail();
      else 
        $datainvoice = $this->getInvoiceDetail($id);
       
        if(!empty($datainvoice))
        foreach ($datainvoice as $key => $value) {
           $orderId = $value['order_id'];
           $inovoice =SalesFlatOrder::select('increment_id')
           ->where('entity_id',$orderId)->first();
           $invoiceDate = SalesFlatInvoice::select('created_at','shipping_amount','grand_total','discount_amount','customercredit_discount','order_currency_code')
           ->where('order_id',$orderId)->first();

           $ivoiceArray[$orderId]['sales_inc_id']   = $inovoice->increment_id;
           $ivoiceArray[$orderId]['shipping_amt']   = $invoiceDate->shipping_amount;
           $ivoiceArray[$orderId]['grand_total']    = $invoiceDate->grand_total;
           $ivoiceArray[$orderId]['discount_total'] = $invoiceDate->discount_amount;
           $ivoiceArray[$orderId]['customercredit'] = $invoiceDate->customercredit_discount;
           $ivoiceArray[$orderId]['curency_code']   = $invoiceDate->order_currency_code;
           $ivoiceArray[$orderId]['created_at']     = date("d-m-Y", strtotime($invoiceDate->created_at));
           $ivoiceArray[$orderId]['invoice_date']   =  $invoiceDate->created_at ;
           if($invoiceDate->order_currency_code=='INR')
           $ivoiceArray[$orderId]['invoice_no']     = "FGRO-".$value['invoice_no'];
         else
           $ivoiceArray[$orderId]['invoice_no']     = "FGGL-".$value['invoice_no'];
           $ivoiceArray[$orderId]['address']        = SalesFlatOrderAddress::getAddress($orderId);
           $ivoiceArray[$orderId]['item']           = SalesFlatOrderItem::getItemDetail($orderId);

            foreach ($ivoiceArray as $jtdOrderId => $orderDetail) {
              $htmldata[$jtdOrderId] = "";
              // if($orderDetail['address']['shipping']['country']=="CA")
              //       $country = "CANADA";
              // elseif ($orderDetail['address']['shipping']['country']=="US") {
              //       $country = "US";
              //   }
              //   elseif ($orderDetail['address']['shipping']['country']=="GB") {
              //       $country = "United Kingdom";
              //   }
              // else
              //       $country = "INDIA";

                $country = CountriesList::getCountry(strtolower($orderDetail['address']['shipping']['country']));
                $awb = "";
                $awb = FedexGlobalAWB::getAwbNo($jtdOrderId); 
                   //echo $orderDetail['address']['shipping']['country']; dd($country);
              if($orderDetail['curency_code'] == 'INR') {

                 $currency        = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
                 $toptext         = '';
                 $arn             = '';
                 $tat_tax_text    = "TAXABLE VALUE";
                 $receipt         = "<div > <center> Original for Recipient </center> </div>";
                 $supply          = ""; 
                 $freightShiping  = "FREIGHT";
                 $particulars     = "ONLINE SALE";

              } else {

                 $currency        = '<span style="font-family: DejaVu Sans; sans-serif;"> &#36;</span>';
                 $toptext         = '<p><center> Supply meant for export under Bond or Letter of Undertaking without payment of Integrrated Tax</center></p>';
                 $arn             = '<p>
                                       <span class="bold-one">7. LUT against ARN No : </span>
                                       <strong> AD070519003205C</strong>
                                     </p><p>
                                       <span class="bold-one">8. IEC No : </span>
                                       <strong> AABCF7736N</strong>
                                     </p><p>
                                       <span class="bold-one">9. AD Code : </span>
                                       <strong> 0510005-2900009</strong>
                                     </p><p>
                                       <span class="bold-one">10. Incoterms : </span>
                                       <strong> FOB Delhi</strong>
                                     </p><p>
                                       <span class="bold-one">11. AWB No : </span>
                                       <strong>'. $awb. '</strong>
                                     </p>';
                 $tat_tax_text    = "TOTAL VALUE";
                 $receipt         = '<div > <center> Original for Recipient </center> </div>';
                 $supply          = "Place of Supply : Outside India";
                 $freightShiping  = "Shipping Charge";
                 $particulars     = "Export Sale";

                }

    
              $htmldata[$jtdOrderId] .= '<html>
               <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
              <body>
              <div class="invoice-page" style="/*margin: 0px 2% 0px 2%; */margin-left:1px ;border: 1px solid #000;">

              <div id="invoice12" style="border-bottom: 3px solid #000;border-top: 3px solid #000;background-color: #e1e1e1;"><center<b><h2>Tax Invoice</h2></b></center></div>
              '.$toptext.' 
               <div class="in-wrap">
                    <div class="half" style="margin-left:4px;">
                        <p><span class="bold-one">1. GSTIN : </span><strong>07AABCF7736N1Z6</strong></p>
                        <p><span class="bold-one">2. Name : </span><strong>Farida Gupta Retail Pvt Ltd</strong></p>
                        <p><span class="bold-one">3. Address : </span><strong>138/2/9 1st floor Kishan Garh Village, New Delhi - 110070</strong></p>
                        <p><span class="bold-one">4. Serial No. of Invoice : </span><strong>'.$orderDetail['invoice_no'].'</strong></p>
                        <p><span class="bold-one">5. Date of Invoice : </span><strong>'.$orderDetail['created_at'].'</strong></p>
                        <p><span class="bold-one">6. Order Number : </span><strong>'.$orderDetail['sales_inc_id'].'</strong></p>
                        '.$arn.'
                    </div>
                    '.$receipt.'
                </div>
              <table width="100%" border="0" cellspacing="0" cellpadding="2" class="seller-and-buyer">
               <tbody>
                <tr bgcolor="#e1e1e1">
                    <td valign="top" style="border-top: 1px solid #000; border-bottom: 2px solid #999;"><b>Details of Receiver (Billed to)<b></td>
                    <td valign="top" style="border-top: 1px solid #000; border-bottom: 2px solid #999;"><b>Details of Consignee (Shipped to)</b></td>
                </tr>
                <tr>
                    <td valign="top">
                        <div id="th_2" class="t s5_2">Name : '.$orderDetail['address']['billing']['name'].' </div>
                        <div id="ti_2" class="t s5_2">Address : '.$orderDetail['address']['billing']['address'].'</div>
                        <div id="tj_2" class="t s5_2">State : '.$orderDetail['address']['billing']['state'].'</div>
                        <div id="tj_2_1" class="t s5_2">Country : '.$country.'</div>
                        <div id="tj_2_2" class="t s5_2">Pincode : '.$orderDetail['address']['billing']['pincode'].'</div>
                        <div id="tk_2" class="t s5_2">State Code : '.$orderDetail['address']['billing']['region_code'].'</div>
                        <div id="tl_2" class="t s5_2">GSTIN/Unique ID : N/A</div>
                    </td>
                    <td valign="top">
                        <div id="tn_2" class="t s5_2">Name : '.$orderDetail['address']['shipping']['name'].'  </div>
                        <div id="to_2" class="t s5_2">Address : '.$orderDetail['address']['shipping']['address'].' </div>
                        <div id="tp_2" class="t s5_2">State : '.$orderDetail['address']['shipping']['state'].'</div>
                        <div id="tq_2" class="t s5_2">Country : '.$country.'</div>
                        <div id="tq_2" class="t s5_2"> '.$supply.' </div>
                        <div id="tr_2" class="t s5_2">Pincode : '.$orderDetail['address']['shipping']['pincode'].'</div>
                        <div id="tr_2_1" class="t s5_2">State Code : '.$orderDetail['address']['shipping']['region_code'].'</div>
                        <div id="tr_2_2" class="t s5_2">GSTIN/Unique ID : N/A</div>
                    </td>
                </tr>
              </tbody>
            </table>
             
            <!-- Order -->
           <table border="1" width="100%" cellspacing="0" cellpadding="3">
            <tbody>';
            
              if($orderDetail['curency_code'] == 'INR') {
                 $htmldata[$jtdOrderId] .='
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent" rowspan="2">S.No.</td>
                      <td class="dataTableHeadingContent" rowspan="2">DESCRIPTION of Goods</td>
                      <td class="dataTableHeadingContent" rowspan="2">HSN</td>
                      <td class="dataTableHeadingContent" rowspan="2">Qty</td>
                      <td class="dataTableHeadingContent" rowspan="2">Unit</td>
                      <td class="dataTableHeadingContent" rowspan="2">MRP</td>
                      <td class="dataTableHeadingContent" rowspan="2">Total</td>
                      <td class="dataTableHeadingContent" rowspan="2">Discount</td>
                      <!--  <td class="dataTableHeadingContent" align="right">Selling Price</td> -->
                      <td class="dataTableHeadingContent" rowspan="2">'.$tat_tax_text.'</td>
                      <td class="dataTableHeadingContent" colspan="2">CGST</td>
                      <td class="dataTableHeadingContent" colspan="2">SGST/UTGST</td>
                      <td class="dataTableHeadingContent" colspan="2">IGST</td>';
              } else {
                    $htmldata[$jtdOrderId] .='
                        <tr >
                          <td class="dataTableHeadingContent">S.No.</td>
                          <td class="dataTableHeadingContent">DESCRIPTION of Goods</td>
                          <td  class="dataTableHeadingContent">HSN</td>
                          <td  class="dataTableHeadingContent">Qty</td>
                          <td  class="dataTableHeadingContent" >Unit</td>
                          <td  class="dataTableHeadingContent">MRP</td>
                          <td  class="dataTableHeadingContent" >Total</td>
                          <td  class="dataTableHeadingContent" >Discount</td>
                          <td class="dataTableHeadingContent"  >'.$tat_tax_text.'</td>';

              }

            $htmldata[$jtdOrderId] .='</tr>';
            if($orderDetail['curency_code'] == 'INR') {
                 $htmldata[$jtdOrderId] .='
                <tr>
                    <td class="dataTableHeadingContent" style="border-top: 0px;">Rate</td>
                    <td class="dataTableHeadingContent" style="border-top: 0px;">Amt</td>
                    <td class="dataTableHeadingContent" style="border-top: 0px;">Rate</td>
                    <td class="dataTableHeadingContent" style="border-top: 0px;">Amt</td>
                    <td class="dataTableHeadingContent" style="border-top: 0px;">Rate</td>
                    <td class="dataTableHeadingContent" style="border-top: 0px;">Amt</td>
                </tr>';
            }
               
            $totaltaxableamount_12 = array();
            $totaltaxableamount_5 = array();
            
            $totalcgstamount_12 = array();
            $totalcgstamount_5 = array();

            $totalsgstamount_12 = array();
            $totalsgstamount_5 = array();

            $totaligstamount_12 = array();
            $totaligstamount_5 = array();

            
            $totaltaxval = array();
            $i=1;
            foreach( $orderDetail['item'] as $key => $orderItem ) { 

                if( count($orderItem)==2)
                    $type = "configurable";
                else
                    $type = "simple";

                $regioncode = $orderDetail['address']['shipping']['region_id'];
               if($orderDetail['curency_code'] == 'INR'){
                  if($orderItem[$type]['original_price'] <=1050) {
                      $totalassable = (($orderItem[$type]['total']-$orderItem[$type]['discount'])/105)*100;
                      $cgstper1     = config('invoice.tax2.cgst');
                      $wtgstper1    = config('invoice.tax2.wtgst');
                      $igstper1     = config('invoice.tax2.igst')/2;
                  } else {
                      $totalassable = (($orderItem[$type]['total']-$orderItem[$type]['discount'])/112)*100;  
                      $cgstper1     = config('invoice.tax.cgst');
                      $wtgstper1    = config('invoice.tax.wtgst');
                      $igstper1     = config('invoice.tax.igst')/2;  
                  }
                }else{
                  $totalassable     = ($orderItem[$type]['total']-$orderItem[$type]['discount']);
                      $cgstper1     = 0;
                      $wtgstper1    = 0;
                      $igstper1     = 0;
                }
                 //Hsn calculation
                    $expire_date_saree='30-01-2019';

                  if(strpos(strtolower($orderItem['simple']['product_name']), 'saree') !== false) {
                    if (strtotime($orderDetail['created_at']) > strtotime($expire_date_saree)) { 
                     if($orderDetail['curency_code'] == 'INR') {                
                       $totalassable = (($orderItem[$type]['total']-$orderItem[$type]['discount'])/105)*100;
                       $cgstper1     = config('invoice.tax2.cgst');
                       $wtgstper1    = config('invoice.tax2.wtgst');
                       $igstper1     = config('invoice.tax2.igst')/2;
                     }
                     $hsnCodeqry   =  CatalogProductEntityVarchar::select('value')
                       ->where('attribute_id', 174)->first();
                       $hsnCode      = $hsnCodeqry->value;
                    } else {
                       $hsnCode     = '62114210';                     
                    }

                  } else {
                     $hsnCodeqry = CatalogProductEntityVarchar::select('value')->where('attribute_id', 174)->first();
                      $hsnCode = $hsnCodeqry->value;
                    }
                
                $ivoiceArray[$jtdOrderId]['hsn'] = $hsnCode;
                $freightper[] = $cgstper1;
                $percentagefreight = max($freightper); 
                
                $totaltaxval[] =  $totalassable;
                $salestaxval   =  ($totalassable*5)/100;
                $percentage    = '';

                if($regioncode == 602) {
                    $cgstper              = $cgstper1;
                    $wtgstper             = $wtgstper1;
                    $igstper              = "";
                    $igstrate             = 0;
                    $cgstrate             = ($totalassable*$cgstper1)/100;
                    $wtgstrate            = ($totalassable*$wtgstper1)/100;
                    $cgstpercentage       = '%';
                    $wtgspercentage       = '%';
                    $igstpercentage       = '';
                    $igstotalshippingtax  = 0;
                    $igstshippercetange   = '';
                    $cgstshippercetange   = $percentagefreight;
                    $wtgstshippercetange  = $percentagefreight;
                    $totalshippingwithout = (($orderDetail['shipping_amt'])/(100+($percentagefreight*2)))*100;
                    $cgstotalshippingtax  = ($totalshippingwithout*$percentagefreight)/100;
                    $wtgstotalshippingtax = ($totalshippingwithout*$percentagefreight)/100;
               
                    if($cgstper1 ==6) {
                        $totaltaxableamount_12[] = $totalassable;
                        $totalcgstamount_12[]    = $cgstrate; 
                        $totalsgstamount_12[]    = $wtgstrate; 
                    } else {
                        $totaltaxableamount_5[]  = $totalassable;
                        $totalcgstamount_5[]     = $cgstrate; 
                        $totalsgstamount_5[]     = $wtgstrate; 
                    }
                } else {
                    $cgstper                = '';
                    $igstper                = $igstper1*2; 
                    $wtgstper               = '';
                    //$cgstrate = ($totalassable*$cgstper1)/100;
                     $cgstrate              = 0;
                     $wtgstrate             = 0;
                     $cgstotalshippingtax   = 0;
                     $wtgstotalshippingtax  = 0;
                     $igstrate              = ($totalassable*($igstper))/100;
                    $cgstpercentage         = '';
                    $igstpercentage         = '%';
                    $wtgspercentage         = '';
                    $cgstshippercetange     ='';
                    $wtgstshippercetange    = '';
                   // $cgstshippercetange = $percentagefreight;
                    $igstshippercetange     = $percentagefreight*2;
                    $totalshippingwithout   =  (($orderDetail['shipping_amt'])/(100+($igstshippercetange)))*100;
                    $igstotalshippingtax    =  ($totalshippingwithout*$igstshippercetange)/100;

                    if($igstper1 == 6) {
                        $totaltaxableamount_12[] = $totalassable;
                        $totaligstamount_12[]    = $igstrate; 
                    } else {
                        $totaltaxableamount_5[]  = $totalassable;
                        $totaligstamount_5[]     = $igstrate; 
                    }
                }
                     

                    $totalcgst[]   = $cgstrate;
                    $totalwtgst[]  = $wtgstrate;
                    $totaligst[]   = $igstrate;
                    $total[]       = $orderItem[$type]['total'];
                    $totaltax[]    = $totalassable;

                    // data insert in invoice summary table
                   //echo $orderItem[$type]['total']-$orderItem[$type]['discount'];exit;

                    
                    $InvoiceDetailsObj                =  new InvoiceDetails;
                    $InvoiceDetailsObj->invoice_date  = $orderDetail['invoice_date'] ; 
                    $InvoiceDetailsObj->invoice_no    = $orderDetail['invoice_no'] ; 
                    $InvoiceDetailsObj->order_no      = $jtdOrderId ; 
                    $InvoiceDetailsObj->orderinc_no   = $orderDetail['sales_inc_id'] ; 
                    $InvoiceDetailsObj->item_name     = $orderItem[$type]['product_name'] ; 
                    $InvoiceDetailsObj->sku           = $orderItem[$type]['sku'] ; 
                    $InvoiceDetailsObj->hsn           = $hsnCode ; 
                    $InvoiceDetailsObj->total_qty     = $orderItem[$type]['qty'] ; 
                    $InvoiceDetailsObj->total_value   = $orderItem[$type]['total']-$orderItem[$type]['discount']; 

                    if($orderDetail['curency_code'] == 'INR')
                     $InvoiceDetailsObj->taxable_val  = round($totalassable, 2); 
                    else
                    $InvoiceDetailsObj->taxable_val   = 0; 

                    $InvoiceDetailsObj->igst_per      = $igstper; 
                    $InvoiceDetailsObj->igst          = round($igstrate, 2); 
                    $InvoiceDetailsObj->cgst_per      = $cgstper ; 
                    $InvoiceDetailsObj->cgst          = round($cgstrate,2) ; 
                    $InvoiceDetailsObj->sgst_per      = $wtgstper ; 
                    $InvoiceDetailsObj->sgst          = round($wtgstrate,2) ; 
                    $InvoiceDetailsObj->state         = $orderDetail['address']['billing']['state'] ; 
                    
                    $InvoiceDetailsObj-> save();
                    //dd($InvoiceDetailsObj) ; 
                 $htmldata[$jtdOrderId] .='<tr class="dataTableRow">
                     <td class="dataTableContent" valign="top" align="">'.$i++ .'</td>
                     <td class="dataTableContent" valign="top">'.$orderItem['simple']['product_name'].' </td>
                     <td class="dataTableContent" valign="top" align="">'.$hsnCode.'</td>
                     <td class="dataTableContent" valign="top" align="">'.$orderItem[$type]['qty'].'</td>
                     <td class="dataTableContent" valign="top" align="">Pc.</td>
                     <td class="dataTableContent" align="right" valign="top"><b>'.$currency.number_format($orderItem[$type]['original_price'],2).'</b></td>
                     <td class="dataTableContent" align="right" valign="top"><b>'.$currency.number_format($orderItem[$type]['total'],2).'</b></td>
                     <td class="dataTableContent" align="right" valign="top"><b>'.$currency.number_format($orderItem[$type]['discount'],2).'</b></td>
                     <td class="dataTableContent" align="right" valign="top"><b>'.$currency.number_format($totalassable, 2).'</b></td>';

                     if($orderDetail['curency_code'] == 'INR') {
                      $htmldata[$jtdOrderId] .='<td class="dataTableContent" align="right" valign="top"><b>'.$cgstper.$cgstpercentage.'</b></td>
                      <td class="dataTableContent" align="right" valign="top"><b>'.$currency.number_format($cgstrate, 2).'</b></td>
                      <td class="dataTableContent" align="right" valign="top"><b>'.$wtgstper.$wtgspercentage.'</b></td>
                      <td class="dataTableContent" align="right" valign="top"><b>'.$currency.number_format($wtgstrate,2).'</b></td>
                      <td class="dataTableContent" align="right" valign="top"><b></b>'.$igstper.$igstpercentage.'</td>
                      <td class="dataTableContent" align="right" valign="top"><b>'.$currency.number_format($igstrate, 2).'</b></td>';
                    }
                  $htmldata[$jtdOrderId] .='</tr>';
            
                }                  
                $total[]            = $orderDetail['shipping_amt']; 
                $totaltax[]         = $totalshippingwithout;
                $totalcgst[]        = $cgstotalshippingtax;
                $totalwtgst[]       = $wtgstotalshippingtax;
                $totaligst[]        = $igstotalshippingtax;
                 
                $total_12tax        = array_sum($totaligstamount_12)+array_sum($totalcgstamount_12) + array_sum($totalsgstamount_12);
                 $total_5tax        = array_sum($totaligstamount_5)+array_sum($totalcgstamount_5) + array_sum($totalsgstamount_5);
                $total_sum          = array_sum($total);
                $total_tax          = array_sum($totaltax);
                $totalcgst_sum      = array_sum($totalcgst);
                $totalwtgst_sum     = array_sum($totalwtgst);
                $totaligst_sum      = array_sum($totaligst);
                
                unset($total,$totaltax,$totalwtgst,$totalcgst,$totaligst,$freightper);
                   if($orderDetail['curency_code'] == 'USD') {
                      $totaltaxableamount_5  = array();
                      $totaltaxableamount_12 = array();

                    }
                // data insert in invoice taxable summary 

                  $invoicetaxArray['invoice_date']   = $orderDetail['invoice_date']; 
                  $invoicetaxArray['particulars']    = $particulars; 
                  $invoicetaxArray['voucher_type']   = "Sales"; 
                  $invoicetaxArray['vch_no']         = $orderDetail['invoice_no'];  
                  $invoicetaxArray['order_inc_id']   = $orderDetail['sales_inc_id']; 
                  $invoicetaxArray['gross_total']    = $total_sum; 

                  if($orderDetail['curency_code'] == 'INR') {
                    if($regioncode == 602) {
                      $invoicetaxList['local_gst_per_5']  = round(array_sum($totaltaxableamount_5), 4); 
                      $invoicetaxList['local_gst_per_12'] = round(array_sum($totaltaxableamount_12),4); 
                    } else {
                      $invoicetaxList['central_sale_igst_per_12'] = round(array_sum($totaltaxableamount_12), 4);
                      $invoicetaxList['central_sale_igst_per_5'] = round(array_sum($totaltaxableamount_5), 4); 
                    }
                  } else {
                    $invoicetaxList['local_gst_per_5']          = 0; 
                    $invoicetaxList['local_gst_per_12']         = 0; 
                    $invoicetaxList['central_sale_igst_per_12'] = 0;
                    $invoicetaxList['central_sale_igst_per_5']  = 0; 
                  }
                  
                  $invoicetaxList['sgst_per_2.5'] = (empty(array_sum($totalsgstamount_5))) ? round(array_sum($totalsgstamount_5), 4) : (empty(array_sum($totalsgstamount_12))) ? round(array_sum($totalsgstamount_5) + $wtgstotalshippingtax, 4) : round(array_sum($totalsgstamount_5), 4);

                  $invoicetaxList['cgst_per_2.5'] = (empty(array_sum($totalcgstamount_5))) ? round(array_sum($totalcgstamount_5), 4) : (empty(array_sum($totalcgstamount_12))) ? round(array_sum($totalcgstamount_5) + $cgstotalshippingtax, 4) : round(array_sum($totalcgstamount_5), 4); 

                  $invoicetaxList['sgst_per_6']   = (empty(array_sum($totalsgstamount_12))) ? round(array_sum($totalsgstamount_12), 4) : round(array_sum($totalsgstamount_12) + $wtgstotalshippingtax,4); 

                  $invoicetaxList['cgst_per_6']   = (empty(array_sum($totalcgstamount_12))) ? round(array_sum($totalcgstamount_12), 4) : round(array_sum($totalcgstamount_12) + $cgstotalshippingtax, 4); 
                   
                  $invoicetaxList['igst_per_12']  = (empty(array_sum($totaligstamount_12))) ? round(array_sum($totaligstamount_12), 4) : round(array_sum($totaligstamount_12) + $igstotalshippingtax, 4);  
                  
                  $invoicetaxList['igst_per_5']   = (empty(array_sum($totaligstamount_5))) ? round(array_sum($totaligstamount_5), 4) : (empty(array_sum($totaligstamount_12))) ? round(array_sum($totaligstamount_5), 4) + round($igstotalshippingtax, 2) : round(array_sum($totaligstamount_5), 4)  ; 
                  
                  $invoicetaxList['order_inc_id']   = $orderDetail['sales_inc_id']; 
                
                  $invoicetaxArray['freight']       = round($totalshippingwithout, 4); 
                  $invoicetaxArray['discount']      = round($orderDetail['discount_total'], 4); 
                  $invoicetaxArray['narration']     = "BEING GOODS SOLD TO ORDER NO. ".$orderDetail['sales_inc_id']."                                   " .$orderDetail['address']['shipping']['name'];   
                  $invoicetaxArray['locations']     = $orderDetail['address']['billing']['state']; 
                  $invoicetaxArray['country']       = $country ; 
                  $invoicetaxArray['store_credit']  = $orderDetail['customercredit'] ; 
                  $invoicetaxArray['currency_type'] = $orderDetail['curency_code'] ;

                  if($orderDetail['curency_code'] == 'INR')
                  $invoicetaxArray['freight_adjust'] =  round($total_sum + $invoicetaxArray['discount'] -
                   (round(array_sum($totaltaxableamount_12), 4)  + round(array_sum($totaltaxableamount_5),4) + $invoicetaxList['sgst_per_2.5'] + $invoicetaxList['cgst_per_2.5'] + $invoicetaxList['sgst_per_6'] + $invoicetaxList['igst_per_12'] + $invoicetaxList['cgst_per_6']  + $invoicetaxList['igst_per_5']) ,4)   ;
                   else
                    $invoicetaxArray['freight_adjust'] = round($totalshippingwithout, 4);
                
                  $checkorder = InvoiceTaxDetails::where('order_inc_id', '=', $orderDetail['sales_inc_id'])->first();


                    if ($checkorder === null) {
                      $this-> saveInvoiceTaxDetail($invoicetaxArray);
                      $this-> saveInvoiceTaxList($invoicetaxList);
                    }
 
                  unset($invoicetaxArray, $invoicetaxList); 

                $htmldata[$jtdOrderId] .= '<tr class="dataTableRow">
                    <td class="dataTableContent" valign="top" align="" rowspan="12"></td>
                    <td class="dataTableContent" valign="top" align="" colspan="5">'.$freightShiping.'</td>
                    <td class="dataTableContent" valign="top" align="right">'.$currency.number_format($orderDetail['shipping_amt'], 2).'</td>
                    <td class="dataTableContent" valign="top" align="right"></td>
                    <td class="dataTableContent" valign="top" align="right">'.$currency.number_format($totalshippingwithout, 2).'</td>';
                if($orderDetail['curency_code'] == 'INR')
                   {
                    $htmldata[$jtdOrderId] .= '<td class="dataTableContent" valign="top" align="right">'.$cgstshippercetange . $cgstpercentage.'</td>
                    <td class="dataTableContent" valign="top" align="right">'.$currency.number_format($cgstotalshippingtax, 2).'</td>
                    <td class="dataTableContent" valign="top" align="right">'.$wtgstshippercetange . $wtgspercentage.'</td>
                    <td class="dataTableContent" valign="top" align="right">'.$currency.number_format($wtgstotalshippingtax, 2).'</td>
                    <td class="dataTableContent" valign="top" align="right">'.$igstshippercetange . $igstpercentage.'</td>
                    <td class="dataTableContent" valign="top" align="right">'.$currency.number_format($igstotalshippingtax, 2).'</td>';
                   }
                $htmldata[$jtdOrderId] .= '</tr>';
                $htmldata[$jtdOrderId] .= '<tr class="dataTableRow">
                    <td class="dataTableContent" valign="top" align="" colspan="5">Insurance</td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>';
                if($orderDetail['curency_code'] == 'INR')
                   {
                   $htmldata[$jtdOrderId] .= '<td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>';
                  }
               $htmldata[$jtdOrderId] .= '</tr>
                <tr class="dataTableRow">
                    <td class="dataTableContent" valign="top" align="" colspan="5">Packing and Forwarding Charges</td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>';
                    if($orderDetail['curency_code'] == 'INR')
                   {
                   $htmldata[$jtdOrderId] .=
                    '<td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>';
                }

                $htmldata[$jtdOrderId] .='</tr>

                <tr class="dataTableRow">
                    <td class="dataTableContent" valign="top" align="" rowspan="2"></td>
                    <td class="dataTableContent" valign="top" align="" colspan="4">&nbsp;</td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>';
                    if($orderDetail['curency_code'] == 'INR')
                   {
                   $htmldata[$jtdOrderId] .=
                    '<td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>
                    <td class="dataTableContent" valign="top" align=""></td>';
                   }
                            

                $htmldata[$jtdOrderId] .= '</tr> <tr class="dataTableRow">
                    <td class="dataTableContent" valign="top" align="" colspan="4">Total</td>
                    <td class="dataTableContent" valign="top" align="right">'.$currency.number_format($total_sum,2).'</td>
                    <td class="dataTableContent" valign="top" align="right">'.$currency.number_format(abs($orderDetail['discount_total']),2).'</td>
                    <td class="dataTableContent" valign="top" align="right">'.$currency.number_format($total_tax, 2).'</td>';
                     if($orderDetail['curency_code'] == 'INR')
                   {
                    $col1 = 8; $col2 =6;
                   $htmldata[$jtdOrderId] .=
                    '<td class="dataTableContent" valign="top" align="right" colspan="2">'.$currency.number_format($totalcgst_sum, 2).'</td>
                    <td class="dataTableContent" valign="top" align="right" colspan="2">'.$currency.number_format($totalwtgst_sum, 2).'</td>
                    <td class="dataTableContent" valign="top" align="right" colspan="2">'.$currency.number_format($totaligst_sum, 2).'</td>';
                   }
                   else{
                    $col1 = 3; $col2 =9;
                   }
               $htmldata[$jtdOrderId] .= '</tr>';
            
              $htmldata[$jtdOrderId] .=   '<tr class="dataTableRow">
                    <td class="dataTableContent" valign="top" align="" colspan="15">&nbsp;</td>
                </tr>            

                <tr class="dataTableRow">
                 <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'">Total Value of invoice (In figure)</td>
                 <td class="dataTableContent" valign="top" align="" colspan="'.$col2.'">'.$currency.number_format($orderDetail['grand_total'], 2).'</td>
                </tr>

               <!-- <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'">Total Invoice Value (In Word)</td>
                <td class="dataTableContent" valign="top" align="" colspan="9">Rupees one thousand seven hundred forty-nine Only </td>
               </tr> -->

              <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'">Customer Credit Use</td>
                <td class="dataTableContent" valign="top" align="" colspan="'.$col2.'">'.$orderDetail['customercredit'].'</td>
              </tr>


             <!--  
              <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'>Additional Discount</td>
                <td class="dataTableContent" valign="top" align="" colspan="'.$col2.'">₹0.00</td>
             </tr>

            -->


            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'">Amount paid / collected from customer (In figure)</td>
                <td class="dataTableContent" valign="top" align="" colspan="'.$col2.'">'.$currency.number_format($orderDetail['grand_total'], 2).'</td>
            </tr>
 
            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'">Amount paid / collected from customer (In Word)</td>
                <td class="dataTableContent" valign="top" align="" colspan="'.$col2.'">'.Utility::numberTowords($orderDetail['grand_total'], $orderDetail['curency_code']).'</td>
            </tr>

           <!-- <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'">Amount of Tax subject to Reverse Charges</td>
                <td class="dataTableContent" valign="top" align="" colspan="'.$col2.'"></td>
                
            </tr>
           -->
              
           </tbody>
          </table>

        <div style="width: 600px; margin: auto; margin-bottom: 20px;">';
          if($orderDetail['curency_code'] == 'INR') {
            $htmldata[$jtdOrderId] .='<p style="font-size: 11px; text-align: center;">Tax Details</p>
            <table border="1" width="100%" cellspacing="0" cellpadding="0">
                <tbody><tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" valign="top" style="text-align: center;">GST Tax %</td>
                    <td class="dataTableHeadingContent" valign="top" style="text-align: center;">Taxable Amount</td>
                    <td class="dataTableHeadingContent" valign="top" style="text-align: center;">CGST Tax</td>
                    <td class="dataTableHeadingContent" valign="top" style="text-align: center;">SGST Tax</td>
                     <td class="dataTableHeadingContent" valign="top" style="text-align: center;">IGST Tax</td>
                    <td class="dataTableHeadingContent" valign="top" style="text-align: center;">Total Tax</td>
                </tr>

                <tr class="dataTableRow">
                    <td class="dataTableContent" valign="top" style="text-align: center;">12%</td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format(array_sum($totaltaxableamount_12),2).'</td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format(array_sum($totalcgstamount_12), 2) .'</td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format(array_sum($totalsgstamount_12), 2).'      </td>
                     <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format(array_sum($totaligstamount_12), 2).'          </td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format($total_12tax, 2).'
                     </td>
                </tr>

                <tr class="dataTableRow">
                    <td class="dataTableContent" valign="top" style="text-align: center;">5%</td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format(array_sum($totaltaxableamount_5), 2).'</td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format(array_sum($totalcgstamount_5), 2) .'          </td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format(array_sum($totalsgstamount_5), 2).'      </td>
                     <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format(array_sum($totaligstamount_5), 2).'          </td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format($total_5tax, 2).'
                     </td>
                </tr>

                 <tr class="dataTableRow">
                    <td class="dataTableContent" valign="top" style="text-align: center;">Freight</td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format($totalshippingwithout, 2).'</td>
                    
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format($cgstotalshippingtax, 2).'        </td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format($wtgstotalshippingtax, 2).'           </td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format($igstotalshippingtax, 2).'            </td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format(($cgstotalshippingtax + $wtgstotalshippingtax + $igstotalshippingtax), 2).' </td>
                </tr>

                <tr class="dataTableRow">
                    <td class="dataTableContent" valign="top" style="text-align: center;">GRAND TOTALS </td>
                    <td class="dataTableContent" valign="top" style="text-align: right;"></td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format($totalcgst_sum, 2).'
                              
                    </td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format($totalwtgst_sum, 2).'           </td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format($totaligst_sum, 2).'           </td>
                    <td class="dataTableContent" valign="top" style="text-align: right;">'.$currency.number_format(($totalcgst_sum + $totalwtgst_sum +$totaligst_sum), 2) .'        </td>
                </tr>

              </tbody>
            </table>';
            unset($totaltaxableamount_5, $totaligstamount_5, $totaligstamount_5, $totaligstamount_5, $totaligstamount_5, $total_5tax, $totaligst_sum, $totalwtgst_sum, $totalcgst_sum, $totalwtgst_sum);
          }
          $htmldata[$jtdOrderId] .= '<div id="footer">
                <table style="width:100%;font-size:8px; ">
                      
                      <tr>
                        <td colspan="2">
                           <br><center><strong>DECLARATION</strong> </center><br>
                           <center> 
                            We declare that this invoice shows actual price of the goods and that all particulars are true and correct.
                             </center>
                        </td>
                    </tr>
                    <tr class="gray-bg border-tt">
                        <td colspan="2" class="border-top-thick-2 border-bottom-thick-3"><center>THIS IS A COMPUTER GENERATED INVOICE AND DOES NOT REQUIRE SIGNATURE</center></td>
                    </tr> 
                   
                </table>
            </div>
        </div>
            <style>
                
                table{
                    
                    font-size:10px;
                }
                #invoice12 {
                    font-size:12px;
                    background: #e1e1e1 none repeat scroll 0 0;
                    border-bottom: 3px solid #515151;
                    border-top: 3px solid;
                    width: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                p{
                    font-size:11px;
                }
                .seller-and-buyer td:first-child {
                    border-right: 2px solid #515151;
                }
                .seller-and-buyer td {
                    padding: 1px 6px;
                    width: 50%;
                    font-family: serif;
                    font-size: 10px;
                    font-weight: 100;
                    line-height: 14px;
                    margin: 7px 0;
                }
                .dataTableHeadingContent {
                    border-bottom: 2px solid #999;
                    color: #000;
                    font-size: 9px;
                    font-weight: bold;
                    padding: 5px 3px;
                    background: #e1e1e1 none repeat scroll 0 0;
                    border-top: 1px solid #000;
                    border-right: 1px solid #000;
                    text-align: center;
                }
                tr {
                    display: table-row;
                    vertical-align: inherit;
                    border-color: inherit;
                }
                body {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 9px;
                    text-transform: uppercase;
                }
            </style>
        </body>
        </html>';
            $datanew = "";
            foreach ($htmldata as $jtdOrderID => $data) {

                $invoiceDate    = $ivoiceArray[$jtdOrderID]['created_at'];
                $invoiceno      = $ivoiceArray[$jtdOrderID]['invoice_no'];
                $increment_id   = $ivoiceArray[$jtdOrderID]['sales_inc_id'];
                $Country_code        = $ivoiceArray[$jtdOrderID]['curency_code'];
                $year           = date("Y",strtotime($invoiceDate));
                $month          = Date("M", strtotime($invoiceDate));
                $datanew        = $data;
                $pdf            = PDF::loadHtml($data);
                $folder         = "$Country_code/$year/$month/";
                $imageName      =  $invoiceno.'_'.$increment_id.".pdf";
                $fileName       = $folder.$imageName;
                $exists         = Storage::disk('s3')->exists($fileName);

                if($exists) {
                  for ($i=1; $i <= 5; $i++) {
                      $imageName =  $invoiceno.'_'.$increment_id."_".$i.".pdf";
                      $fileName  = $folder.$imageName;
                      $exists    = Storage::disk('s3')->exists($fileName);
                      if(!$exists) {
                        break;
                      }
                  }
                }

                JtdInvoice::setFilename($jtdOrderID,$imageName,$folder); 
                Storage::disk('s3')->put($fileName,  $pdf->output()); 
                /*dd($jtdOrderID);
                $pdf->save("/home/farida/Backend/storage/invoicedemo_".$jtdOrderID.".pdf");
                dd(Storage::disk());*/
                
                  echo Storage::disk('s3')->url($fileName)."\n";
                  Log::info("PDF Invoice Created $fileName");
            }
            unset($htmldata);
          }
        unset($ivoiceArray);
       }
        else
          {
            echo "invoice not created";
            return 'false';
           }
          
    }

 
   public function downloadByOrderID($orderID){
    
    $entityID = SalesFlatOrder::select('entity_id')
           ->where('increment_id',$orderID)->first();
    if($entityID!= NULL)
    $entity_id = $entityID->entity_id;
    else 
    return "Invoice not created for $orderID";    
 

        if(isset($entity_id)){
            $asset = JtdInvoice::select('bucket_path','filename')
            ->where('order_id',$entity_id)->first();

            // $data = "invoice_id_79627_10-07-2019.pdf";
            // $asset->filename = 'INR/2019/Jul/'. $data;
            if(isset($asset)){

            $s3File = $asset->bucket_path.$asset->filename;

            $assetPath = Storage::disk('s3')->get($s3File);
             Storage::disk("public")->put("orders/{$asset->filename}", $assetPath);

             $public_dir=storage_path('app/public');
             $file = $public_dir."/orders/".$asset->filename;
             header("Content-Type: application/octet-stream");
             header("Content-Transfer-Encoding: Binary");
             header("Content-Length: ".filesize($file));
             header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
             readfile($file);
             unlink($file);
           }
        }
   }
    public function downloadSummary($start,$end)
    {
      $data = InvoiceDetails::invoiceSummary($start, $end);
       
       header('Content-Type: application/csv');
       header('Content-Disposition: attachment; filename="Invoice Summary._'.$start.'_'.$end.'.csv"');
        echo 'ID, Invoice Date, Invoice No, order No, Orderinc No, Item Name, Sku, Hsn, Total Qty, Total Price, taxable Val, IGST, CGST, SGST, City'."\n";
        echo $data;exit;

     }
    public function downloadAdvanceSummary($start,$end)
        {
          $data = InvoiceTaxDetails::invoiceAdvanceSummary($start, $end);
           
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="Invoice Advance Summary_'.$start.'_'.$end.'.csv"');
            echo 'ID, Date,  Particulars,Voucher Type,  Vch No., Increment ID, GSTIN/UIN, Value ,Gross Total ,LOCAL GST SALE @ 5%, LOCAL GST SALE  @  12 %, SGST / UTGST@2.5%, CGST@2.5%, SGST / UTGST @6 %, CGST@6%, CENTRAL SALE IGST @ 12%,IGST @ 12 %,CENTRAL SALE IGST @5 %,IGST @ 5 %,FREIGHT, DISCOUNT ALLOWED,  Narration,Locations, Country, Store Credit, Currency Type'. "\n";
            echo $data;exit;

         }
    public function downloadZipInvoice($startDate, $endDate){
        $public_dir=storage_path('app/public');
        exec("rm -rf $public_dir/tmp"); 
        $orderID = SalesFlatInvoice::select('order_id')
            ->whereRaw('Date(created_at) between '. "'$startDate'". " AND ". " '$endDate'" )
            ->get();
            // echo $orderID; exit;
             $orders = array();

            if (!empty($orderID)) {
                foreach ($orderID as $id) {
                    $orders[] = $id['order_id'];
                }
            }
           
            if(!empty($orders)){
                foreach ($orders as $key => $orderId) {

                    $asset = JtdInvoice::select('bucket_path','filename')
                    ->where('order_id',$orderId)->first();

                    // $data = "invoice_id_79627_10-07-2019.pdf";
                    // $asset->filename = 'INR/2019/Jul/'. $data;
                    

                    $s3File = $asset->bucket_path.$asset->filename;
                    
                       
                    $exists = Storage::disk('s3')->exists($s3File);
                    if($exists){
                     $file[] =  $asset->filename;
                     $assetPath = Storage::disk('s3')->get($s3File);

                     Storage::disk("public")->put("tmp/{$asset->filename}", $assetPath);  
                    }
               }
               //dd($orders);
               //$resut =  $this->zipDownloads($file);
               $fn = "invoice_".$startDate.'_'.$endDate.'.pdf';
               $resut =  $this->mergePdf($file, $fn);
            } 
          else
             {
              echo "invoice not created ";
             }

    }

    public function mergePdf($files, $mergefn){

        $public_dir=storage_path('app/public');
        $fn =$public_dir."/tmp/";
        $pdfMerger = PDFMerger::init(); //Initialize the merger
         foreach ($files as $key => $file) {
           $pdfMerger->addPDF("$fn$file", 'all');
         }
        $pdfMerger->merge(); 
        //$pdfMerger->save("$public_dir$mergefn");
        $pdfMerger->save("$mergefn", "download");
        exec("rm -rf $public_dir/tmp"); 
       }


    public function downloadInvoice(Request $request)
    {
      //  echo $this->getToken();
      //  echo $this->zipDownload();
      //   echo $this->zipResult();

       if(isset($_GET['order']) && $_GET['order']!="")
        echo $this->downloadByOrderID(trim($_GET['order']));
       else if(isset($_GET['csvsdate']) && isset($_GET['csvedate']) && $_GET['csvedate']!="")
       {
         $this->downloadSummary($_GET['csvsdate'], $_GET['csvedate']) ;  
       }
      else if(isset($_GET['taxsdate']) && isset($_GET['taxedate']) && $_GET['taxedate']!="")
       {
         $this->downloadAdvanceSummary($_GET['taxsdate'], $_GET['taxedate']) ;  
       }

      else if(isset($_GET['sdate']) && isset($_GET['edate']) && $_GET['edate']!="")  {
           $diff= (int)floor(abs(strtotime($_GET['sdate']) - strtotime($_GET['edate']))/(60*60*24));
            if($diff<1)
           $this->downloadZipInvoice($_GET['sdate'], $_GET['edate']) ;     
           else 
           echo "<div style='color:red'> Please select maximum 1 day Invoice Date </div>";  
            }

        return view('dashboard.getInvoice');
    }

    public function zipDownloads($files){
     //print_r($files); exit;
     $public_dir=storage_path('app/public');
       exec("rm -rf $public_dir/invoice.zip"); 
        $zipFileName = 'invoice.zip';//\Carbon\Carbon::now().'.zip';
        $fn =$public_dir."/tmp/";
         
        $zip = new \ZipArchive; 
        if ($zip->open($public_dir . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {    
           foreach ($files as $key => $s3file) {
             if (file_exists($fn.$s3file) && is_file($fn.$s3file))
                $zip->addFile($public_dir."/tmp/".$s3file, $s3file);
             }
             $zip->close();
        }    
        $file=$public_dir.'/'.$zipFileName;
         
        if (headers_sent()) {
            echo 'HTTP header already sent';
        } else {
            if (!is_file($file)) {
                header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
                echo 'File not found';
            } else if (!is_readable($file)) {
                header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
                echo 'File not readable';
            } else {
                header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
                header("Content-Type: application/octet-stream");
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: ".filesize($file));
                header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
                
                readfile($file);
                exec("rm -rf $public_dir/tmp/"); 
            }
        }
 

        /* $headers = array(
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="invoice.zip"',
            );
         return \Response::make($filetopath, 200, $headers);*/
        //$filetopath=$public_dir.'/'.$zipFileName;
         
        if(file_exists($filetopath)){
             return response()->download($filetopath,$zipFileName,$headers);
        }
        return ['status'=>'file does not exist'];

    } 


    public function downloadbackup(){
         $assetPath = Storage::disk('s3db')->get('2017-11-29.sql.gz');
         Storage::disk("public")->put("db/{$asset->filename}", $assetPath);

         echo 1;exit;
    }
    public function saveInvoiceTaxDetail($summaryArray){
       $InvoiceTaxObj =  new InvoiceTaxDetails;
      foreach ($summaryArray as $key => $value) {
        $InvoiceTaxObj ->$key = $value;
      }
      $InvoiceTaxObj-> save();
    }

    public function saveInvoiceTaxList($taxlist){
     
   
      $increment_id = array_pop($taxlist);
      foreach ($taxlist as $taxName => $value) {
    
        $taxNameArr = explode('_per_', $taxName);
        if(!empty($value))
         {
          $InvoiceTaxListObj =  new InvoiceTaxLists;
          $InvoiceTaxListObj ->order_inc_id = $increment_id;
          $InvoiceTaxListObj->tax = $taxNameArr[0];
          $InvoiceTaxListObj->per_tax = $taxNameArr[1];
          $InvoiceTaxListObj->tax_value = $value;
          $InvoiceTaxListObj-> save();
         }
         unset($InvoiceTaxListObj);
      }
    }
}
