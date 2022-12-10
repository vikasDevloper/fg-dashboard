<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Classes\Helpers\GoogleAnalyticsService;
use Dashboard\Data\Models\CataloginventoryStockItem;

use Dashboard\Data\Models\CatalogProductEntity;

use Dashboard\Data\Models\CoreEmailQueue;
use Dashboard\Data\Models\EmailUpdates;
use Dashboard\Data\Models\NotificationLog;
use Dashboard\Data\Models\NotificationSend;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SalesFlatOrderItem;
use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Data\Models\UtmCampaign;
use Dashboard\Http\Controllers\Controller;

use Excel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketingDashboardController extends Controller {

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
	// 	if ($userType != 'A' && $userType != 'SD' && $userType != 'MK') {
	// 		if ($userType === 'ACH') {//Accounts Head
	// 			return redirect('/accounts-dashboard');
	// 		} elseif ($userType === 'AC') {//Accounts
	// 			return redirect('/accounts-dashboard');
	// 		} elseif ($userType === 'CXH') {//Customer Support Head
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

	public function downloadExcel($type) {
		$data = UtmCampaign::sesssionCreatedBySource();

		return Excel::create('users', function ($excel) use ($data) {
				$excel->sheet('mySheet', function ($sheet) use ($data) {
						$sheet->fromArray($data);
					});
			})->download($type);
	}

	public static function show(Request $request) {

		// MarketingDashboardController::allowAccess();
		$userType = Auth::user()->user_type;
		if ($userType != 'A' && $userType != 'SD' && $userType != 'MK' && $userType != 'ACH') {
			if ($userType === 'AC') {//Accounts
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'CXH') {//Customer Support Head
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

		$today             = date('Y-m-d');
		$data['startDate'] = $today;
		$data['endDate']   = $today;

		if ($request->has('start-date')) {
			$data['startDate'] = $request->input('start-date');
			$data['endDate']   = $request->input('end-date');
		}

		$data['unDeliveredOrder'] = SalesFlatOrder::unDeliveredOrders($data);

		$coreMailsQueue        = CoreEmailQueue::getTotalMails($today);
		$notificationSmsToday  = NotificationSend::getAllNotificationSmsSent($today);
		$smsUpdatesToday       = SmsUpdates::getDailySmsSent($today);
		$notificationMailToday = NotificationSend::getAllNotificationMailsSent($today);
		$emailUpdatesToday     = EmailUpdates::getDailyMailsSent($today);

		$data['notificationLog'] = NotificationLog::getNotificationLog($data);

		$data['mailsSentToday'] = array_merge($notificationMailToday, $emailUpdatesToday, $coreMailsQueue);
		$data['smsSentToday']   = array_merge($notificationSmsToday, $smsUpdatesToday);

		$data['analyticsData']                     = GoogleAnalyticsService::getAnalyticsData($data);
		$data['analyticsPageviewsData']            = GoogleAnalyticsService::getPageviewsData($data);
		$data['analyticsData']['transactionsData'] = GoogleAnalyticsService::costPerTransaction($data);
		$data['customers']                         = SalesFlatOrder::customersCount($data);
		$data['sessionBySource']                   = UtmCampaign::sesssionCreatedBySource($data);
		$data['utmConversions']                    = UtmCampaign::getUtmConversion($data);
		$data['revenueByCities']                   = SalesFlatOrder::revenueByCities($data);

		$data['productsNotSelling'] = CatalogProductEntity::productsNotSelling();
		$data['productsQuantities'] = CataloginventoryStockItem::getTotalProductsQuantities();
        //print_r($data['productsNotSelling']);
		return view('dashboard.marketing')->with('data', $data);
	}

	static function cbSort($a, $b) {

		return strnatcmp($b['total'], $a['total']);
	}

	static function channelCostRevenueRoi(Request $request) {

		// MarketingDashboardController::allowAccess();

		$userType = Auth::user()->user_type;
		if ($userType != 'A' && $userType != 'SD' && $userType != 'MK') {
			if ($userType === 'ACH') {//Accounts Head
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'AC') {//Accounts
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'CXH') {//Customer Support Head
				return redirect('/cx-dashboard');
			} elseif ($userType === 'CX') {//Customer Support
				return redirect('/cx-dashboard');
			} elseif ($userType === 'WHH') {//Warehouse Head
				return redirect('/logistics-dashboard');
			} elseif ($userType === 'WH') {//Warehouse
				return redirect('/logistics-dashboard');
			} else {
				echo '<center><strong>You are not allowed for this.</strong></center>';
			}
		}

		$today             = date('Y-m-d');
		$data['startDate'] = $today;
		$data['endDate']   = $today;

		if ($request->has('start-date')) {
			$data['startDate'] = $request->input('start-date');
			$data['endDate']   = $request->input('end-date');
		}
		// // echo 1;
		// // exit;
		// // Initialize a new Session and instantiate an Api object
		// //Api::init('253278541424278', '93fd1ef03522c49dff7b7d61a8a71e6a', 'EAADmWwF9ppYBAMp9qQCGaJn5ZAs525jdp4qYnBpi6G5P39uzSn1qm8s6LlNjoaNmE2T2d0NuAGe600ga7jxr7Vzu1ZCZCesmZAWN5MFjeWjiYDF8TseLk2Bv3LMaeeLEdl0P13FE5C7nTblZC0rfaCHhE7Q7ZAxcTQUkStQItNxZAGAhSsuZAwRpOaJRQpZBptHKKz0HkucoGPCqQIZAlih0cZA6TM0D82OSx0ZD');

		// Api::init('253278541424278', '93fd1ef03522c49dff7b7d61a8a71e6a', 'EAADmWwF9ppYBAJPgRxlm00x7vY0ZCA1U6BWYCZCj5GOGMiXwVUZCohdLGZA4BTYR68hr0CxwEyKzHu6TIahd11KtrWCJDeZCtG8GZBo8pBvELw3LR1F9LQWXjKP5UbNuf5Dk6xeSXJt6lW3M2xBsfzWoJoVxay782SZBwrZCpLzfzQZDZD');

		// // The Api object is now available trough singleton
		// $api = Api::instance();

		// $account_id = 'act_381753992562760';
		// //$account_id = 'act_113820229072315';

		// $account = new AdAccount($account_id);

		// // $adsets = $account->getAdSets(array(
		// // 		AdSetFields::NAME,
		// // 		AdSetFields::CONFIGURED_STATUS,
		// // 		AdSetFields::EFFECTIVE_STATUS,
		// // 	));

		// // foreach ($adsets as $adset) {
		// // 	echo $adset->{AdSetFields::NAME} .PHP_EOL;
		// // }

		// // $users = $account->getUsers();

		// // foreach ($users as $user) {
		// // 	echo $user->{UserFields::ID} .PHP_EOL;
		// // }

		// $fields = array(
		// 	AdAccountFields::ACCOUNT_ID,
		// 	AdAccountFields::ACCOUNT_STATUS,
		// 	// AdAccountFields::AD_ACCOUNT_CREATION_REQUEST,
		// 	AdAccountFields::AGE,
		// 	AdAccountFields::BALANCE,
		// 	AdAccountFields::BUSINESS,
		// 	AdAccountFields::ID,
		// 	AdAccountFields::NAME,
		// 	AdAccountFields::AMOUNT_SPENT,
		// 	AdAccountFields::OWNER,

		// );
		// // Dump TOS Accepted info.
		// //var_dump($account->{AdAccountFields::TOS_ACCEPTED});
		// echo '<pre>';
		// print_r($account->read($fields));
		// // $users   = $account->getUsers();
		// // // echo '<pre>';
		// // // print_r($users);
		// // // exit;

		// // // foreach ($users as $user) {
		// // // 	echo $user->{UserFields::ID} .PHP_EOL;
		// // // }

		// // $account->read(array(
		// // 		AdAccountFields::TOS_ACCEPTED,
		// // 	));

		// // // Dump TOS Accepted info.
		// // var_dump($account->{AdAccountFields::TOS_ACCEPTED});
		// // $account->read($fields);

		// // FacebookAds::init('EAADmWwF9ppYBALq6Qs0IuRAXhIcV1CdDKPmdKeF4SrPOBG1ZCBB9AW2RrFUiH5rtPrdR9wDuTMFQdqKC5isnPkv1AINEiZAN5tX0WpT9xgZAAFUPPIVpvofQTbmqIDnfsCLYktXNyrcyczrafjGPp8W4f59ChgPs362VMQHUwZDZD');
		// // //exit;
		// // print_r(FacebookAds::adAccounts()->all());
		// exit;

		//dd($ads);
		$data['smsrevenue']   = NotificationLog::getSmsNotificationLog($data);
		$data['emailrevenue'] = NotificationLog::getEmailNotificationLog($data);

		return view('dashboard.channelCostRevenue')->with('data', $data);
	}

	static function productSoldByColorPrice(Request $request) {

		// MarketingDashboardController::allowAccess();
		$userType = Auth::user()->user_type;
		if ($userType != 'A' && $userType != 'SD' && $userType != 'MK') {
			if ($userType === 'ACH') {//Accounts Head
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'AC') {//Accounts
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'CXH') {//Customer Support Head
				return redirect('/cx-dashboard');
			} elseif ($userType === 'CX') {//Customer Support
				return redirect('/cx-dashboard');
			} elseif ($userType === 'WHH') {//Warehouse Head
				return redirect('/logistics-dashboard');
			} elseif ($userType === 'WH') {//Warehouse
				return redirect('/logistics-dashboard');
			} else {
				echo '<center><strong>You are not allowed for this.</strong></center>';
			}
		}

		$today             = date('Y-m-d');
		$data['startDate'] = $today;
		$data['endDate']   = $today;

		if ($request->has('start-date')) {
			$data['startDate'] = $request->input('start-date');
			$data['endDate']   = $request->input('end-date');
		}

		$data['saleByColors'] = SalesFlatOrderItem::productSoldByColor($data);
		$data['saleByPrice']  = SalesFlatOrderItem::productSoldByPrice($data);

		return view('dashboard.productSoldByColorPrice')->with('data', $data);
	}

	static function facebookReport(Request $request) {

		// $today             = date('Y-m-d');
		// $data['startDate'] = $today;
		// $data['endDate']   = $today;

		// if ($request->has('start-date')) {
		// 	$data['startDate'] = $request->input('start-date');
		// 	$data['endDate']   = $request->input('end-date');
		// }
	}
	public function getSmsEmailCount(){
		//dd(SmsUpdates::limit(10)->get());
		$data = array();
    	$today             = date('Y-m-d');
		$data['startDate'] = $today;
		$data['endDate']   = $today;

		if (request()->has('start-date') && request()->has('end-date')) {
			$data['startDate'] = request()->get('start-date');
			$data['endDate']   = request()->get('end-date');
		}

		$data['mailsSentToday'] = NotificationLog::getEmailUtmLog($data);

		$data['smsSentToday']   = NotificationLog::getSmsUTMLog($data);
		//$data['unDeliveredOrder'] = SalesFlatOrder::unDeliveredOrders($data);

		// $coreMailsQueue        = CoreEmailQueue::getTotalMails($today);
		// $notificationSmsToday  = NotificationSend::getAllNotificationSmsSent($today);
		// $smsUpdatesToday       = SmsUpdates::getDailySmsSent($today);
		// $notificationMailToday = NotificationSend::getAllNotificationMailsSent($today);
		// $emailUpdatesToday     = EmailUpdates::getDailyMailsSent($today);

		// $data['notificationLog'] = NotificationLog::getNotificationLog($data);

		// $data['mailsSentToday'] = array_merge($notificationMailToday, $emailUpdatesToday, $coreMailsQueue);
		// $data['smsSentToday']   = array_merge($notificationSmsToday, $smsUpdatesToday);

		return view('dashboard.smsemail')->with('data', $data);
    }
}
