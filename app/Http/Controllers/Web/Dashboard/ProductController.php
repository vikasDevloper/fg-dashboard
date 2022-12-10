<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Dashboard\Data\Models\CataloginventoryStockItem;
use Dashboard\Data\Models\CatalogProductEntity;

use Dashboard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	// public static function allowAccess() {
	// 	$userType = Auth::user()->user_type;
	// 	if ($userType != 'A'
	// 		 && $userType != 'SD'
	// 		 && $userType != 'CXH'
	// 		 && $userType != 'CX'
	// 		 && $userType != 'WHH'
	// 		 && $userType != 'WH') {
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

	public function show() {

		// ProductController::allowAccess();
		$userType = Auth::user()->user_type;
		if ($userType != 'A'
			 && $userType != 'SD'
			 && $userType != 'CXH'
			 && $userType != 'CX'
			 && $userType != 'WHH'
			 && $userType != 'WH') {
			if ($userType === 'ACH') {//Accounts Head
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'AC') {//Accounts
				return redirect('/accounts-dashboard');
			} elseif ($userType === 'WH') {//Warehouse
				return redirect('/logistics-dashboard');
			} else {
				echo '<center><strong>You are not allowed for this.</strong></center>';
			}
		}

		$data['disabled']                        = CataloginventoryStockItem::getDisabledProductsHavingInventory();
		$data['not_visible']                     = CataloginventoryStockItem::getVisibleSimpleProductsHavingInventory();
		$data['without_category']                = CataloginventoryStockItem::getProductWithoutCategory();
		$data['filter']                          = CataloginventoryStockItem::getWithoutFilterProduct();
		$data['without_cross_sellproduct']       = CatalogProductEntity::productsHaveNotRelation('5');
		$data['product_without_related_product'] = CatalogProductEntity::productsHaveNotRelation('1');
		//print_r($data['filter']);
		return view('dashboard.product')->with('data', $data);
	}

}
