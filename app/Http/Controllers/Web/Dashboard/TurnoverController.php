<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;
use Dashboard\Http\Controllers\Controller;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SetTurnoverTarget;
use Dashboard\Data\Models\SalesFlatOrderItem;
use Dashboard\Classes\Helpers\Utility;
use Illuminate\Support\Facades\Auth;


class TurnoverController extends Controller
{
        public static function show(Request $request) {
     	//Utility::get_access_token();
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
			
     	$data  = self::getOnlineTurnover($data);
     	$data  = self::getMonthlyRevenue($data);
     	print_r($data);
        return view('dashboard.turnoverStatus')->with('data', $data);

     }

     public static function dailyRevenue(Request $request) {
     	date_default_timezone_set('UTC');
         $id = Auth::id();
 		 $userType = Auth::user()->user_type;

		if ($userType != 'A' && $userType != 'SD' && $userType != 'ACH') {

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
			}
		}

		$data['startDate'] = date('Y-m-d');
		$data['endDate']   = date('Y-m-d');

		if ($request->has('start-date')) {
			$data['startDate'] = $request->input('start-date');
			$data['endDate']   = $request->input('end-date');
		}
			
     	$data['dailyTurnover']  = self::dailyOnlineTurnover($data);
     	$data['monthlyTurnover']  = self::dailyMonthlyRevenue();
     	$data['monthlyqty']  	  = SalesFlatOrderItem::dailyMonthlyqty($data);
     	$data['dailyTurnoverOffline']  = self::dailyOflineTurnover();
     	$data['monthlyTurnoverOffline']  = self::offlineMonthlyRevenue();
     	$data['monthlyTurnoverOffline'] = array_combine(array_column($data['monthlyTurnoverOffline'], 'month'), $data['monthlyTurnoverOffline']);
     	$data['getTuroverTarget'] =  SetTurnoverTarget::getTurnoverTarget();
     	$data['allmonths'] = Utility::getFinacialMonth();
     	$data['financialYear'] = Utility::getFinancialYear();
        
      	  //echo "<pre>";   print_r($data);
      	 // exit;
      	// $data['']
        return view('dashboard.dailyTurnoverStatus')->with('data', $data);

     }

    public static function dailyOnlineTurnover($data){
 
 	    $result = Utility::apiCall('dailyOnlineTurnover', 'POST', $data);
        
        return $result;
	}

	public static function dailyMonthlyRevenue(){

		$result = Utility::apiCall('dailyMonthlyRevenue', 'POST', '');
        
        return $result;
	}

    public static function dailyOflineTurnover(){
 
 	    $result = Utility::apiCall('dailyOflineTurnover', 'POST', '');
        
        return $result[0];
	}

	public static function offlineMonthlyRevenue(){

		$result = Utility::apiCall('offlineMonthlyRevenue', 'POST', '');
        
        return $result;
	}



    public static function getOnlineTurnover($data){
 
 	    $result = Utility::apiCall('getOnlineTurnover', 'POST', $data);
        
        return $result;
	}

	public static function getMonthlyRevenue($data){
		$result = Utility::apiCall('getMonthlyRevenue', 'POST', $data);
        
        return $result;
	}
	public static function setTarget(){
		$data = SetTurnoverTarget::setTurnoverTarget($_GET['formdata'], $_GET['state']);
		//return $data ;
		return $_GET['formdata'].$data;
	}
    public static function setTargetYearly(){
		$data = SetTurnoverTarget::setTurnoverTargetYearly($_GET['total'], $_GET['state']);
		//return $data ;
		return $_GET['total'].$data;
	}
	
	public static function getMonthlyData(){
		
		//$_GET['month'] = "April";
		$data = array($_GET['month']);
		$result = Utility::apiCall('perDayRevenue', 'POST', $data);
         
        $graphData['name'] = $_GET['month'];
        $graphData['type'] = "spline";
        $graphData['xAxis'] = 1;
        $graphData['data'] = $result;
        /*echo "<pre>"; print_r($graphData);
		if($_GET['month'] == "April") 
	        $response = array('name' => 'April', 'data'=>array(array('Toyota', 1), array('Volkswagen', 2), array('Opel', 5)));*/
	  
			return  json_encode($graphData);
     	
	}
	

}
