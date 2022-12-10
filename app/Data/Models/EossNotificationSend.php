<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class EossNotificationSend extends Model
{
    
	protected $table = 'eoss_notification_send';

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

	static function getCustomers($start = 0, $limit = 10, $mailPurpose) {
		$users = EossNotificationSend::where('emailstatus', '0')
			->where('subscriber_email', '!=', '')
			->where('purpose', 'like', '%'.$mailPurpose.'%')
			->orderBy('id', 'asc')
			->skip($start)	->take($limit)
			->get();

		$data = array();

		if (!empty($users)) {

			foreach ($users as $user) {

				$d['email']       = $user['subscriber_email'];
				$d['emailStatus'] = $user['emailstatus'];
				$d['firstname']   = $user['firstname'];
				$d['customer_id'] = $user['customer_id'];
				$d['purpose'] = $user['purpose'];
				$d['city'] = $user['city'];
				$d['coupon_code'] = $user['coupon_code'];
				$data[]           = $d;

			}
		}

		return $data;
	}

	static function updateStatus($data) {
		$update = EossNotificationSend::where('subscriber_email', $data['email'])
			->update(['emailstatus' => $data['status'], 'send_time' => date('Y-m-d H:i:s')]);
		return $update;
	}

	static function updateMobileStatus($data) {
		$update = EossNotificationSend::where('mobile', $data['mobile'])
			->update(['mobilestatus' => $data['status'], 'send_time' => date('Y-m-d H:i:s')]);
		return $update;
	}

	static function getCustomersToSendSmsCount() {
		$users = EossNotificationSend::where('mobilestatus', '0')
			->where('mobile', '!=', '')
			->count();
		return $users;
	}

	static function getCustomersToSendSms($start = 0, $limit = 10, $smsPurpose) {
		$users = EossNotificationSend::where('mobilestatus', '0')
			->where('mobile', '!=', '')
			->whereRaw("purpose like '%".$smsPurpose."%'")
			->orderBy('id', 'ASC')
			->selectRaw("DISTINCT mobile, mobilestatus, firstname, customer_id, city, purpose,coupon_code")
			->skip($start)	->take($limit)
			->get();

		$data = array();
		if (!empty($users)) {
			foreach ($users as $user) {
				$data[] = $user;
			}
		}
		return $data;
	}

	/**
	 * argument todays Date
	 */
	static function getAllNotificationSmsSent() {
		$smses = EossNotificationSend::where('mobile', '!=', '')
			->orderBy('id', 'ASC')
			->selectRaw("count(mobile) As totalUser, sum(if(mobilestatus = 1, 1, 0)) AS totalSmsSent, purpose")
			->groupBy("purpose")
			->get();

		$data = array();

		if (!empty($smses)) {
			foreach ($smses as $sms) {
				$data[$sms['purpose']]['totalSms']     = $sms['totalUser'];
				$data[$sms['purpose']]['totalSmsSent'] = $sms['totalSmsSent'];
			}
		}

		return $data;
	}

	static function getAllNotificationMailsSent() {
		$smses = EossNotificationSend::where('subscriber_email', '!=', '')
			->orderBy('id', 'ASC')
			->selectRaw("count(subscriber_email) As totalUser, sum(if(emailstatus = 1, 1, 0)) AS totalMailsSent, purpose")
			->groupBy("purpose")
			->get();

		$data = array();

		if (!empty($smses)) {
			foreach ($smses as $sms) {
				$data[$sms['purpose']]['totalEmails']    = $sms['totalUser'];
				$data[$sms['purpose']]['totalMailsSent'] = $sms['totalMailsSent'];
			}
		}

		return $data;
	}
}
