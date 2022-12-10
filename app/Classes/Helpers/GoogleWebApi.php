<?php
namespace Dashboard\Classes\Helpers;

class GoogleWebApi {

	static function getCityState($zip) {

		if (is_numeric($zip)) {

			$url          = "http://maps.googleapis.com/maps/api/geocode/json?address=".$zip."&sensor=true&components=country:IN";

			try{
				$address_info = file_get_contents($url);
				$json         = json_decode($address_info);
				$cityName     = "";
				//print_r($json);

				if (count($json->results) > 0) {

					$area = array();

					$area['city']  = $json->results[0]->address_components[1]->long_name;
					$area['state'] = $json->results[0]->address_components[2]->long_name;

					return $area;
				}
			} catch (\Exception $e) {
				echo $e->getMessage();
				return false;
			}
			
		}
	}

}