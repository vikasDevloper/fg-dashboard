<?php

namespace Dashboard\Http\Controllers\Web;

use Dashboard\Data\Models\SalesFlatOrderAddress;
use Dashboard\Http\Controllers\Controller;
use Dashboard\Http\Requests\Request;

class CleanCityOperationController extends Controller {
	//
	public static function show() {
		$data           = array();
		$data['cities'] = SalesFlatOrderAddress::getUniqueCities();
		return view('cleanCityOperation')->with('data', $data);
	}

	public static function cleanCityOperation(Request $request) {
		$requestParameters = $request->all();
		if (SalesFlatOrderAddress::updateCityName($requestParameters)) {
			return redirect()->back()->with('message', 'Thanks for updating me :)');
		} else {
			return redirect()->back()->with('error', 'Something is wrong! :(');
		}
	}
}
