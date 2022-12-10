<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SetTurnoverTarget extends Model
{
    //
    static function setTurnoverTarget($data, $state){
		$cYear=date("Y");
        $eYear=date('Y', strtotime('+1 year'));
        $month=array('January','February','March');
 		$dataArr=json_decode($data);
		DB::delete("DELETE FROM `set_turnover_target` where  `year`= $cYear and `state`= '".$state."' and `month` not in ('January','February','March')");
		DB::delete("DELETE FROM `set_turnover_target` where  `year`= $eYear and `state`= '".$state."' and `month` in ('January','February','March')");
		foreach ($dataArr as $key => $value) {
		  $valueArr = explode("*", $value);
		  	if(in_array($valueArr[1], $month))
		  		$Year = $eYear;
		  	else
		  		$Year = $cYear;
		   $qry="insert into `set_turnover_target` (`month`, `year`, `target_revenue`, `state`) VALUES ('".$valueArr[1]."','".$Year."', '".$valueArr[0]."', '".$state."');";
		    DB::insert($qry);   

 		}
		 
		return true;
    }
    
    static function getTurnoverTarget(){
		  $cYear=date("2019");
 		   $qry="Select `month`, `target_revenue`, `state` from `set_turnover_target` where Year(created_at)=$cYear ";
		   $data  = DB::select($qry);
		   $datas = '';   
		   foreach ($data as $key => $value) {
		         	 
		         	 $state = $value->state;
		         	 $target_revenue = $value->target_revenue;
		         	 $datas[$state][$value->month] = $target_revenue ;
		         }  
     	   return $datas;
    }

    static function setTurnoverTargetYearly($total, $state){
    	$cYear=date("Y");
    	DB::delete("DELETE FROM `set_turnover_target` where  `year`= $cYear and `state`= '".$state."' ");
    	$qry="insert into `set_turnover_target` (`month`, `year`, `target_revenue`, `state`) VALUES ('NA','".$cYear."', '".$total."', '".$state."');";
		DB::insert($qry);   
        return true;
    }


}
