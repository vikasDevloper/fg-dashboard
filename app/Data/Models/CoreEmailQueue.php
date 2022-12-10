<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CoreEmailQueue extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'core_email_queue';

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

	static function getUnprocessedMails() {
		$mails = CoreEmailQueue::join('core_email_queue_recipients', 'core_email_queue.message_id', '=', 'core_email_queue_recipients.message_id')
			->whereRaw("created_at >= (NOW() - INTERVAL 5 Day) and processed_at IS NULL")
			->orderBy('core_email_queue.message_id', 'ASC')
			->select('core_email_queue.message_id', 'message_body', 'recipient_email', 'event_type', 'recipient_name', 'message_parameters','entity_id')
			->get();

		$data = array();

		if (!empty($mails)) {
			foreach ($mails as $mail) {
				$d                       = array();
				$d['recipient_name']     = $mail['recipient_name'];
				$d['recipient_email']    = $mail['recipient_email'];
				$d['message_body']       = $mail['message_body'];
				$d['message_id']         = $mail['message_id'];
				$d['message_parameters'] = $mail['message_parameters'];
				$d['event_type']         = $mail['event_type'];
				$d['entity_id']         = $mail['entity_id'];
				$data[]                  = $d;
			}
		}

		return $data;
	}

	/*  Count total mails sent to user using queue
	 *
	 **/
	static function getTotalMails($today) {
		$mails = CoreEmailQueue::whereRaw("date(created_at) = '".$today."'")
			->selectRaw("count(message_id) AS totalUser, sum(if(processed_at != 'NULL', 1, 0)) AS totalMailsSent, event_type AS purpose")
			->groupBy('event_type')
			->get();

		$data = array();

		if (!empty($mails)) {
			foreach ($mails as $mail) {
				$data[$mail['purpose']]['totalEmails']    = $mail['totalUser'];
				$data[$mail['purpose']]['totalMailsSent'] = $mail['totalMailsSent'];
			}
		}

		return $data;
	}

	static function getAthubEmail($orderId) {
		$mails = CoreEmailQueue::where('entity_id', $orderId)
			->where('event_type', 'at_hub')
		//->whereRaw("processed_at IS NOT NULL")
			->select('created_at')
			->get()->toArray();

		return $mails;
	}
}
