<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Data\Models\SalesFlatOrderStatusHistory;
use Dashboard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippedStatusControllerReport extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	// public static function allowAccess() {
	// 	$userType = Auth::user()->user_type;
	// 	if ($userType != 'A'
	// 		 && $userType != 'SD'
	// 		 && $userType != 'CXH'
	// 		 && $userType != 'WHH'
	// 		 && $userType != 'WH') {
	// 		if ($userType === 'ACH') {//Accounts Head
	// 			return redirect('/accounts-dashboard');
	// 		} elseif ($userType === 'AC') {//Accounts
	// 			return redirect('/accounts-dashboard');
	// 		} elseif ($userType === 'CX') {//Customer Support
	// 			return redirect('/cx-dashboard');
	// 		} else {
	// 			echo '<center><strong>You are not allowed for this.</strong></center>';
	// 		}
	// 	}
	// }

	public function show(Request $request) {

		// ShippedStatusControllerReport::allowAccess();
		$userType = Auth::user()->user_type;
		if ($userType != 'A'
			 && $userType != 'SD'
			 && $userType != 'CXH'
			 && $userType != 'WHH'
			 && $userType != 'WH') {
			if ($userType === 'ACH') {//Accounts Head
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'AC') {//Accounts
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'CX') {//Customer Support
				return redirect('/cx-dashboard');
			} else {
				echo '<center><strong>You are not allowed for this.</strong></center>';
			}
		}

		$data = array();

		$data['startDate'] = date('Y-m-d');
		$data['endDate']   = date('Y-m-d');

		if ($request->has('start-date')) {
			$data['startDate'] = $request->input('start-date');
			$data['endDate']   = $request->input('end-date');
		}

		$data['shippedStatusReport'] = SalesFlatOrderStatusHistory::shippingStatus($data);
		ksort($data['shippedStatusReport']);
		// echo '<pre>';
		// print_r($data['shippedStatusReport']);

		return view('dashboard.shippedStatusReport')->with('data', $data);
	}
}
