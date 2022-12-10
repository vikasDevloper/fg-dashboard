<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;
use Dashboard\Http\Controllers\Controller;
use Dashboard\Data\Models\SalesFlatCreditMemo;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SalesFlatOrderAddress;
use Dashboard\Data\Models\SalesFlatOrderItem;
use Dashboard\Data\Models\SalesFlatOrderGrid;
use Dashboard\Data\Models\SalesFlatCreditMemoItem;
use Dashboard\Data\Models\SalesFlatCreditMemoGrid;
use Dashboard\Data\Models\CatalogProductEntityVarchar;
use Dashboard\Data\Models\CountriesList;
use Dashboard\Classes\Helpers\Utility;
use Dashboard\Data\Models\JtdInvoice;
use Storage;
use Aws\S3\S3Client;
use PDF;
use League\Flysystem\MountManager;
use Illuminate\Support\Facades\Log;
use LynX39\LaraPdfMerger\Facades\PdfMerger;

class CreditMemoController extends Controller
{
    public function creditMemo(){
        
        if(isset($_GET['order']) && $_GET['order']!="")
          $this->CrMemoByOrderID(trim($_GET['order']));
    	else if(isset($_GET['sdate']) && $_GET['sdate']!=""){
            $diff= (int)floor(abs(strtotime($_GET['sdate']) - strtotime($_GET['edate']))/(60*60*24));
            if($diff<3)
             $this->downloadZipCrMemo($_GET['sdate'], $_GET['edate']) ; 
            else 
            echo "<div style='color:red'> Please select maximum 3 days Range </div>";  
        }
    	return view('dashboard.GetCreditMemo');
    }

    public function CrMemoByOrderID($orderID){
    	$entityID = SalesFlatOrder::select('entity_id')
           ->where('increment_id',$orderID)->first();
        if($entityID!= NULL)
        $entity_id = $entityID->entity_id;
        else 
        return "Credit Memo not created for $orderID";    
 
        if(isset($entity_id)){
            $asset = JtdInvoice::select('cr_bucket_path','cr_filename')
            ->where('order_id',$entity_id)->first();

            if(isset($asset)){

            $s3File = $asset->cr_bucket_path.$asset->cr_filename;
            $assetPath = Storage::disk('s3cm')->get($s3File);
             Storage::disk("public")->put("crmemo/{$asset->cr_filename}", $assetPath);

             $public_dir=storage_path('app/public');
             $file = $public_dir."/crmemo/".$asset->cr_filename;
             header("Content-Type: application/octet-stream");
             header("Content-Transfer-Encoding: Binary");
             header("Content-Length: ".filesize($file));
             header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
             readfile($file);
             unlink($file);
           }
        }
    }

    public function downloadZipCrMemo($startDate, $endDate){

    	$orderID = SalesFlatCreditMemo::select('increment_id')
            ->whereRaw('Date(created_at) between '. "'$startDate'". " AND ". " '$endDate'" )
            ->get();
             $increment_id = array();

            if (!empty($orderID)) {
                foreach ($orderID as $id) {
                    $increment_id[] = $id['increment_id'];
                }
            }
            if(!empty($increment_id)){
                foreach ($increment_id as $key => $orderId) {

                    $asset = JtdInvoice::select('cr_bucket_path','cr_filename')
                    ->where('magentocreditnote',$orderId)->whereNotNull('cr_filename')->first();
                    if($asset != NULL) {
                    $s3File = $asset->cr_bucket_path.$asset->cr_filename;
                    $exists = Storage::disk('s3cm')->exists($s3File);
                    if($exists){
                     $file[] =  $asset->cr_filename;
                     $assetPath = Storage::disk('s3cm')->get($s3File);

                     Storage::disk("public")->put("tmp_credit/{$asset->cr_filename}", $assetPath);  
                    }
                }
               }
               
              // $resut =  $this->zipDownloads($file);
               $fn = "credit_memo_".$startDate.'_'.$endDate.'.pdf';
               if(isset($file))
                $resut =  $this->mergePdf($file, $fn);
               else 
                echo "Credit Memo not Created";
            } 
          else
             {
              echo "Credit Memo not Created";
             }
    }
       public function mergePdf($files, $mergefn){
        $public_dir=storage_path('app/public');
        $fn =$public_dir."/tmp_credit/";
        $pdfMerger = PDFMerger::init(); //Initialize the merger
         foreach ($files as $key => $file) {
           $pdfMerger->addPDF("$fn$file", 'all');
         }
        $pdfMerger->merge(); //For a normal merge (No blank page added)

        //$pdfMerger->save("$mergefn");
        $pdfMerger->save("$mergefn", "download");
        exec("rm -rf $public_dir/tmp_credit"); 
       }

