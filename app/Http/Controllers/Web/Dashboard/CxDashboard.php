<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Classes\Helpers\FreshdeskHelpers;
use Dashboard\Classes\Helpers\KnowlarityService;

use Dashboard\Data\Models\BankRefund;
use Dashboard\Data\Models\ExchangeOrders;

use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CxDashboard extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	const notAllowedUsers = array(3);
	protected $redirectTo = '/';

	public function __construct() {

		$this->middleware('auth');

	}

	// public static function allowAccess() {

	// 	$userType = Auth::user()->user_type;

	// 	if ($userType != 'A' && $userType != 'SD' && $userType != 'CXH' && $userType != 'CX') {
	// 		if ($userType === 'ACH') {//Accounts Head
	// 			return redirect('/accounts-dashboard');
	// 		} elseif ($userType === 'AC') {//Accounts
	// 			return redirect('/accounts-dashboard');
	// 		} elseif ($userType === 'WHH') {//Warehouse Head
	// 			return redirect('/logistics-dashboard');
	// 		} elseif ($userType === 'WH') {//Warehouse
	// 			return redirect('/logistics-dashboard');
	// 		} else {
	// 			echo '<center><strong>You are not allowed for this.</strong></center>';
	// 		}
	// 	}
	// }

	public static function show(Request $request) {

		// CxDashboard::allowAccess();
		$userType = Auth::user()->user_type;

		if ($userType != 'A' && $userType != 'SD' && $userType != 'CXH' && $userType != 'CX') {
			if ($userType === 'ACH') {//Accounts Head
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'AC') {//Accounts
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'WHH') {//Warehouse Head
				return redirect('/logistics-dashboard');
			} elseif ($userType === 'WH') {//Warehouse
				return redirect('/logistics-dashboard');
			} else {
				echo '<center><strong>You are not allowed for this.</strong></center>';
				exit;
			}
		}

		$data = array();

		$data['startDate'] = date('Y-m-d');
		$data['endDate']   = date('Y-m-d');

		if ($request->has('start-date')) {
			$data['startDate'] = $request->input('start-date');
			$data['endDate']   = $request->input('end-date');
		}

		$data['unDeliveredOrder'] = SalesFlatOrder::unDeliveredOrders($data);
		$data['cancelReasons']    = SalesFlatOrder::getCancelReasons($data);
		$data['ticketsStatus']    = FreshdeskHelpers::getTicketsCount($data);
		$data['bankRefunds']      = BankRefund::getBankRefund($data);
		$data['exchanges']        = ExchangeOrders::getExchangeData($data);
		$data['callLogs']         = KnowlarityService::getCallLogs($data);

		//Agents
		// $me = Freshdesk::agents()->current();
		//var_dump($data['callLogs']);

		return view('dashboard.cxdashboard')->with('data', $data);
	}
}
