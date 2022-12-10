<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CoreSmsQueue extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'core_sms_queue';

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

	static function getUnprocessedSms() {
		$smsData = CoreSmsQueue::where("sms_status", 0)->get();

		$data = array();

		if (!empty($smsData)) {
			foreach ($smsData as $sms) {
				$data[] = $sms;
			}
		}

		return $data;
	}

	/*  Count total mails sent to user using queue
	 *
	 **/
	//   static function getTotalMails($today)
	//   {
	//   	$mails = CoreEmailQueue::whereRaw("date(created_at) = '". $today . "'")
	//   						->selectRaw("count(message_id) AS totalUser, sum(if(processed_at != 'NULL', 1, 0)) AS totalMailsSent, event_type AS purpose")
	//   						->groupBy('event_type')
	// 					->get();

	// $data = array();

	// if (!empty($mails)) {
	// 	foreach ($mails as $mail) {
	// 		$data[$mail['purpose']]['totalEmails']    = $mail['totalUser'];
	// 		$data[$mail['purpose']]['totalMailsSent'] = $mail['totalMailsSent'];
	// 	}
	// }

	// return $data;
	//   }
}