        public function zipDownloads($files){
        
        $public_dir=storage_path('app/public');
        exec("rm -rf $public_dir/creditmemo.zip"); 
        $zipFileName = 'creditmemo.zip';//\Carbon\Carbon::now().'.zip';
        $fn =$public_dir."/tmp_credit/";
         
        $zip = new \ZipArchive; 
        if ($zip->open($public_dir . '/' . $zipFileName, \ZipArchive::CREATE) === TRUE) {    
           foreach ($files as $key => $s3file) {
             if (file_exists($fn.$s3file) && is_file($fn.$s3file))
                $zip->addFile($public_dir."/tmp_credit/".$s3file, $s3file);
             }
             $zip->close();
        }    
        $file = $public_dir.'/'.$zipFileName;
         
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
                exec("rm -rf $public_dir/tmp_credit/"); 
            }
        }
    } 

    public function generateCrMemo(){
      $dataCr = $this->getCrdetail();
        
        if(!empty($dataCr))
        foreach ($dataCr as $key => $value) {
           echo $orderId = $value['order_id'];
            
            $invoiceDate = SalesFlatCreditMemo::select('created_at','entity_id','shipping_amount','grand_total','discount_amount','base_customercredit_discount','order_currency_code')
           ->where('increment_id',$value['magentocreditnote'])->first();
          
           $orderdetail = SalesFlatOrder::select('created_at','increment_id')
           ->where('entity_id',$orderId)->first();

           $crArray[$orderId]['cr_inc_id'] = $value['magentocreditnote'];
           $crArray[$orderId]['shipping_amt'] = $invoiceDate->shipping_amount;
           $crArray[$orderId]['grand_total'] = $invoiceDate->grand_total;
           $crArray[$orderId]['discount_total'] = $invoiceDate->discount_amount;
           $crArray[$orderId]['customercredit'] = $invoiceDate->base_customercredit_discount;
           $crArray[$orderId]['curency_code'] = $invoiceDate->order_currency_code;
           $crArray[$orderId]['created_at'] = date("d-m-Y", strtotime($invoiceDate->created_at));
           $crArray[$orderId]['invoice_date'] =  $invoiceDate->created_at ;
          if($invoiceDate->order_currency_code=='INR')
           $crArray[$orderId]['invoice_no']     = "FGRO-".$value['invoice_no'];
         else
           $crArray[$orderId]['invoice_no']     = "FGGR-".$value['invoice_no'];

           $crArray[$orderId]['order_date']   =  $orderdetail->created_at;
           $crArray[$orderId]['order_inc_id']   = $orderdetail->increment_id;
           $crArray[$orderId]['address']      = SalesFlatOrderAddress::getAddress($orderId);
           $crArray[$orderId]['item']         = SalesFlatCreditMemoItem::getItemDetail($invoiceDate->entity_id);
           
           foreach ($crArray as $jtdOrderId => $orderDetail) {
              $htmldata[$jtdOrderId] = "";
              // if($orderDetail['address']['shipping']['country']=="CA")
              //   $country = "CANADA";
              //   elseif ($orderDetail['address']['shipping']['country']=="US") {
              //      $country = "US";
              //   }
              //   else
              //       $country = "INDIA";
               $country = CountriesList::getCountry(strtolower($orderDetail['address']['shipping']['country']));

              if($orderDetail['curency_code'] == 'INR'){
                 $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
                 $toptext = '';
                 $arn = '';
                 $tat_tax_text = "TAXABLE VALUE";
                 $receipt = "<div > <center> Original for Recipient </center> </div>";
                 $supply = ""; 
                 $freightShiping = "FREIGHT";
                 $particulars = "ONLINE SALE";
              }
                else {
                 $currency = '<span style="font-family: DejaVu Sans; sans-serif;"> &#36;</span>';
                 $toptext = '<p><center> Supply meant for export under Bond or Letter of Undertaking without payment of Integrrated Tax</center></p>';
                 $arn = '<p><span class="bold-one">7. LUT against ARN No : </span><strong> AD070519003205C</strong></p>';
                 $tat_tax_text = "TOTAL VALUE";
                 $receipt = '<div > <center> Original for Recipient </center> </div>';
                 $supply = "Place of Supply: OUTSIDE INDIA";
                 $freightShiping = "Shipping Charge";
                 $particulars = "Export Sale";
                }

    
              $htmldata[$jtdOrderId] .= '<html>
               <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
              <body>
              <div class="invoice-page" style="/*margin: 0px 2% 0px 2%; */margin-left:1px ;border: 1px solid #000;">

              <div id="invoice12" style="border-bottom: 3px solid #000;border-top: 3px solid #000;background-color: #e1e1e1;"><center<b><h2>CREDIT NOTE</h2></b></center></div>
              '.$toptext.' 
               <div class="in-wrap">
                    <div class="half" style="margin-left:4px;">
                        <p><span class="bold-one"> 1. GSTIN : </span><strong>07AABCF7736N1Z6</strong></p>
                        <p><span class="bold-one"> 2. Name : </span><strong>Farida Gupta Retail Pvt Ltd</strong></p>
                        <p><span class="bold-one"> 3. Address : </span><strong>138/2/9 1st floor Kishan Garh Village, New Delhi - 110070</strong></p>
                     
                        <p><span class="bold-one"> 4. Credit Note Number : </span><strong>FGRR-'.$value['creditmemonumber'].'</strong></p>
                        <p><span class="bold-one"> 5. Date of Order : </span><strong>'.$orderDetail['order_date'].'</strong></p>
                        <p><span class="bold-one"> 6. Against Invoice Number : </span><strong>'.$orderDetail['invoice_no'].'</strong></p>
                     
                        <p><span class="bold-one"> 7. Order Number : </span><strong>'.$orderDetail['order_inc_id'].'</strong></p>
                        <p><span class="bold-one"> 8. Created Date : </span><strong>'.$orderDetail['created_at'].'</strong></p>

                        '.$arn.'
                    </div>
                    '.$receipt.'
                </div>
              <table width="100%" border="0" cellspacing="0" cellpadding="2" class="seller-and-buyer">
               <tbody>
                <tr bgcolor="#e1e1e1">
                    <td valign="top" style="border-top: 1px solid #000; border-bottom: 2px solid #999;"><b>SOLD RETURN FROM<b></td>
                    <td valign="top" style="border-top: 1px solid #000; border-bottom: 2px solid #999;"><b>SHIP RETURN FROM</b></td>
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
            
             if($orderDetail['curency_code'] == 'INR'){
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
                 }
                 else
                 {
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
            if($orderDetail['curency_code'] == 'INR'){
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
            //print_r($orderDetail['item']);
            //exit;
            foreach( $orderDetail['item'] as $key => $orderItem ) { 

                if( count($orderItem)==2)
                    $type = "configurable";
                else
                    $type = "simple";

                $regioncode = $orderDetail['address']['shipping']['region_id'];
               if($orderDetail['curency_code'] == 'INR'){
                  if($orderItem[$type]['original_price'] <=1050) {
                      $totalassable = (($orderItem[$type]['total']-$orderItem[$type]['discount'])/105)*100;
                      $cgstper1 = 2.5;
                      $wtgstper1 = 2.5;
                      $igstper1 = 2.5;
                  } else {
                      $totalassable = (($orderItem[$type]['total']-$orderItem[$type]['discount'])/112)*100;  
                      $cgstper1 = 6;
                      $wtgstper1 = 6;
                      $igstper1 = 6;  
                  }
                }else{
                  $totalassable = ($orderItem[$type]['total']-$orderItem[$type]['discount']);
                      $cgstper1 = 0;
                      $wtgstper1 = 0;
                      $igstper1 = 0;
                }
                 //Hsn calculation
                    $expire_date_saree='30-01-2019';

                  if(strpos(strtolower($orderItem['simple']['product_name']), 'saree') !== false) {
                    if (strtotime($orderDetail['created_at']) > strtotime($expire_date_saree)) {   
                     if($orderDetail['curency_code'] == 'INR'){               
                      $totalassable = (($orderItem[$type]['total']-$orderItem[$type]['discount'])/105)*100;
                      
                      $cgstper1     = 2.5;
                      $wtgstper1    = 2.5;
                      $igstper1     = 2.5;
                      }
                      $hsnCodeqry      =  CatalogProductEntityVarchar::select('value')
                      ->where('attribute_id', 174)->first();
                      $hsnCode = $hsnCodeqry->value;
                    } else {
                       $hsnCode     = '62114210';                     
                    }

                  } else {
                     $hsnCodeqry      =  CatalogProductEntityVarchar::select('value')
                      ->where('attribute_id', 174)->first();
                      $hsnCode = $hsnCodeqry->value;
                  }
                $ivoiceArray[$jtdOrderId]['hsn'] = $hsnCode;
                $freightper[] = $cgstper1;
                $percentagefreight = max($freightper); 
                
                $totaltaxval[] =  $totalassable;
                $salestaxval =  ($totalassable*5)/100;
                $percentage  = '';
                if($regioncode == 602) {
                    $cgstper = $cgstper1;
                    $wtgstper = $wtgstper1;
                    $igstper = "";
                    $igstrate = 0;
                    $cgstrate = ($totalassable*$cgstper1)/100;
                    $wtgstrate = ($totalassable*$wtgstper1)/100;
                    $cgstpercentage = '%';
                    $wtgspercentage = '%';
                    $igstpercentage = '';
                    $igstotalshippingtax = 0;
                    $igstshippercetange= '';
                    $cgstshippercetange = $percentagefreight;
                    $wtgstshippercetange = $percentagefreight;
                    $totalshippingwithout =  (($orderDetail['shipping_amt'])/(100+($percentagefreight*2)))*100;
                    $cgstotalshippingtax =   ($totalshippingwithout*$percentagefreight)/100;
                    $wtgstotalshippingtax =  ($totalshippingwithout*$percentagefreight)/100;
               
                    if($cgstper1 ==6) {
                        $totaltaxableamount_12[] = $totalassable;
                        $totalcgstamount_12[] = $cgstrate; 
                        $totalsgstamount_12[] = $wtgstrate; 
                    } else {
                        $totaltaxableamount_5[] = $totalassable;
                        $totalcgstamount_5[] = $cgstrate; 
                        $totalsgstamount_5[] = $wtgstrate; 
                    }
                } else {
                    $cgstper = '';
                    $igstper = $igstper1*2; 
                    $wtgstper = '';
                    //$cgstrate = ($totalassable*$cgstper1)/100;
                     $cgstrate = 0;
                     $wtgstrate = 0;
                     $cgstotalshippingtax = 0;
                     $wtgstotalshippingtax = 0;
                    $igstrate = ($totalassable*($igstper))/100;  
                          
                    $cgstpercentage = '';
                    $igstpercentage = '%';
                    $wtgspercentage = '';
                    $cgstshippercetange ='';
                    $wtgstshippercetange = '';
                   // $cgstshippercetange = $percentagefreight;
                    $igstshippercetange = $percentagefreight*2;
                    $totalshippingwithout =  (($orderDetail['shipping_amt'])/(100+($igstshippercetange)))*100;
                    $igstotalshippingtax =  ($totalshippingwithout*$igstshippercetange)/100;

                    if($igstper1 == 6) {
                        $totaltaxableamount_12[] = $totalassable;
                        $totaligstamount_12[] = $igstrate; 
                    } else {
                        $totaltaxableamount_5[] = $totalassable;
                        $totaligstamount_5[] = $igstrate; 
                    }
                }
                     

                    $totalcgst[] = $cgstrate;
                    $totalwtgst[] = $wtgstrate;
                    $totaligst[] = $igstrate;
                    $total[] = $orderItem[$type]['total'];
                    $totaltax[] = $totalassable;

                    // data insert in invoice summary table
                   //echo $orderItem[$type]['total']-$orderItem[$type]['discount'];exit;

                    
                /*    $InvoiceDetailsObj =  new InvoiceDetails;
                    $InvoiceDetailsObj->invoice_date = $orderDetail['invoice_date'] ; 
                    $InvoiceDetailsObj->invoice_no = $orderDetail['invoice_no'] ; 
                    $InvoiceDetailsObj->order_no = $jtdOrderId ; 
                    $InvoiceDetailsObj->orderinc_no  = $orderDetail['sales_inc_id'] ; 
                    $InvoiceDetailsObj->item_name = $orderItem[$type]['product_name'] ; 
                    $InvoiceDetailsObj->sku = $orderItem[$type]['sku'] ; 
                    $InvoiceDetailsObj->hsn = $hsnCode ; 
                    $InvoiceDetailsObj->total_qty  = $orderItem[$type]['qty'] ; 
                    $InvoiceDetailsObj->total_value  = $orderItem[$type]['total']-$orderItem[$type]['discount']; 
                    if($orderDetail['curency_code'] == 'INR')
                     $InvoiceDetailsObj->taxable_val = round($totalassable, 2); 
                    else
                    $InvoiceDetailsObj->taxable_val = 0; 
                    $InvoiceDetailsObj->igst  = round($igstrate, 2); 
                    $InvoiceDetailsObj->cgst  = round($cgstrate,2) ; 
                    $InvoiceDetailsObj->sgst  = round($wtgstrate,2) ; 
                    $InvoiceDetailsObj->state  = $orderDetail['address']['billing']['state'] ; 
                    
                    $InvoiceDetailsObj-> save();*/
                      
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

                     if($orderDetail['curency_code'] == 'INR')
                     {
                      $htmldata[$jtdOrderId] .='<td class="dataTableContent" align="right" valign="top"><b>'.$cgstper.$cgstpercentage.'</b></td>
                      <td class="dataTableContent" align="right" valign="top"><b>'.$currency.number_format($cgstrate, 2).'</b></td>
                      <td class="dataTableContent" align="right" valign="top"><b>'.$wtgstper.$wtgspercentage.'</b></td>
                      <td class="dataTableContent" align="right" valign="top"><b>'.$currency.number_format($wtgstrate,2).'</b></td>
                      <td class="dataTableContent" align="right" valign="top"><b></b>'.$igstper.$igstpercentage.'</td>
                      <td class="dataTableContent" align="right" valign="top"><b>'.$currency.number_format($igstrate, 2).'</b></td>';
                    }
                  $htmldata[$jtdOrderId] .='</tr>';
            
                }                  
                $total[] = $orderDetail['shipping_amt']; 
                $totaltax[] = $totalshippingwithout;
                $totalcgst[] = $cgstotalshippingtax;
                $totalwtgst[] = $wtgstotalshippingtax;
                $totaligst[] = $igstotalshippingtax;
                 
                $total_12tax = array_sum($totaligstamount_12)+array_sum($totalcgstamount_12) + array_sum($totalsgstamount_12);
                 $total_5tax = array_sum($totaligstamount_5)+array_sum($totalcgstamount_5) + array_sum($totalsgstamount_5);
                $total_sum = array_sum($total);
                $total_tax = array_sum($totaltax);
                $totalcgst_sum = array_sum($totalcgst);
                $totalwtgst_sum = array_sum($totalwtgst);
                $totaligst_sum = array_sum($totaligst);
                
                unset($total,$totaltax,$totalwtgst,$totalcgst,$totaligst,$freightper);
                   if($orderDetail['curency_code'] == 'USD'){
                      $totaltaxableamount_5=0;
                      $totaltaxableamount_12=0;

                    }
                // data insert in invoice taxable summary 
                /*  $InvoiceTaxObj =  new InvoiceTaxDetails;
                  $InvoiceTaxObj->invoice_date = $orderDetail['invoice_date']; 
                  $InvoiceTaxObj->particulars = $particulars; 
                  $InvoiceTaxObj->voucher_type = "Sales"; 
                  $InvoiceTaxObj->vch_no = $orderDetail['invoice_no'];  
                  $InvoiceTaxObj->order_inc_id = $orderDetail['sales_inc_id']; 
                  $InvoiceTaxObj->gross_total = $total_sum; 
                  if($orderDetail['curency_code'] == 'INR'){
                    if($regioncode == 602){
                      $InvoiceTaxObj->local_gst_5_per = round(array_sum($totaltaxableamount_5), 2); 
                      $InvoiceTaxObj->local_gst_12_per = array_sum($totaltaxableamount_12); 
                    }
                    else{
                      $InvoiceTaxObj->central_sale_igst_12 = array_sum($totaltaxableamount_12);
                      $InvoiceTaxObj->central_sale_igst_5 = round(array_sum($totaltaxableamount_5), 2); 
                    }
                  }
                  else
                  {
                    $InvoiceTaxObj->local_gst_5_per = 0; 
                    $InvoiceTaxObj->local_gst_12_per = 0; 
                    $InvoiceTaxObj->central_sale_igst_12 = 0;
                    $InvoiceTaxObj->central_sale_igst_5 = 0; 
                  }

                  $InvoiceTaxObj->sgst_2_5_per = round(array_sum($totalsgstamount_5), 2); 
                  $InvoiceTaxObj->cgst_2_5_per = number_format(array_sum($totalcgstamount_5), 2); 
                  $InvoiceTaxObj->sgst_6_per = round(array_sum($totalsgstamount_12), 2); 
                  $InvoiceTaxObj->cgst_6_per = round(array_sum($totalcgstamount_12), 2); 
                   
                  $InvoiceTaxObj->igst_12 = round($totaligst_sum, 2);
                  $InvoiceTaxObj->igst_5 = round(array_sum($totaligstamount_5), 2); 
                  $InvoiceTaxObj->freight = round($totalshippingwithout, 2); 
                  $InvoiceTaxObj->discount = round($orderDetail['discount_total'], 2); 
                  $InvoiceTaxObj->narration = "BEING GOODS SOLD TO ORDER NO. ".$orderDetail['sales_inc_id']." " .$orderDetail['address']['shipping']['name'];   
                  $InvoiceTaxObj->locations = $orderDetail['address']['billing']['state']; 
                  $InvoiceTaxObj->country  = $country ; 
                  $InvoiceTaxObj->store_credit  = $orderDetail['customercredit'] ; 
                  $InvoiceTaxObj->currency_type  = $orderDetail['curency_code'] ; 

                  $InvoiceTaxObj-> save();
 */
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
                    $col1 = 5; $col2 = 10;
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
                 <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'">CUSTOMER CREDIT USE </td>
                 <td class="dataTableContent" valign="top" align="" colspan="'.$col2.'">'.$currency.number_format($orderDetail['customercredit'],2).'</td>
                </tr>

              <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'">Total Amount</td>
                <td class="dataTableContent" valign="top" align="" colspan="'.$col2.'">'.$currency.number_format($orderDetail['grand_total'],2).'</td>
              </tr>


            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'">Amount paid / collected from customer (In figure)</td>
                <td class="dataTableContent" valign="top" align="" colspan="'.$col2.'">'.$currency.number_format($orderDetail['grand_total'], 2).'</td>
            </tr>
 
            <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'">Total Amount (In Word) </td>
                <td class="dataTableContent" valign="top" align="" colspan="'.$col2.'">'.Utility::numberTowords($total_sum, $orderDetail['curency_code']).'</td>
            </tr>

           <!-- <tr class="dataTableRow">
                <td class="dataTableContent" valign="top" align="" colspan="'.$col1.'">Amount of Tax subject to Reverse Charges</td>
                <td class="dataTableContent" valign="top" align="" colspan="'.$col2.'"></td>
                
            </tr>
           -->
              
           </tbody>
          </table>

        <div style="width: 600px; margin: auto; margin-bottom: 20px;">';
          if($orderDetail['curency_code'] == 'INR')
            {
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
          }
          $htmldata[$jtdOrderId] .= '<div id="footer">
                <table style="width:100%;font-size:8px; ">                
                      <tr>
                        <td colspan="2">
                           <br><center><strong>DECLARATION</strong> </center><br>
                           <center> 
                           We declare that this credit note shows actual price of the goods and that all particulars are true and correct.
                             </center>
                        </td>
                    </tr>
                    <tr class="gray-bg border-tt">
                        <td colspan="2" class="border-top-thick-2 border-bottom-thick-3"><center>THIS IS A COMPUTER GENERATED CREDIT MEMO AND DOES NOT REQUIRE SIGNATURE</center></td>
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
            //print_r($htmldata);
            //exit;
            foreach ($htmldata as $jtdOrderID => $data) {
                $invoiceDate = $crArray[$jtdOrderID]['created_at'];
                $invoiceno = $crArray[$jtdOrderID]['invoice_no'];
                $increment_id = $crArray[$jtdOrderID]['cr_inc_id'];
                $country = $crArray[$jtdOrderID]['curency_code'];
                $year= date("Y",strtotime($invoiceDate));
                $month = Date("M", strtotime($invoiceDate));
                
                $datanew .= $data."<div class='page-break'></div>";

                $pdf = PDF::loadHtml($data);
                $folder = "$country/$year/$month/";
                $imageName =  $invoiceno.'_'.$increment_id.'_'.$crArray[$jtdOrderID]['order_inc_id'].".pdf";
                $fileName = $folder.$imageName;
                

                  $exists = Storage::disk('s3cm')->exists($fileName);
                  
                  if($exists){
                    for ($i=1; $i <= 5; $i++) {
                      $imageName =  $invoiceno.'_'.$increment_id."_"."_".$crArray[$jtdOrderID]['order_inc_id']."_".$i.".pdf";
                      $fileName = $folder.$imageName;
                      $exists = Storage::disk('s3cm')->exists($fileName);
                      if(!$exists) {
                        break;
                      }
                    }
                    
                  }

                JtdInvoice::setCrFilename($increment_id,$imageName,$folder); 
                Storage::disk('s3cm')->put($fileName,  $pdf->output()); 
                /*dd($jtdOrderID);
                $pdf->save("/home/farida/Backend/storage/invoicedemo_".$jtdOrderID.".pdf");
                dd(Storage::disk());*/
                
                  echo Storage::disk('s3cm')->url($fileName)."\n";
                  Log::info("Credit Memo Created $fileName");
            }
            unset($htmldata);
          }
         unset($crArray);
        }else
          {
            echo "Credit Memo not created";
            return 'false';
           }
    }
    public function getCrdetail(){
	    $cdate = date("Y-m-d H:i:s");
	    $date = strtotime($cdate); 
	    $date = strtotime("-1 day", $date);
	    $date = strtotime("+1 second", $date);
	    $sdate = date('Y-m-d H:i:s', $date);
	    $data = SalesFlatCreditMemo::getCrMemoDetail($sdate,$cdate);
	    
	    //$data = JtdInvoice::jtdDetail(); get latest invoice detail
    
    return $data;
   }

   public function revertCrmemo($orderID =""){
    
    if($orderID!= "")
       {
        $salesorderupdate = SalesFlatOrder::where('entity_id', $orderID) 
          ->update(['status' =>  'order_confirm', 'state' => 'new', 'base_discount_refunded'=> Null
                    , 'base_shipping_refunded' => Null ,'base_shipping_tax_refunded' => Null , 'base_subtotal_refunded' => Null , 'base_tax_refunded' => Null ,  'base_total_refunded' => Null ,'base_total_offline_refunded'=>null, 'base_total_online_refunded'=> Null,  'discount_refunded' => Null, 'shipping_refunded' => Null, 'shipping_tax_refunded' => Null,'subtotal_refunded' => Null,'tax_refunded' => Null,'total_refunded'=> Null]);

         $orderItemUpdate= SalesFlatOrderItem::where('order_id', $orderID) 
              ->update(['qty_refunded' =>  0, 'tax_refunded' => Null , 'hidden_tax_refunded' => Null, 'base_hidden_tax_refunded'=> null, 'amount_refunded' =>0 , 'base_amount_refunded'=> 0 ,'base_tax_refunded'=> null, 'discount_refunded'=>null, 'base_discount_refunded'=>null ]);  
         
        
          $orderGrid = SalesFlatOrderGrid::where('entity_id', $orderID) 
                       ->update(['status'=>'delivered']);
        
          $creditmemoupdate= SalesFlatCreditMemoItem::join('sales_flat_creditmemo', 'sales_flat_creditmemo.entity_id', "=", 'sales_flat_creditmemo_item.parent_id' )
           ->where('sales_flat_creditmemo.order_id',$orderID)
           ->update(['sales_flat_creditmemo_item.base_price'=> 0,
                     'sales_flat_creditmemo_item.tax_amount' => 0, 
                     'sales_flat_creditmemo_item.base_row_total' => 0, 
                     'sales_flat_creditmemo_item.discount_amount' => 0,
                     'sales_flat_creditmemo_item.row_total' => 0, 
                     'sales_flat_creditmemo_item.base_discount_amount' => 0,
                     'sales_flat_creditmemo_item.price_incl_tax' => 0, 
                     'sales_flat_creditmemo_item.base_tax_amount' => 0,
                     'sales_flat_creditmemo_item.base_price_incl_tax' => 0, 
                     'sales_flat_creditmemo_item.qty' => 0,
                     'sales_flat_creditmemo_item.base_cost' => 0,
                     'sales_flat_creditmemo_item.price' => 0,
                     'sales_flat_creditmemo_item.base_row_total_incl_tax' => 0,
                     'sales_flat_creditmemo_item.row_total_incl_tax' => 0, 
                     'sales_flat_creditmemo_item.hidden_tax_amount' => 0, 
                     'sales_flat_creditmemo_item.base_hidden_tax_amount' => 0]);
           
           $creditmemoitemupdate = SalesFlatCreditMemo::where('order_id',$orderID)->update(['base_discount_amount'=>0 ,'base_shipping_tax_amount'=>0, 'base_to_order_rate'=>0, 'grand_total'=>0, 'base_subtotal_incl_tax'=>0, 'shipping_amount'=>0, 'subtotal_incl_tax'=>0, 'base_shipping_amount'=> 0, 'base_subtotal'=> 0, 'discount_amount'=> 0, 'subtotal'=> 0, 'base_grand_total'=> 0, 'base_tax_amount'=> 0, 'shipping_tax_amount'=> 0, 'tax_amount'=> 0, 'base_hidden_tax_amount'=> 0, 'shipping_incl_tax'=>0, 'base_shipping_incl_tax'=>0, 'customercredit_discount'=>0 , 'base_customercredit_discount'=>0 , 'fee_amount'=>0 , 'base_fee_amount'=>0 ]);
         
          $creditmemogridupdate = SalesFlatCreditMemoGrid::join('sales_flat_creditmemo', 'sales_flat_creditmemo.entity_id', "=", 'sales_flat_creditmemo_grid.entity_id')
           ->where('sales_flat_creditmemo.order_id',$orderID)
           ->update(['sales_flat_creditmemo_grid.grand_total'=>0 , 'sales_flat_creditmemo_grid.base_grand_total' => 0]);
          echo "updated Creditmemo ";
       }
       else 
       echo  "not pass order ID";
   }
}
