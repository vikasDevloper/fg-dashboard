<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;
use Dashboard\Http\Controllers\Controller;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Classes\Helpers\Utility;

class YearlyTurnoverController extends Controller
{
    public function show(){
		$data['yearlyRevenueReport'] = SalesFlatOrder::yearlyRevenueReport();
		$data['yearlyRevenueReportOffline'] = SalesFlatOrder::yearlyRevenueReportOffline();
		$data['allmonths'] = Utility::getFinacialMonth();
		return view('dashboard.yearlyTurnoverReport', compact('data'));
    }

   
}
