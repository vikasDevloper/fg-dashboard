<?php

namespace Dashboard\Data\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ExhibitionsData extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'exhibitions_data';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

    public $timestamps = false;

    /** 
     * get order counts by cancel resons
     *
     * @return array
     */

    static function getExhibitionData() {
		$exbhitionData = ExhibitionsData::whereRaw("status = 1")
						->whereDate('from_date', '<=', date("Y-m-d"))
			            ->whereDate('to_date', '>=', date("Y-m-d"))
			            ->selectRaw("exhibitions_id")
			            ->get();
		$data = array();
		if (!empty($exbhitionData)) {
			foreach ($exbhitionData as $val) {
				$data['exhibitions_id'] = $val['exhibitions_id'];
			}
		}
		return $data;
	}
     static function getExhibitionPlace($city) {
        $exbhitionData = ExhibitionsData::join('exhibition_gallery','exhibitions_data.gallery_id','=','exhibition_gallery.id')
                        ->select("exhibitions_data.gallery_id", "exhibitions_data.place_name")
                        ->groupBy('exhibitions_data.place_name')
                        ->where('exhibitions_data.city_id', '=',$city)
                        ->get();                        
        $data = array();
        if (!empty($exbhitionData)) {
            foreach ($exbhitionData as $val) {
                $data[$val->gallery_id] = $val->place_name;
                        }
        }
    return $data;
    }
    static function getExhibitionBefore($order_place,$exhibitions_id,$year,$month){
        $results = DB::select( DB::raw("SELECT * FROM `offline_order_details` WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place' AND YEAR(order_date) = '$year' AND Month(order_date) = '$month' GROUP BY order_date"));
        return $results;
    } 
     static function getExhibitions($galleryId){
        $results = DB::select(DB::raw("SELECT city_id,exhibitions_id FROM `exhibitions_data` WHERE `gallery_id` = '$galleryId'"));
         $data = array();
        if (!empty($results)) {
            foreach ($results as $val) {
                $data[$val->exhibitions_id]=$val->exhibitions_id;
            }
        }
        return $data;
    } 
     static function getExhibitionYear($exhibition_id){
        $results = DB::select(DB::raw("SELECT exhibitions_id, YEAR(from_date) as year FROM `exhibitions_data` WHERE exhibitions_id = $exhibition_id"));
         $data = array();
        if (!empty($results)) {
            foreach ($results as $val) {
                $data[$val->exhibitions_id]=$val->year;
            }
        }
        return $data;
    } 
    static function getExhibitionMonth($exhibition,$year){
        $results = DB::select(DB::raw("SELECT exhibitions_id,MONTHNAME(from_date) as monthname, Month(from_date) as month FROM `exhibitions_data` WHERE exhibitions_id = $exhibition AND Year(from_date) = $year"));
         $data = array();
        if (!empty($results)) {
            foreach ($results as $val) {
                $data[$val->month]=$val->monthname;
            }
        }
        return $data;
    } 
    

    static function getHistPerf($order_place,$exhibitions_id,$year,$month){
        //echo "SELECT * FROM `offline_order_details` GROUP BY order_date"; die;
        $results = DB::select( DB::raw("SELECT YEAR(a.order_date) as year, MONTH(a.order_date) as month , order_id, COUNT(DISTINCT a.customer_id) as footfall, 
        a.exhibitions_id, SUM(a.order_total) as revenue,
       (SELECT COUNT(customer_id)AS dupe_cnt FROM offline_order_details WHERE exhibitions_id=a.exhibitions_id GROUP BY exhibitions_id HAVING dupe_cnt > 1) as oldcust,
       (SELECT SUM(order_total)AS dupe_cnt FROM offline_order_details WHERE exhibitions_id=a.exhibitions_id GROUP BY exhibitions_id HAVING dupe_cnt > 1) as oldcustamt,
       (SELECT COUNT(customer_id)AS dupe_cnt FROM offline_order_details WHERE exhibitions_id=a.exhibitions_id GROUP BY exhibitions_id HAVING dupe_cnt = 1) as newcust,
        (SELECT count(*) AS newfootfall FROM (SELECT COUNT(customer_id)AS dupe_cnt FROM offline_order_details  GROUP BY exhibitions_id HAVING dupe_cnt=1) as tbl1) AS newfootfall, 
        (SELECT SUM(totalamtold) as buyOldUser FROM (SELECT COUNT(customer_id)AS dupe_cnt, order_total as totalamtold FROM offline_order_details GROUP BY customer_id HAVING dupe_cnt > 1) as table2) as buyOldUser,
        (SELECT SUM(totalamtold) as buyNewUser FROM (SELECT COUNT(customer_id)AS dupe_cnt,
        order_total as totalamtold FROM offline_order_details GROUP BY customer_id HAVING dupe_cnt = 1) as table3) as buyNewUser
        FROM `offline_order_details` a GROUP BY a.exhibitions_id order by month,  year desc"));
        return $results;
    } 
    static function getExhibitionTransaction($order_place,$exhibitions_id,$year,$month){

        $results = DB::select( DB::raw("SELECT  SUM(order_cash) as cashOrder,
        SUM(order_creditcard) as creditCardOrder, 
        COUNT(order_total) as totalOrder, COUNT(DISTINCT offline_order_details.customer_id) as uniquecust, 
        (SELECT SUM(totalamtold) as uniqCustAmt FROM (SELECT SUM(offline_order_details.order_total)AS totalamtold FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  AND YEAR(order_date) = '$year' AND Month(order_date) = '$month' GROUP BY offline_order_details.customer_id) as table3) as uniqCustAmt, 
         (SELECT SUM(totalamtold) as buyNewUser FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt, offline_order_details.order_total as totalamtold FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  and offline_order_details.order_total>0 GROUP BY offline_order_details.customer_id HAVING dupe_cnt = 1) as table3) as buyNewUser,
        (SELECT SUM(totalamtold) as buyOldUser FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt, offline_order_details.order_total as totalamtold FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  and offline_order_details.order_total>0 GROUP BY offline_order_details.customer_id HAVING dupe_cnt > 1) as table2) as buyOldUser,
        (SELECT count(DISTINCT customer_id) AS cashCount FROM `offline_order_details` where order_cash>0 and exhibitions_id = '$exhibitions_id' AND order_place = '$order_place' AND YEAR(order_date) = '$year' AND Month(order_date) = '$month')  AS cashCount ,
         (SELECT count(DISTINCT customer_id) AS creditCount FROM `offline_order_details` where order_creditcard>0 and exhibitions_id = '$exhibitions_id' AND order_place = '$order_place' AND YEAR(order_date) = '$year' AND Month(order_date) = '$month')  AS creditCount ,
        (SELECT count(*) AS oldfootfall FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt FROM offline_order_details  WHERE  exhibitions_id = '$exhibitions_id' AND order_place = '$order_place' AND YEAR(order_date) = '$year' AND Month(order_date) = '$month'  GROUP BY offline_order_details.customer_id HAVING dupe_cnt > 1) as tbl1) AS repeatcust ,
        (SELECT count(*) AS newfootfall FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place' AND YEAR(order_date) = '$year' AND Month(order_date) = '$month'  GROUP BY offline_order_details.customer_id HAVING dupe_cnt=1) as tbl1) AS newcust
        FROM `offline_order_details` WHERE  exhibitions_id = '$exhibitions_id' AND order_place = '$order_place' AND YEAR(order_date) = '$year' AND Month(order_date) = '$month'"));
        return $results;
    }
    
    static function getExhibition($order_place, $exhibitions_id, $year, $month) {

        $results = DB::select( DB::raw("SELECT offline_order_details.order_date as exhibitionsdate, SUM(offline_order_details.order_total) as revenue,
            COUNT(DISTINCT offline_order_details.customer_id) as footfall, 
            SUM(offline_order_details.order_qty>0) as qtySold,
            SUM(offline_order_details.order_qty<0) as qtyExchange, 
            
            (SELECT count(*) AS oldfootfall FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  GROUP BY offline_order_details.customer_id HAVING dupe_cnt > 1) as tbl1) AS oldfootfall ,
            
            (SELECT count(*) AS newfootfall FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  GROUP BY offline_order_details.customer_id HAVING dupe_cnt=1) as tbl1) AS newfootfall,
            
            (SELECT SUM(totalamtold) as buyOldUser FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt, offline_order_details.order_total as totalamtold FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  and offline_order_details.order_total>0 GROUP BY offline_order_details.customer_id HAVING dupe_cnt > 1) as table2) as buyOldUser,
            
            (SELECT COUNT(totalamtold) as buyOldUserQty FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt, offline_order_details.order_total as totalamtold FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  and offline_order_details.order_total>0 GROUP BY offline_order_details.customer_id HAVING dupe_cnt > 1) as table2) as buyOldUserQty,
            
            (SELECT COUNT(totalamtold) as returnOldUserQty FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt, offline_order_details.order_total as totalamtold FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  and offline_order_details.order_total<0 GROUP BY offline_order_details.customer_id HAVING dupe_cnt > 1) as table2) as returnOldUserQty,
            
            (SELECT SUM(totalamtold) as buyNewUser FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt, offline_order_details.order_total as totalamtold FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  and offline_order_details.order_total>0 GROUP BY offline_order_details.customer_id HAVING dupe_cnt = 1) as table3) as buyNewUser,
            
            (SELECT COUNT(totalamtold) as buyNewUserQty FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt, offline_order_details.order_total as totalamtold FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  and offline_order_details.order_total>0 GROUP BY offline_order_details.customer_id HAVING dupe_cnt = 1) as table3) as buyNewUsbuyOldUsererQty,
            
            (SELECT COUNT(totalamtold) as returnNewUserQty FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt, offline_order_details.order_total as totalamtold FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  and offline_order_details.order_total<0 GROUP BY offline_order_details.customer_id HAVING dupe_cnt = 1) as table4) as returnNewUserQty,
            
            (SELECT SUM(totalamtold) as returnNewUser FROM (SELECT COUNT(offline_order_details.customer_id)AS dupe_cnt, offline_order_details.order_total as totalamtold FROM offline_order_details  WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place'  and offline_order_details.order_total<0 GROUP BY offline_order_details.customer_id HAVING dupe_cnt = 1) as table4) as returnNewUser
            
            FROM `offline_order_details` WHERE exhibitions_id = '$exhibitions_id' AND order_place = '$order_place' AND YEAR(order_date) = '$year' AND Month(order_date) = '$month' GROUP BY order_date"));
        
        return $results; 
    }
    static function getNewCustExh($order_place, $exhibitions_id, $year, $exhibitionsdate) {
         $results = DB::select( DB::raw("SELECT t1.customer_id,t1.order_date, (SELECT count(*) FROM offline_order_details WHERE customer_id = t1.customer_id) AS custcount FROM offline_order_details AS t1 WHERE t1.order_date = '$exhibitionsdate' GROUP BY t1.customer_id HAVING custcount = 1"));
        if (!empty($results)) {
            $sum = 0;
            foreach ($results as $num => $values) {
                // $data[]= $values->toArray();
                $date          = $values->order_date;
                $data[$date][] = $values->order_date;
            }
        }
        foreach ($data as $key => $value) {
            $count[$key] = count($data[$key]);
        }
        //print_r($count); die;
        return $count;
    }
    static function getOldCustExh($order_place, $exhibitions_id, $year, $exhibitionsdate) {
         $results = DB::select( DB::raw("SELECT t1.customer_id,t1.order_date, (SELECT count(*) FROM offline_order_details WHERE customer_id = t1.customer_id) AS custcount FROM offline_order_details AS t1 WHERE t1.order_date = '$exhibitionsdate' GROUP BY t1.customer_id HAVING custcount > 1"));
        if (!empty($results)) {
            $sum = 0;
            foreach ($results as $num => $values) {
                // $data[]= $values->toArray();
                $date          = $values->order_date;
                $data[$date][] = $values->order_date;
            }
        }
        foreach ($data as $key => $value) {
            $count[$key] = count($data[$key]);
        }
       // print_r($count); die;
        return $count;
    }
    static function getbuyNewUserEx($order_place, $exhibition_id, $year, $exhibitionsdate) {
        $results = DB::select( DB::raw("SELECT t1.customer_id,t1.order_date, t1.order_total,(SELECT count(*) FROM offline_order_details WHERE customer_id = t1.customer_id) AS custcount FROM offline_order_details AS t1 WHERE t1.order_date = '$exhibitionsdate' AND  t1.order_total>0 GROUP BY t1.customer_id HAVING custcount = 1"));
        if (!empty($results)) {
            foreach ($results as $num => $values) {
                $date           = $values->order_date;
                $data2[$date][] = $values->order_total;
            }
            //print_r($data2); die;
            foreach ($data2 as $key => $value) {
                $count[$key] = array_sum($value);
            }
        }
       // print_r($count); die;
        return $count;
    }
    static function getbuyOldUserEx($order_place, $exhibition_id, $year, $exhibitionsdate) {
        $results = DB::select( DB::raw("SELECT t1.customer_id,t1.order_date, t1.order_total,(SELECT count(*) FROM offline_order_details WHERE customer_id = t1.customer_id) AS custcount FROM offline_order_details AS t1 WHERE t1.order_date = '$exhibitionsdate' AND t1.order_total>0 GROUP BY t1.customer_id HAVING custcount > 1"));
        if (!empty($results)) {
            foreach ($results as $num => $values) {
                $date           = $values->order_date;
                $data2[$date][] = $values->order_total;
            }
            foreach ($data2 as $key => $value) {
                $count[$key] = array_sum($value);
            }
        }
      //  print_r($count); die;
        return $count;
    }
    static function getNewCustExhQtyBuy($order_place, $exhibition_id, $year, $exhibitionsdate) {
      //  echo $exhibitionsdate;
        //echo $exhibition_id; 
       $results = DB::select( DB::raw("SELECT t1.customer_id,t1.order_date, t1.order_qty,(SELECT count(*) FROM offline_order_details WHERE customer_id = t1.customer_id) AS custcount FROM offline_order_details AS t1 WHERE t1.order_date = '$exhibitionsdate' AND order_place='$order_place' AND t1.order_total>0 GROUP BY t1.customer_id HAVING custcount = 1"));
       $count=array();
        if (!empty($results)) {
            foreach ($results as $num => $values) {
                $date           = $values->order_date;
                $data2[$date][] = $values->order_qty;
            }
            foreach ($data2 as $key => $value) {
                $count[$key] = array_sum($value);
            }
        }
        //echo "<pre>";
       // print_r($count); die;
        return $count;

    }
    static function getOldCustExhQtyBuy($order_place, $exhibition_id, $year, $exhibitionsdate) {
       $results = DB::select( DB::raw("SELECT t1.customer_id,t1.order_date, t1.order_qty,(SELECT count(*) FROM offline_order_details WHERE customer_id = t1.customer_id) AS custcount FROM offline_order_details AS t1 WHERE t1.order_date = '$exhibitionsdate' AND t1.order_total>0 GROUP BY t1.customer_id HAVING custcount > 1"));
       $count=array();
        if (!empty($results)) {
            foreach ($results as $num => $values) {
                $date           = $values->order_date;
                $data2[$date][] = $values->order_qty;
            }
            foreach ($data2 as $key => $value) {
                $count[$key] = array_sum($value);
            }
        }
      // print_r($count); die;
        return $count;
    }
    static function getNewCustExhQtyRetu($order_place, $exhibition_id, $year, $exhibitionsdate) {
       $results = DB::select( DB::raw("SELECT t1.customer_id,t1.order_date, t1.order_qty,(SELECT count(*) FROM offline_order_details WHERE customer_id = t1.customer_id) AS custcount FROM offline_order_details AS t1 WHERE t1.order_date = '$exhibitionsdate' AND t1.order_total<0 GROUP BY t1.customer_id HAVING custcount = 1"));
       $count=array();
        if (!empty($results)) {
            foreach ($results as $num => $values) {
                $date           = $values->order_date;
                $data2[$date][] = $values->order_qty;
            }
            foreach ($data2 as $key => $value) {
                $count[$key] = array_sum($value);
            }
        }
        //echo "<pre>";
       // print_r($count);
        return $count;

    }
    static function getOldCustExhQtyRetu($order_place, $exhibition_id, $year, $exhibitionsdate) {
       $results = DB::select( DB::raw("SELECT t1.customer_id,t1.order_date, t1.order_qty,(SELECT count(*) FROM offline_order_details WHERE customer_id = t1.customer_id) AS custcount FROM offline_order_details AS t1 WHERE t1.order_date = '$exhibitionsdate' AND t1.order_total<0 GROUP BY t1.customer_id HAVING custcount > 1"));
       $count=array();
        if (!empty($results)) {
            foreach ($results as $num => $values) {
                $date           = $values->order_date;
                $data2[$date][] = $values->order_qty;
            }
            foreach ($data2 as $key => $value) {
                $count[$key] = array_sum($value);
            }
        }
       //print_r($count); die;
        return $count;
    }
    static function getQtyExchange($order_place, $exhibition_id, $year, $exhibitionsdate) {
        $results = DB::select( DB::raw("SELECT SUM(order_qty) as qtyreturn,order_date FROM offline_order_details WHERE order_date = '$exhibitionsdate' AND order_total<0 "));
       $count=array();
        $count = array();
        if (!empty($results)) {
            foreach ($results as $num => $values) {
                $date           = $values->order_date; 
                $data2[$date][] = $values->qtyreturn;
            }
            foreach ($data2 as $key => $value) {
                 $count[$key] = array_sum($value);
            }
        }
        //print_r($count); die;
        return $count;
    }

    static function getExiDates($currDate) {

       $exbhitionDate = ExhibitionsData::select('exhibitions_id','from_date','to_date','gallery_id')
                   ->whereRaw("to_date >= '$currDate' and from_date <= '$currDate'")
                   ->get();
      
        $data = array();
        if (!empty($exbhitionDate)) {
            foreach ($exbhitionDate as $val) {
                $data[$val['exhibitions_id']] = array('from'=> $val['from_date'], 'to' =>$val['to_date'] ,'galleryID' => $val['gallery_id']);
            }
        }
        return $data;
    }
}
