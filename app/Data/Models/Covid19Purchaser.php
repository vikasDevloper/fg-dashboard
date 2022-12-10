<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class Covid19Purchaser extends Model
{
    protected $table = "customer_covid19_purchaser";

    public static function getCouponByMobile($mobile){

    	$coupon_code = Covid19Purchaser::where('customer_mobile', $mobile)
    	                ->where('mobile_status',0)
						->selectRaw('coupon_code')
						->get();

		$data = '';

		if (!empty($coupon_code)) {
			foreach ($coupon_code as $val) {
				$data = $val['coupon_code'];
			}
		}
		
		return $data;
    }

       public static function getCouponByMail($email){

    	$coupon_code = Covid19Purchaser::whereRaw("customer_email = '$email' and email_status = 0")
						->selectRaw('coupon_code')
						->get();
		$data = '';

		if (!empty($coupon_code)) {
			foreach ($coupon_code as $val) {
				$data = $val['coupon_code'];
			}
		}
		
		return $data;
    }

    static function updateMobileCouponStatus($data,$coupon_code) {
		$update = Covid19Purchaser::where('customer_mobile', $data['mobile'])
		->where('coupon_code',$coupon_code)
			->update(['mobile_status' => 1]);
		return $update;
	}

	static function updateEmailCouponStatus($data,$coupon_code) {
		$update = Covid19Purchaser::where('customer_email', $data['email'])
		->where('coupon_code',$coupon_code)
			->update(['email_status' => 1]);
		return $update;
	}
}
