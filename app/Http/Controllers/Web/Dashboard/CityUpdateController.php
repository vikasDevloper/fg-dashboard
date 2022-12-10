<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Classes\Helpers\GoogleWebApi;
use Dashboard\Data\Models\CustomerAddressEntityVarchar;
use Dashboard\Data\Models\SalesFlatOrderAddress;
use Dashboard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CityUpdateController extends Controller {

	// public static function allowAccess() {

	// 	$userType = Auth::user()->user_type;

	// 	if ($userType != 'A' && $userType != 'SD') {
	// 		if ($userType === 'ACH') {//Accounts Head
	// 			return redirect('/accounts-dashboard');
	// 		} elseif ($userType === 'AC') {//Accounts
	// 			return redirect('/accounts-dashboard');
	// 		} elseif ($userType === 'CXH') {//Customer Support Head
	// 			return redirect('/cx-dashboard');
	// 		} elseif ($userType === 'CX') {//Customer Support
	// 			return redirect('/cx-dashboard');
	// 		} elseif ($userType === 'WHH') {//Warehouse Head
	// 			return redirect('/logistics-dashboard');
	// 		} elseif ($userType === 'WH') {//Warehouse
	// 			return redirect('/logistics-dashboard');
	// 		} else {
	// 			echo '<center><strong>You are not allowed for this.</strong></center>';
	// 		}
	// 	}
	// }

	public function show() {

		// CityUpdateController::allowAccess();

		$userType = Auth::user()->user_type;

		if ($userType != 'A' && $userType != 'SD') {
			if ($userType === 'ACH') {//Accounts Head
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'AC') {//Accounts
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'CXH') {//Customer Support Head
				return redirect('/cx-dashboard');
			} elseif ($userType === 'CX') {//Customer Support
				return redirect('/cx-dashboard');
			} elseif ($userType === 'WHH') {//Warehouse Head
				return redirect('/logistics-dashboard');
			} elseif ($userType === 'WH') {//Warehouse
				return redirect('/logistics-dashboard');
			} else {
				echo '<center><strong>You are not allowed for this.</strong></center>';
			}
		}

		echo '<pre>';

		$data                                         = array();
		$data['CustomerAddressEntityVarcharPostCode'] = CustomerAddressEntityVarchar::getPostCode();

		$data['SalesFlatOrderAddressPostCode'] = SalesFlatOrderAddress::getPostCode();

		if (!empty($data['CustomerAddressEntityVarcharPostCode'])) {
			foreach ($data['CustomerAddressEntityVarcharPostCode'] as $value) {

				$area = GoogleWebApi::getCityState($value);

				$data['cust_id'] = CustomerAddressEntityVarchar::getCustomerId($value);

				if (!empty($data['cust_id']) && !empty($area)) {

					foreach ($data['cust_id'] as $custId) {

						//echo  'customer id = '. $custId .' Pincode '.$value. ' Google City Name ' .$cityName.'<br>' ;
						try {
							if (!empty($area['city']) && $area['city'] != 'India') {
								$attribute_id = 26;
								CustomerAddressEntityVarchar::where('attribute_id', $attribute_id)->where('entity_id', $custId)->where('value', '!=', $area['city'])->update(['value' => $area['city']]);

								Log::info('Customer Id:: '.$custId.', Pincode:: '.$value.', City:: '.$area['city'].', State:: '.$area['state']);
							}

							if (!empty($area['state'] && $area['state'] != 'India')) {
								$attribute_id1 = 28;
								CustomerAddressEntityVarchar::where('attribute_id', $attribute_id1)->where('entity_id', $custId)->where('value', '!=', $area['state'])->update(['value' => $area['state']]);

								Log::info('Customer Id:: '.$custId.', Pincode:: '.$value.', City:: '.$area['city'].', State:: '.$area['state']);
							}
						} catch (\Exception $e) {
							echo $e->getMessage();
							Log::error('Customer Id:: '.$custId.', Pincode:: '.$value.' Something went wrong.');
						}

					}
				}
				$data['cust_id'] = '';
			}
		}
		//exit;
		if (!empty($data['SalesFlatOrderAddressPostCode'])) {

			foreach ($data['SalesFlatOrderAddressPostCode'] as $postCode) {

				$area = GoogleWebApi::getCityState($postCode);
				if (!empty($area)) {
					try {
						if (!empty($area['city']) && $area['city'] != 'India') {
							SalesFlatOrderAddress::where('postcode', $postCode)->where('city', '!=', $area['city'])->update([
									'city'   => $area['city'],
									'region' => $area['state']
								]);
							Log::info('Pincode:: '.$value.', City:: '.$area['city'].', State:: '.$area['state']);
						}
					} catch (\Exception $e) {
						echo $e->getMessage();
						Log::error('Pincode:: '.$value.' Something went wrong.');
					}
				}

			}
		}
		//$twoArray = array_merge($data['cust_id'], $data['cityName']);
		//CustomerAddressEntityVarchar::getCustomerCities($twoArray);

		// foreach ($data['cust_id'] as $value) {

		// 	foreach ($value as $custId) {

		// 		$data['customerCity'][] 	= CustomerAddressEntityVarchar::getCustomerCities($custId);

		// 	}

		// }

		//print_r($twoArray);
		//print_r($data['post_code2']);
	}
}
