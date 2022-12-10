<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class UtmCampaign extends Model {
	//
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'utm_campaign';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;

	/**
	 * Get all the orders group by status
	 *
	 */

	static function getUtmConversion($date) {

		$orders = UtmCampaign::whereRaw("date(created) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->where('orderid', '!=', '')
			->selectRaw("SUM(LENGTH(orderid) - LENGTH(REPLACE(orderid, ',', '')) + 1) AS Orders, campaign AS Campaign, source as Source")
			->groupBy('campaign', 'source')
			->orderBy("Orders", "DESC")
			->get();

		$data = array();

		if (!empty($orders)) {
			foreach ($orders as $value) {
				$d['campaign'] = $value['Campaign'];
				$d['orders']   = $value['Orders'];
				$d['source']   = $value['Source'];
				$data[]        = $d;
			}
		}

		return $data;
	}

	static function sesssionCreatedBySource() {
		$sessions = UtmCampaign::selectRaw("source, count(sessionid) AS session")
			->groupBy('source')
			->orderBy('session', 'DESC')
			->get();

		$data = array();

		if (!empty($sessions)) {
			foreach ($sessions as $session) {
				$data[] = $session->toArray();
			}
		}

		return $data;
	}

}
