<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class SalesFlatOrderStatusHistory extends Model
{
    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'sales_flat_order_status_history';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;

	/**
	 * Get all the orders group by status
	 *
	 * @return array
	 */
	
	static function shippingStatus($date) {



		$startDate		=	$date['startDate'];		//.' 11:59:00';
		$date_from 		= 	strtotime($startDate);
		$endDate		=	$date['endDate'];		//.' 12:01:00';
		$date_to 		= 	strtotime($endDate); 
		  
		for ($i = $date_from; $i<=$date_to; $i+=86400) {  
		    $dateArray[]  =	date("Y-m-d", strtotime("-1 day", $i));  
		}  
		//echo '<pre>';
		// print_r($dateArray);
		// exit;
		
		$endDt = '';
		
		$totaldate = count($dateArray);
		
		$j = 1;
		
		$data = array();

		for($i = 0; $i < $totaldate; $i++ ) {
			
			$startDt = $dateArray[$i] .' 11:59:00';
			
			if($totaldate == $j) {

				$endDt	=	date("Y-m-d", strtotime($startDt . ' + 1 days')) .' 11:59:00'; 

			} else {
				$endDt 	 = $dateArray[$i+1] .' 11:59:00';
			}

			$shippedStatus = SalesFlatOrderStatusHistory::whereRaw("created_at between '".$startDt."' AND '".$endDt."'")
							->whereRaw("(status = 'order_confirm')")
							->whereRaw("parent_id NOT IN (SELECT entity_id from sales_flat_order where status in ('canceled', 'holded'))")
							->selectRaw(" (SELECT HS.created_at from `sales_flat_order_status_history` AS HS WHERE HS.parent_id = sales_flat_order_status_history.parent_id AND HS.status = 'order_confirm' ORDER By created_at ASC limit 1) AS confirmDate, parent_id, status")
							->OrderBy("confirmDate", "ASC")
							->groupBy("parent_id")
							->get();
					
		//dd($shippedStatus);

			$totalcount = 0;

			if (!empty($shippedStatus)) {

				foreach ($shippedStatus as $value) {	
						// echo '<pre>';						
						// print_r($value["parent_id"] . "####" . $value["confirmDate"]);	
						if(strtotime($value["confirmDate"]) >= strtotime($startDt) && strtotime($value["confirmDate"]) <= strtotime($endDt)) {
							$totalcount += 1;	
						}

				}	

				$data[date("Y-m-d", strtotime($endDt))]['orderConfirm']	=	$totalcount;	

			}

			
		

		$alreadyShippedOrders = SalesFlatOrderStatusHistory::whereRaw("
								date(created_at) between '".date("Y-m-d", strtotime($endDt))."' AND '".date("Y-m-d", strtotime($endDt))."'")
						->whereRaw("(status = 'shipped')")
						->selectRaw("(SELECT date(HS.created_at) from `sales_flat_order_status_history` AS HS WHERE HS.parent_id = sales_flat_order_status_history.parent_id AND HS.status = 'shipped' ORDER By HS.created_at ASC limit 1) AS Shippeddate, status, count(distinct parent_id) AS totalcount")
						->groupBy('Shippeddate')
						->OrderBy('Shippeddate', 'ASC')
						->get();
		//dd($alreadyShippedOrders);				

		if (!empty($alreadyShippedOrders)) {

			foreach ($alreadyShippedOrders as $shipped) {	
			
				$data[$shipped['Shippeddate']]['orderShipped']	=  $shipped['totalcount'];								
											
			}		
				
			
		}
	
		$j++;
	
	}	

	return $data;

	}

}
