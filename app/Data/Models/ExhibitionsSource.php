<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionsSource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'exhibition_source';

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

    static function getExhibitionSource() {
		$exbhitionSource = ExhibitionsSource::whereRaw("status = 1")
			            ->selectRaw("*")
			            ->get();
			            
		$data = array();
		if (!empty($exbhitionSource)) {
			foreach ($exbhitionSource as $val) {
				$data[] = $val->toArray();
			}
		}
		
		return $data;
	}
}
