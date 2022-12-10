<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Dashboard\Data\Models\OfflineOrderDetails;

class SmsUpdates extends Model {
	//
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'sms_updates';

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

	static function getAllSms() {
		$smses = SmsUpdates::groupBy('sms_type')->get();

		$data = array();

		if (!empty($smses)) {
			foreach ($smses as $sms) {
				$data[] = $sms->toArray();
			}
		}
		return $data;
	}

	static function getDailySmsSent($today) {
		$smses = SmsUpdates::selectRaw("count(mobile) As totalSms, sum(if(send = 1, 1, 0)) AS totalSmsSent, sms_type")
			->groupBy("sms_type")
			->orderBy('id', 'ASC')
			->get();

		$data = array();

		if (!empty($smses)) {
			foreach ($smses as $sms) {
				$data[$sms['sms_type']]['totalSms']     = $sms['totalSms'];
				$data[$sms['sms_type']]['totalSmsSent'] = $sms['totalSmsSent'];
			}
		}

		return $data;
	}

	// static function getDailySmsSent($today) {
	// 	$smses = SmsUpdates::whereRaw("date(created_at) = '".$today."'")
	// 		->orderBy('id', 'ASC')
	// 		->selectRaw("count(mobile) As totalSms, sum(if(send = 1, 1, 0)) AS totalSmsSent, sms_type")
	// 		->groupBy("sms_type")
	// 		->get();

	// 	$data = array();

	// 	if (!empty($smses)) {
	// 		foreach ($smses as $sms) {
	// 			$data[$sms['sms_type']]['totalSms']     = $sms['totalSms'];
	// 			$data[$sms['sms_type']]['totalSmsSent'] = $sms['totalSmsSent'];
	// 		}
	// 	}

	// 	return $data;
	// }

	/** add Sms Update data in to
	 *  notification Log
	 */

	static function insertInNotificationLog() {

		$inserts = [];

		$smses = SmsUpdates::orderBy('id', 'ASC')
			->selectRaw("count(mobile) As totalSms, sum(if(send = 1, 1, 0)) AS totalSmsSent, sms_type, date(send_time) AS sent_date")
			->groupBy("sms_type")
			->get();

		foreach ($smses as $sms) {
			$inserts[] = ['type' => 'sms',
				'tag'               => $sms['sms_type'],
				'total_added'       => $sms['totalSms'],
				'count'             => $sms['totalSmsSent'],
				'sent_at'           => $sms['sent_date']];
		}

		NotificationLog::insert($inserts);
	}

	static function getUsersGotSmsTodayFromSmsUpdates() {
        
        $customers = SmsUpdates::whereRaw("date(created_at) = date(now())")
                                                ->select("mobile")
                                                ->orderBy('id', "asc")
                                                ->get();
        $data = array();
        
        if(!empty($customers)) {
            foreach ($customers as $customer) {
                $data[] =  $customer['mobile'];                                        
            }
        }                                        
                                      
        return $data;
    }  

    static function removeAttendees($from,$to,$galaryID="") {

        $custIDsqry = OfflineOrderDetails::select('customer_id')
                       ->whereRaw("order_date  between '$from' and '$to' ")
                       ->get();

         $custID = array();
          if(!empty($custIDsqry)) {
            foreach ($custIDsqry as $customer) {
                $custID[] =  $customer['customer_id'];                                        
            }
        }   
        if(!empty($custID)){
        	$custids = implode("','", $custID);
        	$removeAttendeesCnt = SmsUpdates::whereIn('mobile', function($query) use ($custID,$custids){	
			     				$query->select('mobile')
					   			->from('offline_customer_entity')
					    		->whereRaw("entity_id in ('$custids')");
				})->delete();                                   
        return $removeAttendeesCnt;
        }
        else
        	return 0;
     }

}
