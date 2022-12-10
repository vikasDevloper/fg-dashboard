<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class SmsUpdatesLog extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'sms_updates_log';

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

     /** 
     * all customers who ordered
     * @return customers
     *
     */

     static function getUsersGotSmsToday() {
        
        $customers = SmsUpdatesLog::whereRaw("date(created_at) = date(now())")
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

}
