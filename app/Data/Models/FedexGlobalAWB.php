<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class FedexGlobalAWB extends Model
{
    protected $table = 'fedex_global_awb';

    static function getAwbNo($orderID) {

    	$awb = FedexGlobalAWB::select('TrackingNumber')
    	                       ->where("order_id","=",$orderID)
    	                       ->get();
    	$data ='';
     	if (!empty($awb)) {
			foreach ($awb as $value) {
				$data = $value['TrackingNumber'];
 
			}
		}
		return $data;                      
    }
}
