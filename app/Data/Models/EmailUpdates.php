<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class EmailUpdates extends Model {
	//
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'email_updates';

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

	static function getAllMails() {
		$emails = EmailUpdates::groupBy('email_type')->get();

		$data = array();

		if (!empty($emails)) {
			foreach ($emails as $email) {
				$data[] = $email->toArray();
			}
		}
		return $data;
	}

	static function getDailyMailsSent($today) {
		$smses = EmailUpdates::selectRaw("count(email) As totalMails, sum(if(send = 1, 1, 0)) AS totalMailsSent, email_type")
			->groupBy("email_type")
			->orderBy('id', 'ASC')
			->get();

		$data = array();

		if (!empty($smses)) {
			foreach ($smses as $sms) {
				$data[$sms['email_type']]['totalEmails']    = $sms['totalMails'];
				$data[$sms['email_type']]['totalMailsSent'] = $sms['totalMailsSent'];
			}
		}

		return $data;
	}

	// static function getDailyMailsSent($today) {
	// 	$smses = EmailUpdates::whereRaw("date(created_at) = '".$today."'")
	// 		->orderBy('id', 'ASC')
	// 		->selectRaw("count(email) As totalMails, sum(if(send = 1, 1, 0)) AS totalMailsSent, email_type")
	// 		->groupBy("email_type")
	// 		->get();

	// 	$data = array();

	// 	if (!empty($smses)) {
	// 		foreach ($smses as $sms) {
	// 			$data[$sms['email_type']]['totalEmails']    = $sms['totalMails'];
	// 			$data[$sms['email_type']]['totalMailsSent'] = $sms['totalMailsSent'];
	// 		}
	// 	}

	// 	return $data;
	// }

	/** add Emails Update data in to
	 *  notification Log
	 */

	static function insertInNotificationLog() {

		$inserts = [];

		$emails = EmailUpdates::orderBy('id', 'ASC')
			->selectRaw("count(email) As totalEmails, sum(if(send = 1, 1, 0)) AS totalEmailSent, email_type, date(send_time) AS sent_date")
			->groupBy("email_type")
			->get();

		foreach ($emails as $email) {
			$inserts[] = ['type' => 'email',
				'tag'               => $email['email_type'],
				'total_added'       => $email['totalEmails'],
				'count'             => $email['totalEmailSent'],
				'sent_at'           => $email['sent_date']];
		}

		NotificationLog::insert($inserts);
	}

}
