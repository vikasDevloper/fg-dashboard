<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Data\Models\CatalogInventoryUpload;
use Dashboard\Data\Models\CatalogProductEntityVarchar;
use Dashboard\Data\Models\OfflineItemDetails;
use Dashboard\Data\Models\SalesFlatOrderItem;
use Dashboard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dashboard\Classes\Helpers\Utility;


class SalesReportController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	// public static function allowAccess() {
	// 	$userType = Auth::user()->user_type;
	// 	if ($userType != 'A'
	// 		 && $userType != 'SD'
	// 		 && $userType != 'CXH'
	// 		 && $userType != 'CX'
	// 		 && $userType != 'WHH') {
	// 		if ($userType === 'ACH') {//Accounts Head
	// 			return redirect('/accounts-dashboard');
	// 		} elseif ($userType === 'AC') {//Accounts
	// 			return redirect('/accounts-dashboard');
	// 		} elseif ($userType === 'WH') {//Warehouse
	// 			return redirect('/logistics-dashboard');
	// 		} else {
	// 			echo '<center><strong>You are not allowed for this.</strong></center>';
	// 		}
	// 	}
	// }

	public function show(Request $request) {

		// SalesReportController::allowAccess();

		$userType = Auth::user()->user_type;
		if ($userType != 'A'
			 && $userType != 'SD'
			 && $userType != 'CXH'
			 && $userType != 'CX'
			 && $userType != 'WHH'
			 && $userType != 'MH') {
			if ($userType === 'ACH') {//Accounts Head
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'AC') {//Accounts
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'WH') {//Warehouse
				return redirect('/logistics-dashboard');
			} else {
				echo '<center><strong>You are not allowed for this.</strong></center>';
				exit;
			}
		}

		$styleNumber = '';
		$productName = '';
		if (!empty($request)) {

			$styleNumber         = $request->input('select-style');
			$productName         = $request->input('select-name');
			$data['productName'] = $productName;
		}

		if ($productName != '') {
			$styleNumber = CatalogProductEntityVarchar::getsalethroughStyleNumber($productName);
		}

		$data['styleNumber'] = (!empty($request->input('select-style')))?$request->input('select-style'):$request->input('select-name');

		$dataval = array();

		if (!empty($styleNumber)) {
			//echo $styleNumber.'==';
			$data['product_id'] = CatalogProductEntityVarchar::getProductId($styleNumber);
			//$data['upload_total_quantity'] 	= CatalogInventoryUpload::getUploadProductQuantity($styleNumber);
			//$data['offline_total_sale'] 	= OfflineItemDetails::getOfflineSaleQuantity($styleNumber);
			$data['offline_sale']    = OfflineItemDetails::getOfflineSaleQuantity($styleNumber);
			$data['upload_quantity'] = CatalogInventoryUpload::getUploadProductQuantity($styleNumber);
			//echo '<pre>';
			// print_r($data['upload_quantity']);
			// print_r($data['offline_sale']);
			//print_r($data['product_id']);

			$uploadQuantity = array();
			if (!empty($data['upload_quantity'])) {
				$sum = 0;
				foreach ($data['upload_quantity'] as $value) {
					$sum = $value['total']+$sum;
					// $data['SKU'][] = $value['sku'];
					// $data['product_style'][] = $value['product_style'];
					$uploadQuantity[$value['sku']]['product_style'] = $value['product_style'];
					$uploadQuantity[$value['sku']]['total']         = $value['total'];
				}
			}
			//print_r($uploadQuantity);

			if (!empty($data['offline_sale'])) {
				$offlineSale = array();
				$offlineSum  = 0;
				foreach ($data['offline_sale'] as $value) {
					$offlineSum = $value['OfflineQuantity']+$offlineSum;
					// $data['offlineSku'][] = $value['item_code'];
					// $data['itemName'][] = $value['item_name'];
					$uploadQuantity[$value['item_code']]['item_name']       = $value['item_name'];
					$uploadQuantity[$value['item_code']]['OfflineQuantity'] = $value['OfflineQuantity'];
					$uploadQuantity[$value['item_code']]['item_size']       = $value['item_size'];
				}
			}
			//print_r($uploadQuantity);
			// $qt = array_merge($uploadQuantity,$offlineSale);
			// print_r($qt);

			$product_id = isset($data['product_id'])?$data['product_id']:'';
			if ($product_id) {
				foreach ($product_id as $value) {
					$data['totalSaleQuantity'][] = SalesFlatOrderItem::totalSaleQuantity($value);
				}

				foreach ($data['totalSaleQuantity'] as $value) {
					$dataval[]                                                                      = $value['SaleQuantity'];
					$uploadQuantity[empty($value['sku'])?'Not Found':$value['sku']]['SaleQuantity'] = empty($value['SaleQuantity'])?'':$value['SaleQuantity'];
				}
			}
			// print_r($data['totalSaleQuantity']);
			// print_r($uploadQuantity);

		}

		$data['upload_total_quantity']  = !empty($sum)?$sum:0;
		$data['offline_total_sale']     = !empty($offlineSum)?$offlineSum:0;
		$data['skuWiseSalethrough']     = !empty($uploadQuantity)?$uploadQuantity:'';
		$data['totalQuantity']          = array_sum($dataval);
		$data['offlineStyleNumberList'] = OfflineItemDetails::getOfflineStyleNumber();
		$data['onlineStyleNumberList']  = CatalogProductEntityVarchar::getStyleNumber();
		$data['styleNumberList']        = array_merge($data['offlineStyleNumberList'], $data['onlineStyleNumberList']);
		$data['productNameList']        = CatalogProductEntityVarchar::getProductName();

		return view('dashboard.salesStatusReport')->with('data', $data);

	}
	public function showCatalog(){
		$result = SalesFlatOrderItem::catalogOrderItems( );
		$data['catalogorderitems'] = $result[0];//SalesFlatOrderItem::catalogOrderItems( $start_date, $end_date);
		
		$data['itemsbyorderID']    = $result[1];
		// $data['catalogorderitems'] = SalesFlatOrderItem::catalogOrderItems();
		// $data['itemsbyorderID']    = SalesFlatOrderItem::catalogItemsByOrderId();

		
		// foreach ($data['catalogorderitems'] as $key => $product) {
		// 	$val = $product->value;
		// 	$arr[$val]['sku'][] = $product->sku;
		// 	$arr[$val]['count'][] = $product->count;
		// 	$arr[$val]['name'][] = $product->name;
		// 	$arr[$val]['size'][] = Utility::getCatalogProductSize($product->entity_id);

		// }
		// $data['arr'] = $arr;
        $data = array();
		if(!empty($data['catalogorderitems'])){
			foreach ($data['catalogorderitems'] as $key => $product) {
	
			$val = $product['style'];
			$arr[$val]['sku'][] = $product['sku'];
			$arr[$val]['img'][] = $product['img'];
			//$arr[$val]['count'][] = $product->count;
			$arr[$val]['name'][] = $product['name'];
			$arr[$val]['qty_ordered'][] = $product['qty_ordered'];
			$arr_size = Utility::getCatalogProductSize($product['product_id']);
	
			if(!empty($arr_size)){
				$arr[$val]['size'][] = $arr_size;

			}else{
				$arr[$val]['size'][] = 'Free Size';
			}
			}
			$data['arr'] = $arr;
		}
		 

			if(!empty($data['itemsbyorderID'])){
				foreach ($data['itemsbyorderID'] as $orderincID => $SkuArray) {
		          foreach ($SkuArray as $sku => $product) {
		          	 
					$arr2[$orderincID]['sku'][] = $product['sku'];
					$arr2[$orderincID]['order_id'] = $product['order_id'];
					$arr2[$orderincID]['style'][] = $product['style'];
					$arr2[$orderincID]['img'][] = $product['img'];//'/2/2/22-11-19_fg_02850.jpg';
					//$arr[$val]['count'][] = $product->count;
					$arr2[$orderincID]['name'][] = $product['name'];
					$arr2[$orderincID]['qty_ordered'][] = $product['qty_ordered'];
					$arr2_size = Utility::getCatalogProductSize($product['product_id']);
			
					if(!empty($arr2_size)){
						$arr2[$orderincID]['size'][] = $arr2_size;

					}else{
						$arr2[$orderincID]['size'][] = 'Free Size';
					}
				  }
				}
				$data['arr2'] = $arr2;
			}
		 

		return view('dashboard.catalogOrdersReport')->with('data', $data);
	}
	public function getCatalogByDate(Request $request){
			
		$st_date = $request->get('start-date');
		$ed_date = $request->get('end-date');

		 $data['startDate'] = $start_date = date("Y-m-d H:i:s", strtotime($st_date));
		 $data['endDate'] = $end_date = date("Y-m-d H:i:s", strtotime($ed_date));
        $result = SalesFlatOrderItem::catalogOrderItems( $start_date, $end_date);
		$data['catalogorderitems'] = $result[0];//SalesFlatOrderItem::catalogOrderItems( $start_date, $end_date);
		
		$data['itemsbyorderID']    = $result[1];//SalesFlatOrderItem::catalogItemsByOrderId($start_date, $end_date);

		$eav_attributes = array('XXS','XS','S','M','L','XL','XXL','3XL');
		
		if(!empty($data['catalogorderitems'])){
			foreach ($data['catalogorderitems'] as $key => $product) {
				//dd($product['img']);
	
				$val = $product['style'];
	
				$arr_size = Utility::getCatalogProductSize($product['product_id']);
			
				if(!empty($arr_size)){
					$arr[$val]['size'][] = $arr_size;

				}else{
					$arr[$val]['size'][] = 'Free Size';
				}
				//$arr[$val]['substr1'][] = $product->substr1;
				$arr[$val]['sku'][] = $product['sku'];
				$arr[$val]['img'][] = $product['img'];
				$arr[$val]['name'][] = $product['name'];
				$arr[$val]['qty_ordered'][] = $product['qty_ordered'];	
			}
	
			$data['arr'] = $arr;
		}

		if(!empty($data['itemsbyorderID'])){
				foreach ($data['itemsbyorderID'] as $orderincID => $SkuArray) {
		          foreach ($SkuArray as $sku => $product) {
		          	 
					$arr2[$orderincID]['sku'][] = $product['sku'];
					$arr2[$orderincID]['order_id'] = $product['order_id'];

					$arr2[$orderincID]['style'][] = $product['style'];
					$arr2[$orderincID]['img'][] = $product['img'];//'/2/2/22-11-19_fg_02850.jpg';
					//$arr[$val]['count'][] = $product->count;
					$arr2[$orderincID]['name'][] = $product['name'];
					$arr2[$orderincID]['qty_ordered'][] = $product['qty_ordered'];
					$arr2_size = Utility::getCatalogProductSize($product['product_id']);
			
					if(!empty($arr2_size)){
						$arr2[$orderincID]['size'][] = $arr2_size;

					}else{
						$arr2[$orderincID]['size'][] = 'Free Size';
					}
				  }
				}
				$data['arr2'] = $arr2;
			}
		 	//dd($data);
		return view('dashboard.catalogOrdersReport')->with('data', $data);

	}


}
