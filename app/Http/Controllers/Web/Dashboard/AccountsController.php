<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Classes\Helpers\GoogleAnalyticsService;
use Dashboard\Data\Models\CustomerEntityDecimal;
use Dashboard\Data\Models\OfflineOrderDetails;
use Dashboard\Data\Models\SalesFlatInvoice;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SalesFlatOrderItem;
use Dashboard\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountsController extends Controller {
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
	// 	if ($userType != 'A'
	// 		 && $userType != 'SD'
	// 		 && $userType != 'ACH'
	// 		 && $userType != 'AC') {

	// 		if ($userType === 'CXH') {//Customer Support Head
	// 			return redirect('/cx-dashboard');
	// 		} elseif ($userType === 'CX') {//Customer Support
	// 			return redirect('/cx-dashboard');
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

		// AccountsController::allowAccess();

		$userType = Auth::user()->user_type;
		if ($userType != 'A'
			 && $userType != 'SD'
			 && $userType != 'ACH'
			 && $userType != 'AC') {

			if ($userType === 'CXH') {//Customer Support Head
				return redirect('/cx-dashboard');
			} elseif ($userType === 'CX') {//Customer Support
				return redirect('/cx-dashboard');
			} elseif ($userType === 'WHH') {//Warehouse Head
				return redirect('/logistics-dashboard');
			} elseif ($userType === 'WH') {//Warehouse
				return redirect('/logistics-dashboard');
			} else {
				echo '<center><strong>You are not allowed for this.</strong></center>';
				exit;
			}
		}

		$data['startDate'] = date('Y-m-d');
		$data['endDate']   = date('Y-m-d');

		if ($request->has('start-date')) {
			$data['startDate'] = $request->input('start-date');
			$data['endDate']   = $request->input('end-date');
		}

		//print_r($data);
		//exit;

		$data['unDeliveredOrder']                  = SalesFlatOrder::unDeliveredOrders($data);
		$data['analyticsData']                     = GoogleAnalyticsService::getAnalyticsData($data);
		$data['analyticsData']['transactionsData'] = GoogleAnalyticsService::costPerTransaction($data);
		$data['customers']                         = SalesFlatOrder::customersCount($data);
		$data['offlineDetails']                    = OfflineOrderDetails::getOfflineRevenue($data);

		$data['deliveryByPaymentMethods'] = SalesFlatOrder::deliveryByPaymentMethods($data);
		$data['ordersByPaymentMethods']   = SalesFlatOrder::ordersByPaymentMethods($data);

		$data['totalStoreCredit']           = CustomerEntityDecimal::getTotalCreditBalance();
		$data['soldQtyByCategory']          = SalesFlatOrderItem::soldProductQtyByCategory($data);
		$data['returnedQtyByCategory']      = SalesFlatOrderItem::returnedProductQtyByCategory($data);
		$data['totalSoldTaxByState']        = SalesFlatOrderItem::totalSoldTaxByState($data);
		$data['totalRefundedTaxByState']    = SalesFlatOrderItem::totalRefundedTaxByState($data);
		$data['deliveredByInvoicedCod']     = SalesFlatInvoice::deliveredByInvoicedDateCod($data);
		$data['deliveredByInvoicedPrepaid'] = SalesFlatInvoice::deliveredByInvoicedDatePrepaid($data);

		return view('dashboard.accounts')->with('data', $data);
	}
}
