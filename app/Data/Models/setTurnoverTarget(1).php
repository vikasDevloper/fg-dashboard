<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class setTurnoverTarget extends Model
{
    //
    static function setTurnoverTarget($data, $state){
		$cYear=date("Y");
 		$dataArr=json_decode($data);
		
		foreach ($dataArr as $key => $value) {
		  $valueArr = explode("*", $value);
		  if($valueArr[0]!='' and $valueArr[1]!=''){ 		
		   $qry="insert into `set_turnover_target` (`month`, `year`, `target_revenue`, `state`) VALUES ('".$valueArr[1]."','".$cYear."', '".$valueArr[0]."', '".$state."');";
		    DB::insert($qry);         
		  }	
		}
		 
		return true;
    }
    
    static function getTurnoverTarget(){
		   $cYear=date("Y");
 		   $qry="Select `month`, `target_revenue`, `state` from `set_turnover_target` where Year(created_at)=$cYear ";
		   $data  = DB::select($qry);
		   $datas = '';   
		   foreach ($data as $key => $value) {
		         	 
		         	 $state = $value->state;
		         	 $target_revenue = $value->target_revenue;
		         	 $datas[$state][] = $value->month .'*'.$target_revenue ;
		         }  
     	   return $datas;
    }
    static function setTurnoverTargetYearly(){

    	return "hello datat";

    }

}
