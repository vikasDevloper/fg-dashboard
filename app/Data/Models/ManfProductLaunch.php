<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class ManfProductLaunch extends Model
{
    protected $table = "product_manufacturing_launch";

    static function updateStatus($launchDate){

    	$updatestatus = ManfProductLaunch::whereRaw("date(`launched_date`) = '$launchDate'")
                       ->update(array('launched_status' => '1' ));
                      
    	return $updatestatus;
    }
}
