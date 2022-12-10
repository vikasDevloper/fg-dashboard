<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;
use Dashboard\Data\Models\SalesFlatCreditMemoGrid;
use Dashboard\Classes\Helpers\Falconide;
use Dashboard\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SalesFlatCreditMemoGridController extends Controller
{
    
	public function showinfo(){
		$falconideObj = new Falconide();	
		$mytime = '2019-03-04';//Carbon::now()->format('Y-m-d');
		$maildata = array();		
		$emailBody = SalesFlatCreditMemoGrid::rtoData($mytime);

		echo $emailBody;
		die;

		// $maildata['to'] 			= "chandan@faridagupta.com";
		// $maildata["recipient_name"] = "care";		
		// $maildata["subject"] 		= "Today Create RTO Order List";
		// $maildata["replytoid"] 		= "chandan@faridagupta.com";	
		// $maildata["from"]      		= config('mail.from.address_mailers');
		// $maildata["message"] 		= $emailBody;
		// $maildata["tag"] 			= 'rto_list';
		
		// if ($falconideObj->createMail($maildata)) {			
		// 	$status             = 1;
		// 	Log::info('RTO Order List :: Sent');
		// } else {			
		// 	$status             = 0;			
		// 	Log::error('RTO Order List :: Not Sent');
		// }

		echo $status;

		die;
	}

}
