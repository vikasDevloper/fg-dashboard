<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CountriesList extends Model
{
    protected $table = "countries";

    static function getCountry($countryID){
    	$country = CountriesList::whereRaw("code = '".$countryID."' ")
			->select("name")
			->first();

		return $country['name'];
    }
}
