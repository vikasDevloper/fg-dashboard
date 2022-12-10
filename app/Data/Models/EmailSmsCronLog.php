<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSmsCronLog extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'email_sms_cron_log';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;

	/**
	 * Get all the orders group by status
	 *
	 * @return array
	 */

	static function lastEmailSend($utm_campaign) {
		return $lastdata = EmailSmsCronLog::whereRaw("utm_campaign = '".$utm_campaign."' AND communiction_type = 'email'")
			->orderBy('created_at', 'DESC')
			->limit(1)
			->get();
	}

	static function lastSmsSend($utm_campaign) {
		return $lastdata = EmailSmsCronLog::whereRaw("utm_campaign = '".$utm_campaign."' AND communiction_type = 'sms'")
			->orderBy('created_at', 'DESC')
			->limit(1)
			->get();
	}

}
