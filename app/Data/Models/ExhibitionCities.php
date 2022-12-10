<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionCities extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'exhibition_cities';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */


    static function getListOffCities() {
		$cities = ExhibitionCities::orderBy('city_id', 'ASC')
			->select("city_id", "city_name", "state_id", "city_sort")
			->get();

		$data = array();
		if (!empty($cities)) {
			foreach ($cities as $citylist) {
				$data[] = $citylist;
			}
		}

		return $data;
	}
}
