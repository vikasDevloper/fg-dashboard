<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class JtdInvoice extends Model
{
    protected $table = 'jtd_invoice';
    public $timestamps = false;

 public function orderDetail()
    {
        return $this->hasOne('Dashboard\Data\Models\SalesFlatOrder', 'entity_id', 'order_id');
    }

 static function jtdDetail(){

 	$orderNo = JtdInvoice::select('entity_id','order_id','invoice_no')
 	           ->whereRaw('filename is NULL')
       		   ->orderBy('entity_id', 'DESC')
       		   ->limit(1)->get(); 
      		   $data = array();
 
		if (!empty($orderNo)) {
			foreach ($orderNo as $value) {
				$data[] = $value->toArray();

			}
		}
    //dd($data);
		return $data;
  }   

  static function setFilename($orderID,$imageName,$folder){

  	$orderNo = JtdInvoice::where('order_id', $orderID)
          ->update(['filename' =>  $imageName, 'bucket_path' => $folder]);
          
  }
   static function setCrFilename($cr_inc_id,$imageName,$folder){

    $orderNo = JtdInvoice::where('magentocreditnote', $cr_inc_id)
          ->update(['cr_filename' =>  $imageName, 'cr_bucket_path' => $folder]);
          
  }
 }