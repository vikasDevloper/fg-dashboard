<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineOrderDetails extends Model
{

    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'offline_order_details';

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


	static function getOfflineRevenue($date) 
	{
		$from = $date['startDate'];
		$to   = $date['endDate'];
		$totalSum = array();
		$totalSum['totalAmount'] = 0;
		$totalSum['totalQty']    = 0;

		$offlineSales = OfflineOrderDetails::wherebetween('order_date', [$from, $to])
									   ->selectRaw("sum(order_total) AS totalAmount")
									   ->selectRaw("sum(order_qty) AS totalQty")->get();
		if (!empty($offlineSales)) {
			foreach ($offlineSales as $sales) {
				$total['totalAmount'] = $sales['totalAmount'];
				$total['totalQty']    = $sales['totalQty'];
				$totalSum = $total;
			}
		}							   

	    return $totalSum; 
	}
	 static function getHistPerfOne($exhibitions_id){
        $histperformance= OfflineOrderDetails::where('exhibitions_id', '=', $exhibitions_id)
				->selectRaw("order_id")
				->selectRaw("YEAR(order_date) as year")
				->selectRaw("MONTH(order_date) as month")
				->selectRaw("COUNT(DISTINCT customer_id) as footfall")
				->selectRaw("exhibitions_id")
				->selectRaw("SUM(order_total) as revenue")
				->selectRaw("(select COUNT(*) from (SELECT COUNT(customer_id)AS dupe_cnt FROM offline_order_details WHERE exhibitions_id=$exhibitions_id GROUP BY customer_id  HAVING dupe_cnt > 1) t) as old ")
	        	->selectRaw("(select COUNT(*) from (SELECT COUNT(customer_id)AS dupe_cnt FROM offline_order_details WHERE exhibitions_id=$exhibitions_id GROUP BY customer_id  HAVING dupe_cnt = 1) t) as new ")
	       		 ->selectRaw("(SELECT SUM(totalamtold) as newbuy FROM (SELECT COUNT(customer_id)AS dupe_cnt, order_total as totalamtold FROM offline_order_details where exhibitions_id=$exhibitions_id GROUP BY customer_id HAVING dupe_cnt = 1) as tabl2) as newbuy ")
	        ->selectRaw("(SELECT SUM(totalamtold) as oldbuy FROM (SELECT COUNT(customer_id)AS dupe_cnt, order_total as totalamtold FROM offline_order_details where exhibitions_id=$exhibitions_id GROUP BY customer_id HAVING dupe_cnt > 1) as tabl2) as oldbuy ")->get();
        $data = array();
        if (!empty($histperformance)) {
            foreach ($histperformance as $val) {
                $data= $val->toArray();
            }
        }      
        //print_r($data); die;  
        return $data;
    }

    static function getexhibitionDates($exhibitions_id){
    	$response = OfflineOrderDetails::where('exhibitions_id', $exhibitions_id)
    	->select('order_date')
    	->groupBy('order_date')
    	->get();
    	$data = array();
        if (!empty($response)) {
            foreach ($response as $val) {
                $data[] = $val->toArray();
            }
        }        
        return $data;
    }

    static function getFootfallByDateId($exhibitions_id, $exhibition_date){
    	$source= OfflineOrderDetails::whereIn('order_customer_source', ['FB', 'SMS','EMAIL', 'NEWSPAPER','INSTA','REF','WALK','OTHERS'])
    	 ->where('exhibitions_id', $exhibitions_id)
    	 ->where('order_date', $exhibition_date)
    	 ->selectRaw("count(*) as cnt, order_customer_source  as sources,order_date")
    	 ->groupBy('order_customer_source')->get();
    	$data = array();
        if (!empty($source)) {
            foreach ($source as $val) {
                $data[$val->sources] = $val->cnt;
            }
        }  
        return $data; 
    }
    static function getFootFallSource($exhibitions_id){
    	 $source= OfflineOrderDetails::whereIn('order_customer_source', ['FB', 'SMS','EMAIL', 'NEWSPAPER','INSTA','REF','WALK','OTHERS'])
    	 ->where('exhibitions_id', $exhibitions_id)
    	 ->selectRaw("count(*) as cnt, order_customer_source  as sources")
    	 ->groupBy('order_customer_source')->get();
    	$data = array();
        if (!empty($source)) {
            foreach ($source as $val) {
                $data[]= $val->toArray();
            }
        }  
          return $data;
    }
     static function getExhibitionTransactionTq($order_place,$exhibitions_id,$year,$month){
     		$source=OfflineOrderDetails::where('exhibitions_id',$exhibitions_id)
     			->where('order_place', $order_place) 
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month)
     			->selectRaw("SUM(order_cash) as cashOrder,SUM(order_creditcard) as creditCardOrder,COUNT(order_total) as totalOrder, COUNT(DISTINCT customer_id) as uniquecust")
     			->get();
     	$data = array();
     	if (!empty($source)) {
            foreach ($source as $val) {
                $data= $val->toArray();
            }
        }  
        return $data;
     }
    static function getfirst($order_place,$exhibitions_id,$year,$month){
     	$first=OfflineOrderDetails::where('exhibitions_id',$exhibitions_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month)
				->selectRaw("SUM(order_total)AS totalamtold");

     	$source=OfflineOrderDetails::selectRaw("SUM(totalamtold) as uniqCustAmt FROM")
     			 ->mergeBindings($first->getQuery())
     			 ->get();
     	$data = array();
     	if (!empty($source)) {
            foreach ($source as $val) {
                $data= $val->toArray();
            }
        }  
        return $data;
    }
    static function getExhibitionOldFootFall(){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibitions_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month)
				->selectRaw("COUNT(customer_id)AS dupe_cnt")
     			->get();
     	$data = array();
     	if (!empty($source)) {
            foreach ($source as $val) {
                $data= $val->toArray();
            }
        }  
        return $data;
    }
    static function getUniqCustAmt($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month)
				->selectRaw('SUM(order_total)AS totalamtold,order_date')
				->groupBy('customer_id')
     			->get();
     	$data = array();
     	$sum=array();
     	if (!empty($source)) {
     		$sum = 0;
            foreach ($source as $num => $values) {
                $data[]= $values->toArray();
            }
         $sum = 0;
		foreach($data as $num => $values) {
		    $sum += $values[ 'totalamtold' ];
		}
        }  
        return $sum;
    }
    static function getbuyNewUser($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month." AND "." order_total > 0")
				->selectRaw('COUNT(customer_id)AS dupe_cnt, order_total as totalamtold,order_date')
				->groupBy('customer_id')
				->having('dupe_cnt', '=', 1)
     			->get();
     	$data = array();
     	$sum = 0;
     	if (!empty($source)) {
            foreach ($source as $num => $values) {
                $data[]= $values->toArray();
            }
        
		foreach($data as $num => $values) {
		    $sum += $values[ 'totalamtold' ];
		}
        }  
        return $sum;
    }
    static function getbuyOldUser($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month." AND "." order_total > 0")
				->selectRaw('COUNT(customer_id)AS dupe_cnt, order_total as totalamtold,order_date')
				->groupBy('customer_id')
				->having('dupe_cnt', '>', 1)
     			->get();
     	$data = array();
     	$sum = 0;
     	if (!empty($source)) {
            foreach ($source as $num => $values) {
                $data[]= $values->toArray();
            }
		foreach($data as $num => $values) {
		    $sum += $values[ 'totalamtold' ];
		}
        }  
        return $sum;
    }
     static function getbuyNewUserEx($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month." AND "." order_total > 0")
				->selectRaw('COUNT(customer_id)AS dupe_cnt, order_total as totalamtold,order_date')
				->groupBy('customer_id')
				->having('dupe_cnt', '=', 1)
				->orderBy('order_date', 'ASC')
     			->get();
     	$data = array();
     	$count = array();
     	if (!empty($source)) {
        foreach ($source as $num => $values) {
            $date= $values->order_date;
            $data2[$date][]= $values->totalamtold;
        }
        foreach ($data2 as $key => $value) {
        	$count[][$key]=array_sum($value);
        }
        }  
        return $count;
    }
    static function getbuyOldUserEx($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month." AND "." order_total > 0")
				->selectRaw('COUNT(customer_id)AS dupe_cnt, order_total as totalamtold,order_date')
				->groupBy('customer_id')
				->having('dupe_cnt', '>', 1)
				->orderBy('order_date', 'ASC')
     			->get();
     	
     	$count= array();
     	if (!empty($source)) {
        foreach ($source as $num => $values) {
            $date= $values->order_date;
            $data2[$date][]= $values->totalamtold;
        }
        foreach ($data2 as $key => $value) {
        	$count[][$key]=array_sum($value);
        }
        }  
        return $count;
    }
    static function getcashCount($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month." AND "." order_cash > 0")
				->selectRaw("count(DISTINCT customer_id) AS cashCount")
     			->get();
     		$data = array();
     	if (!empty($source)) {
            foreach ($source as $val) {
                $data= $val->toArray();
            }

        }  
        return $data['cashCount'];
    }
    static function getcreditCount($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month." AND "." order_creditcard > 0")
				->selectRaw("count(DISTINCT customer_id) AS creditCount")
     			->get();
     		$data = array();
     	if (!empty($source)) {
            foreach ($source as $val) {
                $data= $val->toArray();
            }

        }  
        return $data['creditCount'];
    }
    
     static function getRepeatCust($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month)
				->selectRaw('COUNT(customer_id)AS dupe_cnt, order_total as totalamtold')
				->groupBy('customer_id')
				->having('dupe_cnt', '>', 1)
     			->get();
     			//print_r($source); die;
     		$data = array();
     		$val = array();
     	if (!empty($source)) {
     		$sum = 0;
            foreach ($source as $num => $values) {
                $data[]= $values->toArray();
            }
         
		foreach($data as $num => $values) {
		    $val[]=$values['totalamtold'];
		}
        }  
        return count($val);
    }
     static function getNewCust($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month)
				->selectRaw('COUNT(customer_id)AS dupe_cnt, order_total as totalamtold')
				->groupBy('customer_id')
				->having('dupe_cnt', '=', 1)
     			->get();
     		$data = array();
     		$val = array();
     	if (!empty($source)) {
            foreach ($source as $num => $values) {
                $data[]= $values->toArray();
            }
        
		foreach($data as $num => $values) {
		    $val[]=$values['totalamtold'];
		}
        }  
        return count($val);
    }

     static function getExhibReportTq($order_place,$exhibitions_id,$year,$month){
     		$source=OfflineOrderDetails::where('exhibitions_id',$exhibitions_id)
     			->where('order_place', $order_place) 
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month)
     			->selectRaw('order_date as exhibitionsdate, SUM(order_total) as revenue,COUNT(DISTINCT customer_id) as footfall,SUM(order_qty>0) as qtySold,SUM(order_qty<0) as qtyExchange')
     			->groupBy('order_date')
     			->get();
     		$data = array();
     	if (!empty($source)) {
            foreach ($source as $val) {
            	$oldFFall=OfflineOrderDetails::getRepeatCustExh($order_place,$exhibitions_id,$year,$month);	
            	$newFFall=OfflineOrderDetails::getNewCustExh($order_place,$exhibitions_id,$year,$month);	
            	$buyNewUser=OfflineOrderDetails::getbuyNewUserEx($order_place,$exhibitions_id,$year,$month); 
            	$buyOldUser=OfflineOrderDetails::getbuyOldUserEx($order_place,$exhibitions_id,$year,$month);
            	$buyNewUserQty=OfflineOrderDetails::getNewCustExhQtyBuy($order_place,$exhibitions_id,$year,$month);	
            	$buyOldUserQty=OfflineOrderDetails::getOldCustExhQtyBuy($order_place,$exhibitions_id,$year,$month);	
            	$returnNewUserQty=OfflineOrderDetails::getNewCustExhQtyRetu($order_place,$exhibitions_id,$year,$month);	
            	$returnOldUserQty=OfflineOrderDetails::getOldCustExhQtyRetu($order_place,$exhibitions_id,$year,$month);	
            	$data[]= $val->toArray();
            }
            foreach ($data as  $key=>$value) {
        		if(isset($oldFFall[$key]))
        		foreach ($value as $keys => $values) {
        				$datanew[$key][$keys]=$values;
        			}	
                    $date=$value['exhibitionsdate'];
                    $datanew[$key]['oldFootFall']=isset($oldFFall[$key][$date]) ? $oldFFall[$key][$date] : '0';
                    $datanew[$key]['newFootFall']=isset($newFFall[$key][$date]) ? $newFFall[$key][$date] : '0';
                    $datanew[$key]['buyNewUser']=isset($buyNewUser[$key][$date]) ? $buyNewUser[$key][$date] : '0';
                    $datanew[$key]['buyOldUser']=isset($buyOldUser[$key][$date]) ? $buyOldUser[$key][$date] : '0';
                    $datanew[$key]['buyNewUserQty']=isset($buyNewUserQty[$key][$date]) ? $buyNewUserQty[$key][$date] : '0';
                    $datanew[$key]['buyOldUserQty']=isset($buyOldUserQty[$key][$date]) ? $buyOldUserQty[$key][$date] : '0';
                    $datanew[$key]['returnNewUserQty']=isset($returnNewUserQty[$key][$date]) ? $returnNewUserQty[$key][$date] : '0';
                    $datanew[$key]['returnOldUserQty']=isset($returnOldUserQty[$key][$date]) ? $returnOldUserQty[$key][$date] : '0';
            }
        }  
       // print_r($datanew); die;
        return $datanew;
     }
     static function getRepeatCustExh($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month)
				->selectRaw('COUNT(customer_id)AS dupe_cnt,order_date')
				->groupBy('customer_id')
				->having('dupe_cnt', '>', 1)
				->orderBy('order_date', 'ASC')
     			->get();
     			//print_r($source); die;
     		$data = array();
     		$count= array();
     	if (!empty($source)) {
     		$sum = 0;
            foreach ($source as $num => $values) {
               // $data[]= $values->toArray();
                $date= $values->order_date;
                $data[$date][]= $values->order_date;
            }
        }  
        //echo "<pre>";
        foreach ($data as $key => $value) {
        	 $count[][$key]=count($data[$key]);
        }
        return $count;
    }
     static function getNewCustExh($order_place,$exhibitions_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibitions_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month)
				->selectRaw('COUNT(customer_id)AS dupe_cnt, order_total as totalamtold,order_date')
				->groupBy('customer_id')
				->having('dupe_cnt', '=', 1)
				->orderBy('order_date', 'ASC')
     			->get();
     	$data = array();
     	$count = array();
     	if (!empty($source)) {
     		$sum = 0;
            foreach ($source as $num => $values) {
               // $data[]= $values->toArray();
                $date= $values->order_date;
                $data[$date][]= $values->order_date;
            }
        }  
        foreach ($data as $key => $value) {
        	$count[][$key]=count($data[$key]);
        }
        return $count;
    }
   
    static function getNewCustExhQtyBuy($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month." AND "."order_total >0")
				->selectRaw('COUNT(customer_id)AS dupe_cnt, order_total as totalamtold,order_date')
				->groupBy('customer_id')
				->having('dupe_cnt', '=', 1)
				->orderBy('order_date', 'ASC')
     			->get();
     	$data2 = array();
     	$count = array();
     	if (!empty($source)) {
        foreach ($source as $num => $values) {
            $date= $values->order_date;
            $data2[$date][]= $values->totalamtold;
        }
        foreach ($data2 as $key => $value) {
        	$count[][$key]=count($value);
        }
        }  
        return $count;

    }
    static function getOldCustExhQtyBuy($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month." AND "."order_total >0")
				->selectRaw('COUNT(customer_id)AS dupe_cnt, order_total as totalamtold,order_date')
				->groupBy('customer_id')
				->having('dupe_cnt', '>', 1)
				->orderBy('order_date', 'ASC')
     			->get();
     	$data2 = array();
     	$count = array();
     	if (!empty($source)) {
        foreach ($source as $num => $values) {
            $date= $values->order_date;
            $data2[$date][]= $values->totalamtold;
        }
        foreach ($data2 as $key => $value) {
        	$count[][$key]=count($value);
        }
        }  
        return $count;
    }
     static function getNewCustExhQtyRetu($order_place,$exhibition_id,$year,$month){
     	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month." AND "."order_total <0")
				->selectRaw('COUNT(customer_id)AS dupe_cnt, order_total as totalamtold,order_date')
				->groupBy('customer_id')
				->having('dupe_cnt', '=', 1)
				->orderBy('order_date', 'ASC')
     			->get();
     	$data2 = array();
     	$count = array();
     	if (!empty($source)) {
        foreach ($source as $num => $values) {
            $date= $values->order_date;
            $data2[$date][]= $values->totalamtold;
        }
        foreach ($data2 as $key => $value) {
        	$count[][$key]=count($value);
        }
        }  
        return $count;
    }
    
    static function getOldCustExhQtyRetu($order_place,$exhibition_id,$year,$month){
    	$source=OfflineOrderDetails::where('exhibitions_id',$exhibition_id)
     			->where('order_place', $order_place)
     			->whereRaw("YEAR(order_date) = ".$year." AND "." Month(order_date) =".$month." AND "."order_total <0")
				->selectRaw('COUNT(customer_id)AS dupe_cnt, order_total as totalamtold,order_date')
				->groupBy('customer_id')
				->having('dupe_cnt', '>', 1)
				->orderBy('order_date', 'ASC')
     			->get();
     	$data2 = array();
     	$count = array();
     	if (!empty($source)) {
        foreach ($source as $num => $values) {
            $date= $values->order_date;
            $data2[$date][]= $values->totalamtold;
        }
        foreach ($data2 as $key => $value) {
        	$count[][$key]=count($value);
        }
        }  
        return $count;
    }
}
