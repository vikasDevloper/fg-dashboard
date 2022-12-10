<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Data\Models\SalesFlatOrder;
use GuzzleHttp;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ApiController extends Controller {
	/**
	 * Create a new controller instance.
	 *

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function redirectAuthorization() {

		$query = http_build_query([
				'client_id'    => '7',
				'redirect_uri' => 'http://devdashboard.faridagupta.com/callback',
				//'redirect_uri' => 'http://localhost/callback',
				'response_type' => 'code',
				'scope'         => '',
			]);

		return redirect('http://devdashboard.faridagupta.com/oauth/authorize?'.$query);
	}

	public function callbackAccess(Request $request) {

		//$http = new Client;\
		$http = new GuzzleHttp\Client();

		$response = $http->post('http://devdashboard.faridagupta.com/oauth/token', [
				'form_params' => [
					'grant_type' => 'client_credentials',
					'client_id'  => '7',
					//'user_id' => '2',
					'client_secret' => 'mX1hp9GBogzHanB5GHQo7emn1mWdcf9zhScgv0oy',
					'redirect_uri'  => 'http://devdashboard.faridagupta.com/callback',
					'code'          => $request->code,
				],
			]);

		return json_decode((string) $response->getBody(), true);
	}

	public function my_first_api() {
		// echo "daca";
		//exit('daada');
        if(Auth::check())
            echo "user";
        else
            echo "no logged";
		$data['startDate'] = date('Y-m-d', strtotime('yesterday'));
		$data['endDate']   = date('Y-m-d');
		$data['details']   = [
			'name'   => 'Vikas',
			'mobile' => '80872728',
			'email'  => 'vikas@faridagupta.com',
			'status' => 0
		];
		$data['customers']   = SalesFlatOrder::customersCount($data);
		$data['last5Orders'] = SalesFlatOrder::last5Orders($data);
		return /*"<h1>ddd</h1>";
		 */$data;
	}

}
