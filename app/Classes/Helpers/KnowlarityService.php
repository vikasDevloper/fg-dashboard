<?php

namespace Dashboard\Classes\Helpers;


class KnowlarityService
{

	static function getCallLogs($date) 
	{
		
		$curl = curl_init();

		$headers = array(
            "authorization: 562c53bc-522e-11e6-b56f-066beb27a027",
            "cache-control: no-cache",
            "channel: Basic",
            "content-type: application/json",
            "end_time: " . trim($date['endDate']) . " 23:59:59+05:30",
            "start_time: " . trim($date['startDate']) . " 00:00:00+05:30",
            "x-api-key: 02GZga0i0k5KmLnNByIto6d7wzMVrhyV5gmU1rSC",
            "limit: 5000"
          );

		$data = "?start_time=" . trim($date['startDate']) . "%2000%3A00%3A00%2B05%3A30&end_time=" . trim($date['endDate']) . "%2023%3A59%3A59%2B05%3A30&limit=5000";

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://kpi.knowlarity.com/Basic/v1/account/calllog" . $data,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "",
          CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);
        
        curl_close($curl);

        $data = array();
        $data['total'] = 0;
        $data['incoming'] = 0;
        $data['outgoing'] = 0;
        $data['missed']   = 0;
        
        if ($err) {
          
          echo "cURL Error #:" . $err;

        } else {
          $response = json_decode($response);
          
          $data['total'] = isset($response->meta) ? $response->meta->total_count : '0';
          if(isset($response->objects)) {

            foreach ($response->objects as $key => $value) {
              
            if($value->Call_Type == 1) {
              $data['outgoing'] += 1; 
            } elseif($value->Call_Type == 0) {
              $data['incoming'] += 1; 
            }

            if (strpos(strtolower($value->agent_number), 'sound') !== false) {
            $data['missed'] += 1;
        }

          }
          }
          
        }

        return $data;

	}


}
