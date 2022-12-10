<?php

namespace Dashboard\Http\Controllers\Api;

use Illuminate\Http\Request;
use Dashboard\Http\Controllers\Controller;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Classes\Helpers\Utility;

class TurnoverController extends Controller
{
    //
  public static function show(Request $request) {
     	Utility::get_access_token();
     	$data['getOnlineTurnover']  = self::getOnlineTurnover();
     	 
     }

	public static function getOnlineTurnover(){

		    $result = Utility::apiCall('getOnlineTurnover', 'GET');
	    print_r($result);
	    return 'apidatadacbjabkjfdah';
	}

}
