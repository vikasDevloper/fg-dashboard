<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class NotificationSend extends Model {
	//
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'notification_send';

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
		$users = NotificationSend::where('emailstatus', '0')
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
				$data[]           = $d;

			}
		}

		return $data;
	}

	static function updateStatus($data) {
		$update = NotificationSend::where('subscriber_email', $data['email'])
			->update(['emailstatus' => $data['status'], 'send_time' => date('Y-m-d H:i:s')]);
		return $update;
	}

	static function updateMobileStatus($data) {
		$update = NotificationSend::where('mobile', $data['mobile'])
			->update(['mobilestatus' => $data['status'], 'send_time' => date('Y-m-d H:i:s')]);
		return $update;
	}

	static function getCustomersToSendSmsCount() {
		$users = NotificationSend::where('mobilestatus', '0')
			->where('mobile', '!=', '')
			->count();
		return $users;
	}

	static function getCustomersToSendSms($start = 0, $limit = 10, $smsPurpose) {
		$users = NotificationSend::where('mobilestatus', '0')
			->where('mobile', '!=', '')
			->whereRaw("purpose like '%".$smsPurpose."%'")
			->orderBy('id', 'ASC')
			->selectRaw("DISTINCT mobile, mobilestatus, firstname, customer_id, city, purpose")
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
		$smses = NotificationSend::where('mobile', '!=', '')
			->orderBy('id', 'ASC')
			->selectRaw("count(mobile) As totalUser, sum(if(mobilestatus = 1, 1, 0)) AS totalSmsSent, purpose, utm_id")
			->groupBy("purpose","utm_id")
			->get();

		$data = array();

		if (!empty($smses)) {
			foreach ($smses as $sms) {
				$data[$sms['purpose']]['totalSms']     = $sms['totalUser'];
				$data[$sms['purpose']]['totalSmsSent'] = $sms['totalSmsSent'];
				$data[$sms['purpose']]['utm_id'] = $sms['utm_id'];
				$data[$sms['purpose']]['compaign_name'] = $sms['compaign_name'];
				$data[$sms['purpose']]['costing'] = $sms['costing'];
			}
		}

		return $data;
	}

	static function getAllNotificationMailsSent() {
		$smses = NotificationSend::where('subscriber_email', '!=', '')
			->orderBy('id', 'ASC')
			->selectRaw("count(subscriber_email) As totalUser, sum(if(emailstatus = 1, 1, 0)) AS totalMailsSent, purpose, utm_id")
			->groupBy("purpose","utm_id")
			->get();

		$data = array();

		if (!empty($smses)) {
			foreach ($smses as $sms) {
				$data[$sms['purpose']]['totalEmails']    = $sms['totalUser'];
				$data[$sms['purpose']]['totalMailsSent'] = $sms['totalMailsSent'];
				$data[$sms['purpose']]['utm_id'] = $sms['utm_id'];
				$data[$sms['purpose']]['compaign_name'] = $sms['compaign_name'];
				$data[$sms['purpose']]['costing'] = $sms['costing'];
			}
		}

		return $data;
	}

	static function getAllBuyersMobileByDate($date){
	$buyersData = NotificationSend::whereIn('mobile', function($query ) use ($date){	
		     				$query->select('telephone')
				   			->from('sales_flat_order_address')
				    		->whereRaw("created_at >= '".$date."' ");
				    	})
	                    ->where('mobilestatus','=',0)
	                    ->update(['mobilestatus' => -1]);
	 return $buyersData;             
 	}  

 	static function getAllBuyersEmailByDate($date){
    	$buyersEmailData = NotificationSend::whereIn('subscriber_email', function($query ) use ($date){	
		     				$query->select('email')
				   			->from('sales_flat_order_address')
				    		->whereRaw("created_at >= '".$date."' ");
				    	})
	                    ->where('emailstatus','=',0)
	                    ->update(['emailstatus' => -1]);
	    return $buyersEmailData;
 	}
 	static function getAllUrlLogsSMS($date){
 		//SELECT 'sms', count(*), sum(if(mobilestatus = 1, 1, 0)), purpose, date(send_time),utm_id,notification_send.compaign_name FROM `notification_send` WHERE mobile != '' and date(send_time)='2019-06-13' GROUP BY purpose, date(send_time),utm_id limit 5 + date
 		$res = NotificationSend::where(DB::raw('date(send_time)'),'=',"'$date'")->where('mobile','!=','')->selectRaw("'sms', count(*) as added, sum(if(mobilestatus = 1, 1, 0)) as sent, purpose, date(send_time) as date,utm_id,compaign_name,costing")->groupBy(DB::raw('purpose,date(send_time),utm_id'))->get();
 		return $res;
 	}
 	static function getAllUrlLogEmails($date){

 		$res = NotificationSend::where(DB::raw('date(send_time)'),'=',"'$date'")->where('subscriber_email','!=','')->selectRaw("'email', count(*)  as added, sum(if(emailstatus = 1, 1, 0)) as sent, purpose, date(send_time) as date,utm_id,compaign_name,costing")->groupBy(DB::raw('purpose,date(send_time),utm_id'))->get();
  		return $res;
 	}


}
