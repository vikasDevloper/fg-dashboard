<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Classes\Helpers\Utility;
use Dashboard\Data\Models\CatalogProductEntityInt;
use Dashboard\Data\Models\ProductManufacturing;
use Dashboard\Data\Models\ProductManufacturingInfo;
use Dashboard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
class BreakevenController extends Controller {
	public static function show(Request $request) {
		Utility::get_access_token();
		$data = self::productDetail();
		return json_encode($data);
	}

	public static function breakEvenAnalysis() {
		
		if(Auth::user()->email == 'sahil@faridagupta.com' ||Auth::user()->email == 'sanjay@faridagupta.com' || Auth::user()->email == 'nitya@faridagupta.com'){
		$data                = array();
		$data['style_no']    = ProductManufacturing::getStyle();
		$data['collections'] = CatalogProductEntityInt::GetProductCollectionList();
		$data['categories']  = ProductManufacturingInfo::getProductCategoryList();

		return view('dashboard.breakEvenAnalysis')->with('data', $data);
	}
	else
		return redirect('/');

	}

	public static function productDetail() {

		$result = Utility::apiCallNew($url = "productPerformance", $type = "POST", $_GET);
		return $result;
	}
}
