<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Dashboard\Data\Models\CatalogProductEntityVarchar;
use Dashboard\Classes\Helpers\Utility;
use DB;


class SalesFlatOrderItem extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'sales_flat_order_item';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;

	/**
	 * Get all the orders group by status
	 *
	 * @return array
	 */

	static function getKurtaPurchased($order_id) {

		$kurta_category_id = 5;

		$kurtaPurchased = SalesFlatOrderItem::join("catalog_category_product", "sales_flat_order_item.product_id", "=", "catalog_category_product.product_id")
			->whereRaw("sales_flat_order_item.order_id = '".$order_id."'")
			->whereRaw("sales_flat_order_item.product_type = 'configurable'")
			->whereRaw("catalog_category_product.category_id = '".$kurta_category_id."'")
			->select('sales_flat_order_item.product_id AS product_id', 'sales_flat_order_item.name AS name', 'catalog_category_product.category_id AS category_id')
			->get();

		$data = array();

		if (!empty($kurtaPurchased)) {
			foreach ($kurtaPurchased as $kurtas) {
				$data[] = $kurtas->toArray();
			}
		}

		return $data;

	}

	static function checkCrosssellPurchased($customer_id, $product_id) {

		$checkCrosssell = SalesFlatOrderItem::join("sales_flat_order", "sales_flat_order.entity_id", "=", "sales_flat_order_item.order_id")
			->whereRaw("sales_flat_order.customer_id = '".$customer_id."'")
			->whereRaw("sales_flat_order_item.product_id = '".$product_id."'")
			->select('sales_flat_order_item.order_id AS order_id')
			->get();

		$data = array();

		if (!empty($checkCrosssell)) {
			foreach ($checkCrosssell as $crosssell) {
				$data[] = $crosssell->toArray();
			}
		}

		return $data;
	}

	static function productSoldByColor($date) {

		$soldByColors = SalesFlatOrderItem::join("sales_flat_order", "sales_flat_order.entity_id", "=", "sales_flat_order_item.order_id")
			->join("catalog_product_entity_int", "catalog_product_entity_int.entity_id", "=", "sales_flat_order_item.product_id")
			->join("eav_attribute_option_value", "eav_attribute_option_value.option_id", "=", "catalog_product_entity_int.value")
			->whereRaw("catalog_product_entity_int.attribute_id = '92'")
			->whereRaw("sales_flat_order.status = 'delivered'")
			->whereRaw("sales_flat_order_item.product_type = 'simple'")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("SUM(ROUND(sales_flat_order_item.qty_ordered-sales_flat_order_item.qty_refunded)) AS Quantity, eav_attribute_option_value.value AS Color")
			->groupBy("eav_attribute_option_value.value")
			->orderBy("Quantity", "DESC")
			->get();

		$data          = array();
		$data['total'] = 0;

		if (!empty($soldByColors)) {
			foreach ($soldByColors as $soldByColor) {
				$data['total'] += $soldByColor['Quantity'];
				$data[$soldByColor['Color']] = $soldByColor['Quantity'];
			}
		}

		return $data;
	}

	static function productSoldByPrice($date) {

		$i             = 1;
		$min           = 500;
		$maxm          = 3500;
		$data          = array();
		$range         = '';
		$data['total'] = 0;
		while ($i < $maxm) {
			$i     = $i+500;
			$min   = $min+500;
			$range = $i.'-'.$min;

			$soldByPrice = SalesFlatOrderItem::join("sales_flat_order", "sales_flat_order.entity_id", "=", "sales_flat_order_item.order_id")
				->whereRaw("sales_flat_order.status = 'delivered'")
				->whereRaw("sales_flat_order_item.product_type = 'Configurable'")
				->whereRaw("sales_flat_order_item.original_price between '".$i."' AND '".$min."'")
				->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
				->selectRaw("SUM(ROUND(sales_flat_order_item.qty_ordered-sales_flat_order_item.qty_refunded)) AS Quantity")
				->orderBy("Quantity", "DESC")
				->get();

			$priceData = '';

			if (!empty($soldByPrice)) {
				foreach ($soldByPrice as $soldPrice) {
					//$priceData['total'] += $priceData['Quantity'];
					$priceData = $soldPrice['Quantity'];
				}
			}
			$data['total'] += $priceData;
			$data[$range] = $priceData;
		}

		return $data;
	}

	static function topSellerKurta($dayInterval, $limit) {

		$topSellerkurtas = SalesFlatOrderItem::join("catalog_category_product", "sales_flat_order_item.product_id", "=", "catalog_category_product.product_id")
			->whereRaw("catalog_category_product.category_id = '5'")
			->whereRaw("sales_flat_order_item.product_type = 'Configurable'")
			->whereRaw("date(sales_flat_order_item.created_at) = date(now() - INTERVAL ".$dayInterval." DAY)")
			->selectRaw("(SELECT catalog_product_entity_varchar.value FROM catalog_product_entity_varchar Where catalog_product_entity_varchar.attribute_id = '98' AND catalog_product_entity_varchar.entity_id = sales_flat_order_item.product_id limit 1) AS URL")
			->selectRaw("(SELECT catalog_product_entity_varchar.value FROM catalog_product_entity_varchar Where catalog_product_entity_varchar.attribute_id = '86' AND catalog_product_entity_varchar.entity_id = sales_flat_order_item.product_id limit 1) AS ImageUrl")
			->selectRaw("(SELECT ROUND(catalog_product_entity_decimal.value) FROM catalog_product_entity_decimal WHERE catalog_product_entity_decimal.attribute_id = '75' AND catalog_product_entity_decimal.entity_id = sales_flat_order_item.product_id limit 1) AS Price")
			->selectRaw("SUM(ROUND(sales_flat_order_item.qty_ordered)) AS qty")
			->selectRaw("sales_flat_order_item.product_id AS product_id")
			->selectRaw("sales_flat_order_item.name AS product_name")
			->groupBy("sales_flat_order_item.product_id")
			->orderBy("qty", "DESC")
			->limit($limit)
			->get();

		//dd($topSellerkurtas);

		$data = array();

		if (!empty($topSellerkurtas)) {
			foreach ($topSellerkurtas as $topSeller) {
				$data[] = $topSeller->toArray();
			}
		}

		return $data;

	}

	static function topSellerBottom($dayInterval, $limit) {

		$topSellerBottom = SalesFlatOrderItem::join("catalog_category_product", "sales_flat_order_item.product_id", "=", "catalog_category_product.product_id")
			->whereRaw("catalog_category_product.category_id = '9'")
			->whereRaw("sales_flat_order_item.product_type = 'Configurable'")
			->whereRaw("date(sales_flat_order_item.created_at) = date(now() - INTERVAL ".$dayInterval." DAY)")
			->selectRaw("(SELECT catalog_product_entity_varchar.value FROM catalog_product_entity_varchar Where catalog_product_entity_varchar.attribute_id = '98' AND catalog_product_entity_varchar.entity_id = sales_flat_order_item.product_id limit 1) AS URL")
			->selectRaw("(SELECT catalog_product_entity_varchar.value FROM catalog_product_entity_varchar Where catalog_product_entity_varchar.attribute_id = '86' AND catalog_product_entity_varchar.entity_id = sales_flat_order_item.product_id limit 1) AS ImageUrl")
			->selectRaw("(SELECT ROUND(catalog_product_entity_decimal.value) FROM catalog_product_entity_decimal WHERE catalog_product_entity_decimal.attribute_id = '75' AND catalog_product_entity_decimal.entity_id = sales_flat_order_item.product_id limit 1) AS Price")
			->selectRaw("SUM(ROUND(sales_flat_order_item.qty_ordered)) AS qty")
			->selectRaw("sales_flat_order_item.product_id AS product_id")
			->selectRaw("sales_flat_order_item.name AS product_name")
			->groupBy("sales_flat_order_item.product_id")
			->orderBy("qty", "DESC")
			->limit($limit)
			->get();

		//dd($topSellerkurtas);

		$data = array();

		if (!empty($topSellerBottom)) {
			foreach ($topSellerBottom as $topSeller) {
				$data[] = $topSeller->toArray();
			}
		}

		return $data;

	}

	static function topSellerSteal($dayInterval, $limit) {

		$topSellerSteal = SalesFlatOrderItem::join("catalog_category_product", "sales_flat_order_item.product_id", "=", "catalog_category_product.product_id")
			->whereRaw("catalog_category_product.category_id = '13'")
			->whereRaw("sales_flat_order_item.product_type = 'Configurable'")
			->whereRaw("date(sales_flat_order_item.created_at) = date(now() - INTERVAL ".$dayInterval." DAY)")
			->selectRaw("(SELECT catalog_product_entity_varchar.value FROM catalog_product_entity_varchar Where catalog_product_entity_varchar.attribute_id = '98' AND catalog_product_entity_varchar.entity_id = sales_flat_order_item.product_id limit 1) AS URL")
			->selectRaw("(SELECT catalog_product_entity_varchar.value FROM catalog_product_entity_varchar Where catalog_product_entity_varchar.attribute_id = '86' AND catalog_product_entity_varchar.entity_id = sales_flat_order_item.product_id limit 1) AS ImageUrl")
			->selectRaw("(SELECT ROUND(sfi.original_price - sfi.discount_amount) FROM sales_flat_order_item AS sfi WHERE sfi.product_id = sales_flat_order_item.product_id ORDER BY sfi.item_id DESC limit 1) AS special_price")
			->selectRaw("(SELECT ROUND(sfi.original_price) FROM sales_flat_order_item AS sfi WHERE sfi.product_id = sales_flat_order_item.product_id ORDER BY sfi.item_id DESC limit 1) AS Price")
			->selectRaw("SUM(ROUND(sales_flat_order_item.qty_ordered)) AS qty")
			->selectRaw("sales_flat_order_item.product_id AS product_id")
			->selectRaw("sales_flat_order_item.name AS product_name")
			->groupBy("sales_flat_order_item.product_id")
			->orderBy("qty", "DESC")
			->limit($limit)
			->get();

		//dd($topSellerSteal);

		$data = array();

		if (!empty($topSellerSteal)) {
			foreach ($topSellerSteal as $topSeller) {
				$data[] = $topSeller->toArray();
			}
		}

		return $data;
	}

	// SELECT SUM(ROUND(sales_flat_order_item.qty_invoiced)) AS Qty, catalog_category_product.category_id  FROM `sales_flat_order_item`
	// INNER JOIN catalog_category_product ON sales_flat_order_item.product_id = catalog_category_product.product_id
	// AND catalog_category_product.category_id IN ('5', '7', '10', '11', '12', '19', '8')
	// GROUP BY catalog_category_product.category_id

	static function soldProductQtyByCategory($date) {

		$soldQtyByCategory = SalesFlatOrderItem::join("catalog_category_product", "sales_flat_order_item.product_id", "=", "catalog_category_product.product_id")
		//->whereRaw("catalog_category_product.category_id IN ('5', '7', '10', '11', '12', '19', '8')")
			->whereRaw("date(sales_flat_order_item.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->select("catalog_category_product.category_id")
			->selectRaw("CASE
					WHEN catalog_category_product.category_id = 5 THEN 'kurtas'
					WHEN catalog_category_product.category_id = 7 THEN 'Shirt/Tops'
					WHEN catalog_category_product.category_id = 6 THEN 'Kurti/Shirt/Tops'
					WHEN catalog_category_product.category_id = 8 THEN  'Dupattas'
					WHEN catalog_category_product.category_id = 10 THEN 'Shararas'
					WHEN catalog_category_product.category_id = 11 THEN 'Pants'
					WHEN catalog_category_product.category_id = 12 THEN 'Farsi pants'
					WHEN catalog_category_product.category_id = 19 THEN 'Palazzo'
				END AS Category")
			->selectRaw("SUM(ROUND(sales_flat_order_item.qty_invoiced)) AS qty")
			->groupBy("catalog_category_product.category_id")
			->orderBy("qty", "DESC")
			->get();

		//dd($soldQtyByCategory);

		$data          = array();
		$data['total'] = 0;

		if (!empty($soldQtyByCategory)) {

			foreach ($soldQtyByCategory as $sold) {
				if ($sold['Category'] != '') {

					$data['total'] += $sold['qty'];
				}
				$data[$sold['Category']] = $sold['qty'];
			}
		}

		return $data;
	}

	static function totalSoldTaxByState($date) {

		$totalTax = SalesFlatOrderItem::join("sales_flat_order", "sales_flat_order_item.order_id", "=", "sales_flat_order.entity_id")
			->join("sales_flat_order_address", "sales_flat_order.billing_address_id", "=", "sales_flat_order_address.entity_id")
			->selectRaw("SUM(ROUND(sales_flat_order_item.qty_invoiced)) AS qty,
					SUM(ROUND(sales_flat_order_item.tax_amount, 2)) AS taxAmount,
					SUM(ROUND(sales_flat_order_item.base_price, 2)) AS basicValue,
					SUM(ROUND(sales_flat_order_item.original_price, 2)) AS totalAmount,
					sales_flat_order_address.region AS state")
			->whereRaw("date(sales_flat_order_item.created_at) between '".$date['startDate']."' AND '".$date['endDate']."' AND ROUND(sales_flat_order_item.original_price) != '0' AND sales_flat_order_item.qty_invoiced != 0")
			->groupBy("sales_flat_order_address.region")
			->orderBy("qty", "DESC")
			->get();

		//dd($totalTax);

		$data = array();

		if (!empty($totalTax)) {
			foreach ($totalTax as $tax) {
				$data[] = $tax->toArray();
			}
		}

		return $data;
	}

	static function returnedProductQtyByCategory($date) {

		$soldQtyByCategory = SalesFlatOrderItem::join("catalog_category_product", "sales_flat_order_item.product_id", "=", "catalog_category_product.product_id")
		//->whereRaw("catalog_category_product.category_id IN ('5', '7', '10', '11', '12', '19', '8')")
			->whereRaw("date(sales_flat_order_item.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->select("catalog_category_product.category_id")
			->selectRaw("CASE
							WHEN catalog_category_product.category_id = 5 THEN 'kurtas'
							WHEN catalog_category_product.category_id = 7 THEN 'Shirt/Tops'
							WHEN catalog_category_product.category_id = 6 THEN 'Kurti/Shirt/Tops'
							WHEN catalog_category_product.category_id = 8 THEN 'Dupattas'
							WHEN catalog_category_product.category_id = 10 THEN 'Shararas'
							WHEN catalog_category_product.category_id = 11 THEN 'Pants'
							WHEN catalog_category_product.category_id = 12 THEN 'Farsi pants'
							WHEN catalog_category_product.category_id = 19 THEN 'Palazzo'
						END AS Category")
			->selectRaw("SUM(ROUND(sales_flat_order_item.qty_refunded)) AS qty")
			->groupBy("catalog_category_product.category_id")
			->orderBy("qty", "DESC")
			->get();

		$data          = array();
		$data['total'] = 0;

		if (!empty($soldQtyByCategory)) {
			foreach ($soldQtyByCategory as $sold) {
				if ($sold['Category'] != '') {
					$data['total'] += $sold['qty'];
				}
				$data[$sold['Category']] = $sold['qty'];
			}
		}

		return $data;
	}

	static function totalRefundedTaxByState($date) {

		$totalTax = SalesFlatOrderItem::join("sales_flat_order", "sales_flat_order_item.order_id", "=", "sales_flat_order.entity_id")
			->join("sales_flat_order_address", "sales_flat_order.billing_address_id", "=", "sales_flat_order_address.entity_id")
			->selectRaw("SUM(ROUND(sales_flat_order_item.qty_ordered)) AS qtyOrdered,
				SUM(ROUND(sales_flat_order_item.qty_refunded)) AS qtyRefunded,
				SUM(ROUND(sales_flat_order_item.tax_amount, 2)) AS taxAmount,
				SUM(ROUND(sales_flat_order_item.base_price, 2)) AS basicValue,
				SUM(ROUND(sales_flat_order_item.original_price, 2)) AS totalAmount,
				sales_flat_order_address.region AS state,
				ROUND((SUM(sales_flat_order_item.tax_amount)/SUM(sales_flat_order_item.qty_ordered))*SUM(sales_flat_order_item.qty_refunded), 2) AS refundedTaxAmount,
				ROUND((SUM(sales_flat_order_item.base_price)/SUM(sales_flat_order_item.qty_ordered))*SUM(sales_flat_order_item.qty_refunded), 2) AS refundedBasicValue,
				ROUND((SUM(sales_flat_order_item.original_price)/SUM(sales_flat_order_item.qty_ordered))*SUM(sales_flat_order_item.qty_refunded), 2) AS refundedTotalAmount")
			->whereRaw("date(sales_flat_order_item.created_at) between '".$date['startDate']."' AND '".$date['endDate']."' AND ROUND(sales_flat_order_item.original_price) != '0' AND sales_flat_order_item.qty_invoiced != 0")
			->groupBy("sales_flat_order_address.region")
			->orderBy("qtyOrdered", "DESC")
			->get();

		//dd($totalTax);

		$data = array();

		if (!empty($totalTax)) {
			foreach ($totalTax as $tax) {
				$data[] = $tax->toArray();
			}
		}

		return $data;
	}

	static function totalSaleQuantity($productId) {

		$totalSaleQuantity = SalesFlatOrderItem::whereRaw("product_id = '".$productId."' AND product_type = 'simple'")
			->selectRaw("SUM(ROUND(qty_ordered - qty_refunded - qty_canceled)) AS SaleQuantity, sku")
			->get();

		$data   = array();
		$sumval = 0;

		if (!empty($totalSaleQuantity)) {
			foreach ($totalSaleQuantity as $value) {

				$data['SaleQuantity'] = $value['SaleQuantity'];
				$data['sku']          = $value['sku'];
			}
		}

		return $data;

	}

	static function totalSaleQuantityByProductId($productId) {

		$totalSaleQuantity = SalesFlatOrderItem::whereRaw("product_id IN (".implode(',', $productId).") AND product_type = 'simple'")
			->selectRaw("SUM(ROUND(qty_invoiced - qty_refunded)) AS SaleQuantity, product_id")
			->groupBy('product_id')
			->get();

		//dd($totalSaleQuantity);

		$data = array();

		if (!empty($totalSaleQuantity)) {
			$data['SaleQuantity'] = 0;
			foreach ($totalSaleQuantity as $value) {

				$data['SaleQuantity'] += $value['SaleQuantity'];
			}
		}

		return $data;

	}

	static function getItemDetail($orderID) {

		$itemDtail = SalesFlatOrderItem::whereRaw("order_id = '".$orderID."'")
			->selectRaw("SUM(ROUND(qty_invoiced )) AS SaleQuantity, original_price, row_total_incl_tax AS total, discount_amount,item_id,product_type,sku,name")
			->groupBy("item_id")
			->get();

		$data   = array();
		$sumval = 0;

		if (!empty($itemDtail)) {
			foreach ($itemDtail as $value) {
				$item                                = $value['item_id'];
				$sku                                 = $value['sku'];
				$type                                = $value['product_type'];
				$data[$sku][$type]['qty']            = $value['SaleQuantity'];
				$data[$sku][$type]['original_price'] = $value['original_price'];
				$data[$sku][$type]['total']          = $value['total'];
				$data[$sku][$type]['discount']       = $value['discount_amount'];
				$data[$sku][$type]['product_type']   = $value['product_type'];
				$data[$sku][$type]['product_name']   = $value['name'];
				$data[$sku][$type]['sku']            = $value['sku'];

			}
		}

		return $data;
	}

	static function getOnlineTotalByProductIds($productIds) {
		$pid = implode("','", $productIds);

		$productPrice = SalesFlatOrderItem::whereRaw("product_id in ('".$pid."') and base_original_price!='' ")
			->selectRaw('sum((base_original_price -base_tax_amount)* ROUND(qty_invoiced - qty_refunded)) AS TotalSaleValue, SUM(ROUND(qty_invoiced - qty_refunded)) AS SaleQuantity, (sum((base_price_incl_tax - base_discount_amount -base_tax_amount) * ROUND(qty_invoiced - qty_refunded)) ) AS totalSaleAmount, product_id')
			->groupBy('product_id')
			->get();

		$data = array();
		if (!empty($productPrice)) {
			foreach ($productPrice as $value) {
				//$data['orig_price'][$value['SaleQuantity']] = $value['base_original_price'];
				$data['totSaleVal'] = $value['TotalSaleValue'];
				$data['saleQty']    = $value['SaleQuantity'];
				$data['totSaleAmt'] = $value['totalSaleAmount'];
			}
		}
		//dd($data); 
		return $data;
	}
	
	static function dailyMonthlyqty($date){
		$sdate           = date('2019-04-01');
		$edate           = date('Y-03-31', strtotime('+1 year'));

		$qryqty             = "SELECT  MONTH(created_at) as month, sum(round(SFOI.qty_ordered - SFOI.qty_refunded - SFOI.qty_canceled )) AS qty FROM `sales_flat_order_item` as SFOI where created_at between  '".$sdate."' and '".$edate."' and product_type= 'simple' group by MONTH(created_at)";

		$monthlyqty = DB::select($qryqty);
		$data = array();
		foreach ($monthlyqty as $key => $value) {
			$data[$value->month] = $value->qty;
		}
		return $data;
	}

static function saleByCategory($date){


 		$productIds = SalesFlatOrderItem::whereRaw("product_type = 'simple' AND created_at between '".$date['startDate']." 00:00:00' AND '".$date['endDate']." 23:59:59' ")
			->selectRaw("sum(round(qty_ordered - qty_refunded - qty_canceled )) AS qty, product_id AS ProductId")
			->groupBy("product_id")
			->get();
		 //$qry = "SELECT sum(round(SFOI.qty_ordered - SFOI.qty_refunded - SFOI.qty_canceled )) AS qty, SFOI.product_id AS ProductId FROM `sales_flat_order_item` as SFOI   WHERE SFOI.product_type = 'simple' AND SFOI.created_at between '".$date['startDate']."' AND '".$date['endDate']."' GROUP BY SFOI.product_id";
		//return array(1);
		$data = array();
		//dd($productIds);
		if (!empty($productIds)) {

      
		foreach ($productIds as $key => $value) {
			//print_r($value['ProductId']);
		    $categoryId = CatalogCategoryProduct::getCategoryByProductId($value['ProductId']);
		    if(!empty($categoryId)){ 
		    	$catName = CatalogCategoryEntityVarchar::getCategoryByCategoryId($categoryId);
		    	if(isset($categoryArr[$catName]))
		        $categoryArr[$catName] += $value['qty'];
		    else
		        $categoryArr[$catName] = $value['qty'];

			  }else
			  {
		    	if(isset($categoryArr['other']))
			   	  $categoryArr['other'] += $value['qty'];
                else
			      $categoryArr['other'] = $value['qty'];
			  }
			  
		 }
		}
        $data = array();
		//dd($categoryArr);
        if(!empty($categoryArr)){

        arsort($categoryArr);
        $total =  array_sum($categoryArr);

	        foreach($categoryArr as $category => $categoryCnt){
				 
				$prcnt_val = ($categoryCnt / $total ) * 100;	
				$data[] = array('name' => $category, 'count' => $categoryCnt, 'prcnt_val'=> $prcnt_val );
				
			}
			$data[] = array('name' => 'Total', 'count' => $total, 'prcnt_val'=> '100' );
         }
		return $data;

	}
	static function catalogOrderItems( $st_date = '', $end_date = '', $order = ''){
	
		// if( $st_date == '' && $end_date == '' ){
	
		// 	$timestamp = strtotime('today midnight');
		// 	  $st_date = date("Y-m-d H:i:s",$timestamp);
		//       $end_date = date("Y-m-d H:i:s");
		// }
		//
		// $qry = "SELECT sum(round(qty_ordered - qty_refunded - qty_canceled )) AS qty_ordered ,SFOI.sku,SFOI.name,CPEV.entity_id,CPEV.value FROM `sales_flat_order_item` as SFOI INNER JOIN `catalog_product_entity_varchar` as CPEV ON SFOI.product_id =  CPEV.entity_id WHERE created_at between  '2020-04-28 00:00:00' and '2020-05-09 19:11:00' AND CPEV.attribute_id = 163 GROUP BY SFOI.sku,CPEV.value ORDER BY SFOI.created_at DESC";
		// // //INNER JOIN `catalog_product_entity_varchar` as CPEV ON SFOI.product_id =  CPEV.entity_id ,CPEV.entity_id,CPEV.value CPEV.attribute_id = 163
		// // echo $qry;
		// $qty = DB::select($qry);
		// //dd($qty);
  //        return $qty;
//catalog_product_entity_media_gallery

		//$qry = "SELECT sum(round(qty_ordered - qty_refunded - qty_canceled )) AS qty_ordered,product_type ,SFOI.sku,SFOI.name,SFOI.product_id FROM `sales_flat_order_item` as SFOI  WHERE created_at between  '2020-04-28 00:00:00' and '2020-05-09 19:11:00'  ORDER BY SFOI.created_at DESC";
		//dd($order);
 		$itemDtail = SalesFlatOrderItem::whereRaw("sales_flat_order_item.created_at between  '".$st_date."' and '".$end_date."' and sales_flat_order.status = '".$order."'")
			->selectRaw("SUM(ROUND(qty_ordered - qty_refunded - qty_canceled  )) AS qty_ordered,sales_flat_order_item.product_type ,sales_flat_order_item.sku,sales_flat_order_item.name,sales_flat_order_item.product_id,sales_flat_order.increment_id,sales_flat_order.entity_id as order_id,sales_flat_order.shipping_method ")
			->join("sales_flat_order", "sales_flat_order_item.order_id", "=", "sales_flat_order.entity_id")
			->where("sales_flat_order.order_currency_code",'=','INR')

			->groupBy("item_id") 
			->orderBy("qty_ordered","DESC")
			//->limit(25)
			->get();

  		$data = $filteredData =$databyorder = $DataByOrder=array();
			foreach ($itemDtail as $key => $value) {
				$pickedArr = Utility::getPickedOrders($value->order_id);
 				//$pincodeArr = Utility::getOrderZipcodes($value->order_id,$value->shipping_method);
				
				if( (empty($pickedArr) )) {


 				//$pincodeArr = Utility::getOrderZipcodes($value->order_id,$value->shipping_method);		
 
                $value->style = CatalogProductEntityVarchar::getStyleByProductId($value->product_id);
                if($value->style == '')
                	$value->style = 'NA';
                $type = $value->product_type;
                $value->img = CatalogProductEntityMediaGallery::getImageByProductId($value->product_id);
                $sku = $value->sku;
                $orderIncId = $value->increment_id;
                $databyorder[$orderIncId][$sku][$type] = $value->toArray();
                if(isset($data[$sku][$type]))
				$value->qty_ordered += $data[$sku][$type]['qty_ordered'];
                 
				$data[$sku][$type] = $value->toArray();
				
			}
		}

			foreach ($data as $sku => $skuWiseItem) {

				 if(count($skuWiseItem)>1){
				 	// $filteredData['qty_ordered'][]	 = $skuWiseItem['configurable']['qty_ordered'];
				 	// $filteredData['product_type'][]	 = $skuWiseItem['configurable']['product_type'];
				 	// $filteredData['sku'][] 			 = $skuWiseItem['configurable']['qty_ordered'];
				 	// $filteredData['name'][] 		 = $skuWiseItem['configurable']['name'];
				 	// $filteredData['product_id'][] 	 = $skuWiseItem['simple']['product_id'];
				 	// $filteredData['style'][]		 = $skuWiseItem['configurable']['style'];
 
				 	if($skuWiseItem['configurable']['qty_ordered']>0)
				 	$filteredData[] = array_merge($skuWiseItem['configurable'],array('product_id'=>$skuWiseItem['simple']['product_id']));
				 }
				 else { 
				 	foreach ($skuWiseItem as $type => $value) {
				    if($value['qty_ordered']>0)
				 	$filteredData[] = $value;
				 }
				 	}
 
 
			} 

			foreach ($databyorder as $order => $orderArr) {
                 foreach ($orderArr as $sku => $skuWiseItem) {
                 	# code...
                 
					 if(count($skuWiseItem)>1){
					   if($skuWiseItem['configurable']['qty_ordered']>0)
					 	$DataByOrder[$order][$sku] = array_merge($skuWiseItem['configurable'],array('product_id'=>$skuWiseItem['simple']['product_id']));
					 }
					 else { 
					 	foreach ($skuWiseItem as $type => $value) {
					    if($value['qty_ordered']>0)
					 	$DataByOrder[$order][$sku] = $value;
					 	}
				 	}
 
 				}
			}

		 // echo '<pre>';
		 // print_r($data);exit;
			return array($filteredData,$DataByOrder);		//return $qty;
	}

	static function catalogItemsByOrderId($st_date = '', $end_date = ''){

		if( $st_date == '' && $end_date == '' ){
	
			$timestamp = strtotime('today midnight');
			  $st_date = date("Y-m-d H:i:s",$timestamp);
		      $end_date = date("Y-m-d H:i:s");
		}

		$itemDtail = SalesFlatOrderItem::whereRaw("sales_flat_order_item.created_at between  '".$st_date."' and '".$end_date."' and sales_flat_order.status = 'order_confirm' and sales_flat_order_item.product_type = 'simple'")
			->selectRaw("SUM(ROUND(qty_ordered - qty_refunded - qty_canceled  )) AS qty_ordered,sales_flat_order_item.product_type ,sales_flat_order_item.sku,GROUP_CONCAT(SUBSTRING_INDEX(sales_flat_order_item.name,'-',1)) AS name,sales_flat_order_item.product_id,sales_flat_order.increment_id,sales_flat_order.entity_id as order_id")
			->join("sales_flat_order", "sales_flat_order_item.order_id", "=", "sales_flat_order.entity_id")
			->groupBy("item_id") 
			->orderBy("qty_ordered","DESC")
			//->limit(25)
			->get();
            
            $data = $filteredData =array();
			foreach ($itemDtail as $key => $value) {
				
                $value->style = CatalogProductEntityVarchar::getStyleByProductId($value->product_id);
                if($value->style == '')
                	$value->style = 'NA';
                $type = $value->product_type;
                $sku = $value->sku;
                $value->img = CatalogProductEntityMediaGallery::getImageByProductId($value->product_id);
                $orderIncId = $value->increment_id;
               
				$data[$orderIncId][$sku] = $value->toArray();
			}

           return $data;
	}
}
