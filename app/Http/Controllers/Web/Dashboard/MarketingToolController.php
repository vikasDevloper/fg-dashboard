<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Data\Models\ExhibitionCities;
use Dashboard\Data\Models\NewsLetter;
use Dashboard\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarketingToolController extends Controller {

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

	public function create(Request $request) {

		$formVal = $request->all();

		DB::table('newsletter_template')->insert(['template_subject' => $formVal['template_subject'], 'template_code' => $formVal['template_name']]);

		return redirect('/marketing-tool');

	}

	public function store(Request $request) {

		$formData = $request->all();

		if (empty($formData['sendsms']) && empty($formData['sendemail'])) {

			$this->validate($request, [
					'Sms Checkbox'   => 'required',
					'Email Checkbox' => 'required',
					'Select City'    => 'required',
					'City Like'      => 'required',
					'Template Name'  => 'required',
					'User Type'      => 'required',
				]);

		}
		if (!empty($formData['sendsms']) && empty($formData['smscontent'])) {

			$this->validate($request, [
					'Sms Content' => 'required',
				]);

		}
		if (!empty($formData['sendemail']) && empty($formData['subject'])) {

			$this->validate($request, [
					'Email subject' => 'required',
					'Preview Text'  => 'required',
				]);

		}

		if (empty($formData['city']) && empty($formData['citylike']) && empty($formData['templatename']) && empty($formData['usertype'])) {

			$this->validate($request, [
					'Select City'   => 'required',
					'City Like'     => 'required',
					'Template Name' => 'required',
					'User Type'     => 'required',
				]);

		}

		if (!empty($formData['sendsms']) || !empty($formData['sendemail'])) {

			Artisan::call('cityCustomers:create', ['data' => $formData]);
			// if($formData['usertype'] == 'TestUsers') {

			// 	Artisan::call('smsUpdate:send');
			// 	Artisan::call('emailUpdate:send' , ['file' => 0]);

			// }

		}

	}

	public function show() {

		$data['citylist']     = ExhibitionCities::getListOffCities();
		$data['templatelist'] = NewsLetter::getListTemplates();

		return view('dashboard.marketingTool')->with('data', $data);
	}

}
