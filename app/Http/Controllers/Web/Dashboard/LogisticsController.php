<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Data\Models\Picking;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SalesFlatOrderItem;

use Dashboard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogisticsController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Show the logistics dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */

	// public static function allowAccess() {
	// 	$userType = Auth::user()->user_type;
	// 	if ($userType != 'A' && $userType != 'SD' && $userType != 'CXH' && $userType != 'WH' && $userType != 'WHH') {
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

	public static function show(Request $request) {

		// LogisticsController::allowAccess();
		$userType = Auth::user()->user_type;
		if ($userType != 'A' && $userType != 'SD' && $userType != 'CXH' && $userType != 'WH' && $userType != 'WHH') {
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

		$data['startDate'] = date('Y-m-d');
		$data['endDate']   = date('Y-m-d');

		if ($request->has('start-date')) {
			$data['startDate'] = $request->input('start-date');
			$data['endDate']   = $request->input('end-date');
		}

		$salesOrderObj = new SalesFlatOrder();

		$data['deliverySla']                      = SalesFlatOrder::ordersDeliveredSLA($data);
		$data['shippingSla']                      = SalesFlatOrder::ordersShippingSLA($data);
		$data['ordersByPaymentMethods']           = SalesFlatOrder::ordersByPaymentMethods($data);
		$data['unDeliveredOrder']                 = SalesFlatOrder::totalProccessedOrders($data);
		$data['ordersSold']                       = $salesOrderObj->ordersSold($data);
		$data['shipping']                         = SalesFlatOrder::orderByShipment($data);
		$data['saleByColors']                     = SalesFlatOrderItem::productSoldByColor($data);
		$data['saleByPrice']                      = SalesFlatOrderItem::productSoldByPrice($data);
		$data['deliveryTimelineOrderConfirm']     = SalesFlatOrder::deliveryTimelineOrderConfirm($data);
		$data['deliveryTimelineOrderShipping']    = SalesFlatOrder::deliveryTimelineOrderShipping($data);
		$data['deliveryTimelineOrderDelivered']   = SalesFlatOrder::deliveryTimelineOrderDelivered($data);
		$data['deliveryTimelineOrderPicked']      = Picking::deliveryTimelineOrderPicked($data);
		$data['deliveryTimelineOrderPacked']      = Picking::deliveryTimelineOrderPacked($data);
		$data['customers']                        = SalesFlatOrder::customersCount($data);
		$data['ordersByPaymentMethods']           = SalesFlatOrder::ordersByPaymentMethods($data);
		$data['ordersByQuantity']                 = SalesFlatOrder::ordersByQuantity($data);
		$data['averageTimeOrdersDeliveredByCity'] = SalesFlatOrder::averageTimeOrdersDeliveredByCity($data);

		// var_dump( $data['deliverySla'] );
		if (!empty($data['ordersSold']['items'])) {
			usort($data['ordersSold']['items'], array("self", "cbSort"));
		}

		return view('dashboard.logistics')->with('data', $data);
	}

	static function cbSort($a, $b) {

		return strnatcmp($b['total'], $a['total']);
	}
}
