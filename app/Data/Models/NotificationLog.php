<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model {
	//
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'notification_log';

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

	static function getNotificationLog($date) {
		$logs = NotificationLog::whereRaw("DATE(sent_at) >= DATE('".$date['startDate']."' - INTERVAL 3 DAY)")
		//NotificationLog::whereRaw("DATE(sent_at) >= DATE(NOW() - INTERVAL 7 DAY)")
		//whereRaw("sent_at between '".$date['startDate']."' AND '".$date['endDate']."'")
			->groupBy('tag', 'sent_at')
			->orderBy('sent_at', 'DESC')
			->selectRaw("type, tag, count, sent_at")
			->get();

		//dd($logs);

		$data = array();
		//$previousDate = '';

		if (!empty($logs)) {
			foreach ($logs as $log) {
				$data[] = $log;

				// $data[$log['sent_at']][$log['type']]['type']  = $log['tag'];
				// $data[$log['sent_at']][$log['type']]['count'] = $log['count'];

			}
		}

		return $data;
	}

	static function getSmsNotificationLog($date) {
		$logs = NotificationLog::whereRaw("sent_at between '".$date['startDate']."' AND '".$date['endDate']."' AND type = 'sms'")
			->orderBy('sent_at', 'DESC')
			->selectRaw("type, tag, count, sent_at")
			->get();

		$data = array();
		if (!empty($logs)) {
			foreach ($logs as $log) {
				$data[] = $log;
			}
		}

		return $data;
	}

	static function getEmailNotificationLog($date) {
		$logs = NotificationLog::whereRaw("sent_at between '".$date['startDate']."' AND '".$date['endDate']."' AND type = 'email'")
			->orderBy('sent_at', 'DESC')
			->selectRaw("type, tag, count, sent_at")
			->get();

		$data = array();
		if (!empty($logs)) {
			foreach ($logs as $log) {
				$data[] = $log;
			}
		}

		return $data;
	}
}
