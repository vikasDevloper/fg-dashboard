<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class BankRefund extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'bankrefund';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

    public $timestamps = false;

    /** 
     * get order counts by cancel resons
     *
     * @return array
     */

    static function getBankRefund($date)
    {
         $orders = BankRefund::whereRaw("status in ('2', '3', '1')")
                                ->whereRaw("date(created_time) between '" . $date['startDate'] . "' AND '" . $date['endDate'] . "'")
                                ->selectRaw("count(distinct bankrefund_id) AS numbers, sum(product_amount) AS amount, reason")
                                ->groupBy("reason")
                                ->get();
        $data =  array();    
        $data['total'] = 0;
        $data['totalAmount'] =0;

        if(!empty($orders)) {
            foreach ($orders as $value) {

                $d['orders']   = $value['numbers'];
                $d['reason']   = $value['reason'];
                $d['amount']   = $value['amount'];
                $data['total'] += $value['numbers'];
                $data['totalAmount'] += $value['amount'];
                $data[] = $d;
                             
            }   
        }   

        return $data;  
    } 
}
