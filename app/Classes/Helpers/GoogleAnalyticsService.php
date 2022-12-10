<?php

namespace Dashboard\Classes\Helpers;

use Analytics;

class GoogleAnalyticsService
{

	static function getAnalyticsData($date) 
	{

        $site_id = config('google-analytics.site_id');
      	
      	$metrics = config('google-analytics.metrics');
        
        $dimensions = config('google-analytics.dimensions');
        $data = array();


       try{
         
          $stats = Analytics::query($site_id, $date['startDate'], $date['endDate'], $metrics, $dimensions);
          
          } catch (\Google_Service_Exception $e) {
          // echo 'There was a general error : ' . $e->getMessage();
           return $data;
        }
        if(isset($stats->totalsForAllResults)) {
 

        	foreach ($stats->totalsForAllResults as $key => $value) {
        		$data[$key] = $value;
        	}
        }

   		return $data;
    }

  static function getPageviewsData($date) 
  {

        $site_id = config('google-analytics.site_id');
        
        $metrics = config('google-analytics.pageviews-metrics');
        
        $extras = config('google-analytics.pageviews-extras');
        $data = array();


      try{
        $stats = Analytics::query($site_id, $date['startDate'], $date['endDate'], $metrics, $extras);
      }
       catch (\Google_Service_Exception $e) {
           //echo 'There was a general error : ' . $e->getMessage();
           return $data;
        }


        // echo "<pre>";
        // print_r($stats);
        // exit;
        $data['success'] = 0;
        $data['cart'] = 0;
        $data['checkout'] = 0;
        if(isset($stats->rows)) {
          $i = 0;
          foreach ($stats->rows as $key => $value) {
            //echo '<pre>';print_r($value);
            if(preg_match("/\/checkout\/onepage\/index/i", $value[0])){
              continue;
            }
            
            if(preg_match("/\bsuccess\b/i", $value[0])) {
              $data['success'] += $value[1];
            } else if(preg_match("/\/checkout\/cart\//i", $value[0])) {
              $data['cart'] += $value[1];
            } else if(preg_match("/\/checkout\/onepage\//i", $value[0])){
              $data['checkout'] += $value[1];
            }
            //$data[$key] = $value;
            $i++;
            if($i >= 4) {
              break;
            }
          }
        }
      return $data;
    }


  static function costPerTransaction($date) 
  {

        $site_id = config('google-analytics.site_id');
        
        $metrics = config('google-analytics.cost-per-transaction-metrics');
        
        $extras = config('google-analytics.cost-per-transaction-extras');

        $data = array();

        try{
        $stats = Analytics::query($site_id, $date['startDate'], $date['endDate'], $metrics, $extras);
        }
       catch (\Google_Service_Exception $e) {
           //echo 'There was a general error : ' . $e->getMessage();
           return $data;
        }

        // echo "<pre>";
        // print_r($stats);
        
        $data['transactionsCost'] = 0;
        $data['transactions'] = 0;

        if(isset($stats->rows)) {
        
          foreach ($stats->rows as $key => $value) {
            $data['transactionsCost'] += $value[1] * $value[2];
            $data['transactions'] += $value[1];
          }
        
        }
        return $data;
    }

}
