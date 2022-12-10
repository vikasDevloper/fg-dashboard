<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dashboard\Http\Controllers\Controller;
use Dashboard\Data\Models\ExhibitionUserSource;
use Dashboard\Data\Models\ExhibitionsData;
use Dashboard\Data\Models\ExhibitionsSource;

class ExhibitionUserSourceController extends Controller
{
    
    public function __construct() {

		$this->middleware('auth');

	}

    public function storeSource(Request $request) {

			$id = Auth::id();
			$formVal     = $request->all();
			$exhibitionId = !empty($formVal['exhibitions_id']) ? $formVal['exhibitions_id'] : '';
			
        	if(!empty($formVal)) {

				ExhibitionUserSource::insert(['exhibition_id' => $exhibitionId, 'source' => trim($formVal['source']), 'login_id' => $id]);

				//return 'true';

				return redirect('/thank-you');

			} else {

				return 'false';
			}
	
	}

	public function viewthankyou() {


		return view('dashboard.thankyouexhibitionusersource');

	}

	public function showSource() {

		$data['exhibitionsource'] = ExhibitionsSource::getExhibitionSource();
		$data['exhibitionId'] = ExhibitionsData::getExhibitionData();

		return view('dashboard.exhibitionusersource')->with('data', $data);

	}

}
