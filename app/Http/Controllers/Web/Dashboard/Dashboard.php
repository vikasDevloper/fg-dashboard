<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Classes\Helpers\GoogleAnalyticsService;

use Dashboard\Data\Models\CustomerEntity;

use Dashboard\Data\Models\EmailUpdates;

use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\OfflineCustomerEntity;
use Dashboard\Data\Models\OfflineOrderDetails;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SalesFlatOrderItem;
use Dashboard\Data\Models\SalesFlatOrderAddress;
use Dashboard\Data\Models\ProductManufacturingInfo;

use Dashboard\Data\Models\SmsUpdates;

use Dashboard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp;
use Dashboard\Http\Controllers\ApiController;
use Dashboard\Classes\Helpers\Utility;

class Dashboard extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	protected $redirectTo = '/';

	public function __construct() {

		$this->middleware('auth');

	}

	/**
	 * Show the dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */

  

	public static function show(Request $request) {

		set_time_limit(0);

		if(empty(session('accessToken'))){
			 Utility::get_access_token();
        }

        //$data['getExibitionDetails']  = self::getExibitionDetails();

		$id = Auth::id();

		//Dashboard::allowAccess();
		$userType = Auth::user()->user_type;

		if ($userType != 'A' && $userType != 'SD') {

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
			} elseif ($userType === 'MH') {//Merchendiser
				return redirect('/sales-status');
			} elseif ($userType === 'LC') {//Merchendiser
				return redirect('/fg-short-url');
			}elseif ($userType === 'MK') {//Merchendiser
				return redirect('/marketing-dashboard');
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

		$salesOrderObj = new SalesFlatOrder();

		$data['deliveryByPaymentMethods'] = SalesFlatOrder::deliveryByPaymentMethods($data);
		$data['ordersByPaymentMethods']   = SalesFlatOrder::ordersByPaymentMethods($data);
		$data['unDeliveredOrder']         = SalesFlatOrder::unDeliveredOrders($data);

		//$data['revenueByCities']                   = SalesFlatOrder::revenueByCities($data);
		$data['customers']    = SalesFlatOrder::customersCount($data);
		$data['ordersByTime'] = SalesFlatOrder::ordersByTime($data);
		$data['ordersSold']   = $salesOrderObj->ordersSold($data);
		//$data['utmConversions']                    = UtmCampaign::getUtmConversion($data);
		$data['sizesTotal']  = $salesOrderObj->sizesSold;
		$data['subscribers'] = NewsletterSubscriber::getSubscribers($data);
		// $data['lastestSearchTerms']                = CatalogsearchQuery::getSearchTerms(5);
		// $data['popularSearchTerms']                = CatalogsearchQuery::getSearchTermsByPopularity(5);
		$data['analyticsData']                     = GoogleAnalyticsService::getAnalyticsData($data);
		$data['analyticsPageviewsData']            = GoogleAnalyticsService::getPageviewsData($data);
		$data['analyticsData']['transactionsData'] = GoogleAnalyticsService::costPerTransaction($data);
		$data['last5Orders']                       = SalesFlatOrder::last5Orders($data);
		$data['top5Orders']                        = SalesFlatOrder::top5Orders($data);
		$data['onlineCustomers']                   = CustomerEntity::getOnlineCustomers();
		$data['offlineCustomers']                  = OfflineCustomerEntity::getOfflineCustomers();
		$data['allSubscribers']                    = NewsletterSubscriber::getTotalSubscribers();
		$data['allSubscribersMobile']                    = NewsletterSubscriber::getTotalSubscribersMobile();
		$data['offlineDetails']                    = OfflineOrderDetails::getOfflineRevenue($data);
		$data['globalCountry']                     = SalesFlatOrder::getGlobalCountry($data);
		$data['saleCategory']                      = SalesFlatOrderItem::saleByCategory($data);
 
		if(Auth::user()->email != 'sahil@faridagupta.com'){
		
		$data['fgstealsCategory']                  = SalesFlatOrder::getProductFGSteals($data);
	    }
	    else{
	    	$data['saleCategory']                      = array();
		    $data['fgstealsCategory']                  = array();
	    
	    }
 
        //Data for Api Test
       
		  
		    
		   // print_r($data);
		  
		//       echo $data['offlineDetails']['totalAmount'];
		//       echo $data['offlineDetails']['totalQty'];
		    //   exit;

		if (!empty($data['ordersSold']['items'])) {
			usort($data['ordersSold']['items'], array("self", "cbSort"));
		}

		//echo "<pre>";
		//print_r($data['ordersSold']['items']);

		return view('dashboard.dashboard')->with('data', $data);
	}
    
	public static function showAllMails() {
		$data                 = array();
		$data['showAllMails'] = EmailUpdates::getAllMails();
		return view('dashboard.showAllMails')->with('data', $data);
	}

	public static function showAllSms() {
		$data               = array();
		$data['showAllSms'] = SmsUpdates::getAllSms();
		return view('dashboard.showAllSms')->with('data', $data);
	}

	static function cbSort($a, $b) {

		return strnatcmp($b['total'], $a['total']);
	}

	public static function getExibitionDetails(){
	

	    $result=Utility::apiCall('getexibition', 'GET');
        print_r($result);
        return 'test exibition';
	}

}
