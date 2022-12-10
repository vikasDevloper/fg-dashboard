<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundReportController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	// public static function allowAccess() {
	// 	$userType = Auth::user()->user_type;
	// 	if ($userType != 'A'
	// 		 && $userType != 'SD'
	// 		 && $userType != 'CXH'
	// 		 && $userType != 'CX'
	// 		 && $userType != 'ACH'
	// 		 && $userType != 'AC') {
	// 		if ($userType === 'WHH') {//Warehouse
	// 			return redirect('/logistics-dashboard');
	// 		} elseif ($userType === 'WH') {//Warehouse
	// 			return redirect('/logistics-dashboard');
	// 		} else {
	// 			echo '<center><strong>You are not allowed for this.</strong></center>';
	// 		}
	// 	}
	// }

	public function show(Request $request) {

		// RefundReportController::allowAccess();
		$userType = Auth::user()->user_type;
		if ($userType != 'A'
			 && $userType != 'SD'
			 && $userType != 'CXH'
			 && $userType != 'CX'
			 && $userType != 'ACH'
			 && $userType != 'AC') {
			if ($userType === 'WHH') {//Warehouse
				return redirect('/logistics-dashboard');
			} elseif ($userType === 'WH') {//Warehouse
				return redirect('/logistics-dashboard');
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

		$data['RTOReport'] = SalesFlatOrder::oldNewCustomerWithRto($data);

		return view('dashboard.refund')->with('data', $data);
	}
}
