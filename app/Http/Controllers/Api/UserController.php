<?php

namespace Dashboard\Http\Controllers\Api;
use Dashboard\Data\Models\CatalogProductEntity;
use Dashboard\Data\Models\CatalogProductEntityVarchar;

use Dashboard\Data\Models\OfflineItemDetails;
use Dashboard\Data\Models\ProductManufacturing;
use Dashboard\Data\Models\ProductManufacturingInfo;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SalesFlatOrderItem;
use Dashboard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

	public function __construct() {
		//echo 1;
		date_default_timezone_set('UTC');
		$this->middleware('client_credentials');

		//return $data;
		//print_r($data);

	}

	public function getexibition() {

		/*$data['data'] = "return Exibition Data";
	return $data;*/
	}

	public function getTurnover(Request $request) {

		$data = $request->data;

		$data['unDeliveredOrder'] = SalesFlatOrder::unDeliveredOrders($data);
		return $data;
	}

	public function getMonthlyRevenue(Request $request) {

		$data                   = $request->data;
		$data['monthlyrevenue'] = SalesFlatOrder::getMonthlyRevenue($data);
		return $data;
	}

	public function dailyTurnover(Request $request) {

		$data                     = $request->data;
		$data['unDeliveredOrder'] = SalesFlatOrder::unDeliveredOrders($data);
		return $data;
	}

	public function dailyMonthlyRevenue() {

		$data = SalesFlatOrder::dailyMonthlyRevenue();
		return $data;
	}

	public function dailyOfflineTurnover() {
		$data = SalesFlatOrder::dailyOfflineTurnover();
		return $data;
	}

	public function offlineMonthlyRevenue() {

		$data = SalesFlatOrder::offlineMonthlyRevenue();
		return $data;
	}

	public function perDayRevenue(Request $request) {
		$data       = $request->data;
		$dayrevenue = SalesFlatOrder::dailyRevenue($data);
		return $dayrevenue;
	}

	public function productPerformance(Request $request) {

		// validate incoming request
		$validator = Validator::make($request->all(), [
				'input_option'  => 'required|in:product_wise,month_block_wise,collection_wise,category_wise',
				'styleno'       => 'required_if:input_option,==,product_wise|exists:product_manufacturing,product_style_number',
				'from_date'     => 'required_if:input_option,==,month_block_wise|date',
				'collection_id' => 'required_if:input_option,==,collection_wise',
				'category_id'   => 'required_if:input_option,==,category_wise',
				'limit'         => 'required_if:input_option,==,category_wise',
			]);

		if ($validator->fails()) {
			return $validator->errors();
		}

		$productDetailsArr = array();
		$data1             = array();

		if ($request->input_option == 'product_wise'):
		/* Get Product performance by Product ID */
		$productDetailsArr = ProductManufacturing::getManufDetailsByStyle($request->styleno);
		$productCount = count($productDetailsArr);

		 elseif ($request->input_option == 'month_block_wise'):
		/* Get Product performance by month block wise */
		$productDetailsArr = CatalogProductEntity::getManufDetailsByDate($request->from_date);
		$productCount = count($productDetailsArr);

		 elseif ($request->input_option == 'collection_wise'):
		/* Get Product performance by Collection wise */
		$productDetailsArr = CatalogProductEntity::getManufDetailsByCollection($request->collection_id);
		$productCount = count($productDetailsArr);

		 elseif ($request->input_option == 'category_wise'):

		/* Get Product performance by Category wise */

		if ($request->limit == 1) {

			$productCount = CatalogProductEntity::getManufDetailsCountByCategory($request->category_id);
			$pc           = json_decode(json_encode($productCount), true)[0]['dataCount'];
			session(['product_count' => $pc]);
		}
		// session('st_limit') = $request->limit;
		// session('end_limit') = $request->limit+100;
		if (session('product_count') > 100) {

			$productDetailsArr = CatalogProductEntity::getManufDetailsByCategory($request->category_id, $request->limit);
		} else {
			$productDetailsArr = CatalogProductEntity::getManufDetailsByCategory($request->category_id);
			$productCount      = count($productDetailsArr);
		}

		endif;

		$dataArr = json_decode(json_encode($productDetailsArr), true);

		if (!empty($productDetailsArr)) {

			foreach ($dataArr as $key => $productDetails) {

				if (strpos(strtolower($productDetails['product_name']), 'kurta') == true) {

					$overheads = config('dashboard-reports.overheads.kurta');

				} elseif (strpos(strtolower($productDetails['product_name']), 'dupatta') == true) {

					$overheads = config('dashboard-reports.overheads.dupatta');

				} elseif (strpos(strtolower($productDetails['product_name']), 'pant') == true ||
					strpos(strtolower($productDetails['product_name']), 'farsi') == true ||
					strpos(strtolower($productDetails['product_name']), 'culottes') == true ||
					strpos(strtolower($productDetails['product_name']), 'palazzo') == true) {

					$overheads = config('dashboard-reports.overheads.bottom');

				} else {

					$overheads = config('dashboard-reports.overheads.kurta');
				}

				$data                             = $offlineProductTotal                             = array();
				$onlineProductTotal['totSaleVal'] = 0;

				if (!empty($productDetails['product_mrp']) && $productDetails['product_mrp'] > 0) {

					$data['styleno'] = $productDetails['style_no'];
					$productionReleaseDate = ProductManufacturingInfo::getProductReleaseDate($productDetails['style_no']);

					$productID = CatalogProductEntityVarchar::getProductIdByStyle($data['styleno']);

					$onlineSalesDetails  = SalesFlatOrderItem::totalSaleQuantityByProductId($productID);
					$offlineSalesDetails = OfflineItemDetails::getOfflineSaleQuantity1($productDetails['style_no']);

					$onliineSaleQty = 0;
					$offlineSaleQty = 0;

					if (!empty($onlineSalesDetails) && $onlineSalesDetails['SaleQuantity'] != null) {
						$onliineSaleQty = $onlineSalesDetails['SaleQuantity'];
					}

					if (!empty($offlineSalesDetails) && $offlineSalesDetails['OfflineQuantity'] != null) {
						$offlineSaleQty = $offlineSalesDetails['OfflineQuantity'];
					}

					$amount_makes = round(($productDetails['product_mrp'] > config('dashboard-reports.tax_break_mrp'))?($productDetails['product_mrp']/config('dashboard-reports.tax_percent.above_1050')):($productDetails['product_mrp']/config('dashboard-reports.tax_percent.on_1050')), 2);

					$onlineProductTotal['totSaleVal'] = 0;

					if (!empty($productID)) {
						$onlineProductTotal = SalesFlatOrderItem::getOnlineTotalByProductIds($productID);
						if (empty($onlineProductTotal)) {
							$onlineProductTotal['totSaleVal'] = 0;
						}

					}

					$offlineProductTotal = OfflineItemDetails::getOfflineTotalByProductIds($data['styleno']);

					if (empty($offlineProductTotal)) {
						$offlineProductTotal['totOfflineSale'] = 0;
					}

					$data['product_name']    = $productDetails['product_name'];
					$data['product_created']    = $productionReleaseDate;
					$data['manf_cost']       = $productDetails['manf_cost'];
					$data['overheads']       = $overheads;
					$data['total_cost']      = $data['manf_cost']+$data['overheads'];
					$data['planned_qty']     = $productDetails['planned_qty'];
					$data['total_manf_cost'] = number_format($data['total_cost']*$data['planned_qty']);
					$data['product_mrp']     = $productDetails['product_mrp'];
					$data['amount_makes']    = $productDetails['product_mrp'];
					$fgmakes                 = $productDetails['product_mrp'];
					if ($onliineSaleQty+$offlineSaleQty > 0) {
						$fgmakes = $data['amount_makes'] = round((($onlineProductTotal['totSaleVal']+$offlineProductTotal['totOfflineSale'])/($onliineSaleQty+$offlineSaleQty)), 2);
					}

					$data['breakeven_qty']          = round(($data['total_cost']*$data['planned_qty'])/$fgmakes, 2);
					$data['online_Sale_qty']        = $onliineSaleQty;
					$data['offline_Sale_qty']       = $offlineSaleQty;
					$data['total_Sale_qty']         = $onliineSaleQty+$offlineSaleQty;
					$data['actual_sale_through']    = number_format((($onliineSaleQty+$offlineSaleQty)/$data['planned_qty'])*100, 2).'%';
					$data['breakeven_sale_through'] = number_format(($data['breakeven_qty']/$data['planned_qty'])*100, 2).'%';
					$data['profit_or_loss']         = ((($onliineSaleQty+$offlineSaleQty)*$fgmakes)-($data['total_cost']*$data['planned_qty']));
					$data['product_count'] = $productCount;
				}
				//dd($data);
				$data1[] = $data;

				$productDetails = array();

			}
		}

		return $data1;
		// exit;
		// $byProductId = CatalogProductEntityVarchar::productPerformanceByProductId($data);
		// /* End get Product performance by Product ID */

		// // $byCollection = SalesFlatOrder::productPerformanceByCollection($data);
		// // $byMonths = SalesFlatOrder::productPerformanceByMonth($data);

		// return $byProductId;
	}

}
