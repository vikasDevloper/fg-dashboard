<?php

namespace Dashboard\Data\Models;

use Carbon\Carbon;
use Dashboard\Data\Models\Session;
use DB;
use Illuminate\Database\Eloquent\Model;
use Dashboard\Data\Models\ProductManufacturingInfo;
use Dashboard\Data\Models\catalogCategoryEntity;

class SalesFlatOrder extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'sales_flat_order';

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

	public $sizesSold = array();

	static function unDeliveredOrders($date) {

		$orders = SalesFlatOrder::whereRaw("date(created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count('entity_id') AS numbers, status, sum(grand_total) AS amount, order_currency_code AS currency")
			->groupBy('status', 'order_currency_code')
			->get();

		$data                 = array();
		$data['total']        = 0;
		$data['totalOrders']  = 0;
		$data['globalOrders'] = 0;
		$data['globalAmount'] = 0;
		$remove               = array('canceled', 'pending_payment', 'refund_order', 'holded');

		if (!empty($orders)) {
			foreach ($orders as $value) {
				$conversion = 1;

				if ($value['currency'] == 'USD' and !in_array($value['status'], $remove)) {
					$conversion = 68;
					$data['globalOrders'] += 1;
					$data['globalAmount'] += $value['amount'];
				}

				if (isset($data[$value['status']]['orders'])) {
					$data[$value['status']]['orders'] += $value['numbers'];
				} else {

					$data[$value['status']]['orders'] = $value['numbers'];
				}

				if (isset($data[$value['status']]['amount'])) {
					$data[$value['status']]['amount'] += $value['amount']*$conversion;
				} else {

					$data[$value['status']]['amount'] = $value['amount']*$conversion;
				}

				if (!in_array($value['status'], $remove)) {
					$data['total'] += $value['amount']*$conversion;
					$data['totalOrders'] += $value['numbers'];
				}
			}
		}
		return $data;
	}

	static function unDeliveredOrdersByCurrency($date, $currency = '') {

		if (!empty($currency)) {
			$currency   = 'USD';
			$conversion = 68;
		} else {
			$currency   = 'INR';
			$conversion = 1;
		}

		$orders = SalesFlatOrder::whereRaw("date(created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count('entity_id') AS numbers, status, sum(grand_total) AS amount")
			->where("order_currency_code", $currency)
			->groupBy('status')
			->get();

		$data                   = array();
		$data['totalUSD']       = 0;
		$data['totalOrdersUSD'] = 0;
		$remove                 = array('canceled', 'pending_payment', 'refund_order', 'holded');
		if (!empty($orders)) {
			foreach ($orders as $value) {
				$data[$value['status']]['orders'] = $value['numbers'];
				$data[$value['status']]['amount'] = $value['amount']*$conversion;

				if (!in_array($value['status'], $remove)) {
					$data['totalUSD'] += $value['amount']*$conversion;
					$data['totalOrdersUSD'] += $value['numbers'];
				}
			}
		}

		return $data;
	}

	static function getMonthlyRevenue($date) {
		$stDate = $date['startDate'];
		$stYear = date("Y", strtotime($date['startDate']));
		$edYear = date("Y", strtotime($date['endDate']));
		/* $orders = SalesFlatOrder::whereRaw("date(created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
		->selectRaw("sum(grand_total) AS amount")
		->groupBy('status')
		->get();*/
		$qry             = "Select sum(grand_total) AS amount from `sales_flat_order` where Year(created_at) between  $stYear and $stYear and status not in ('canceled', 'pending_payment', 'holded','refund_order') group by MONTH(created_at)";
		$data['monthly'] = $qry;
		return $data;
	}
	static function dailyMonthlyRevenue() {
		$cYear           = date("Y");
		$sdate           = date('2019-04-01');
		$edate           = date('Y-03-31', strtotime('+1 year'));
		$qry             = "Select MONTH(created_at) as month, sum(grand_total) AS amount from `sales_flat_order` where created_at between '".$sdate."' and '".$edate."' and status not in ('canceled', 'pending_payment', 'holded', 'refund_order') group by MONTH(created_at)";
		$monthlyTurnover = DB::select($qry);


		$data            = $monthlyTurnover;
		//dd($data);
		return $data;
	}
	static function dailyRevenue($data) {

		$date   = date_parse($data[0]);
		$month  = $date['month'];
		$cYear  = date("Y");
		$orders = SalesFlatOrder::whereRaw("Month(`created_at`) = '".$month."' and Year(`created_at`) = '".$cYear."' and status not in ('canceled', 'pending_payment', 'holded', 'refund_order')")
			->selectRaw("sum(grand_total) AS amount, `created_at` as current")
			->groupBy(DB::raw("Day(created_at)"))
			->get();

		if (!empty($orders)) {
			foreach ($orders as $value) {
				$utcDate = strtotime($value['current'])*1000;

				$data1[] = array($utcDate, round($value['amount']));
			}
		}
		// $datas = array('$month', '$data1', $orders);
		return $data1;
	}
	static function dailyOfflineTurnover() {
		$cdate = date("Y-m-d");
		$sdate = date('2019-04-01');
		$edate = date('Y-03-31', strtotime('+1 year'));

		$qry             = "Select sum(order_total) AS amount from `offline_order_details` where order_date between '".$sdate."' and '".$edate."' ";
		$monthlyTurnover = DB::select($qry);
		$data            = $monthlyTurnover;
		return $data;
	}

	static function offlineMonthlyRevenue() {
		$cYear = date("Y");
		$sdate = date('2019-04-01');
		$edate = date('Y-03-31', strtotime('+1 year'));

		$qry             = "Select MONTH(order_date) as month, sum(order_total) AS amount, sum(order_qty) as qty from `offline_order_details` where `order_date` between '".$sdate."' and '".$edate."' group by MONTH(order_date)";
		$monthlyTurnover = DB::select($qry);
		$data            = $monthlyTurnover;
		return $data; 
	}

	static function totalProccessedOrders($date) {

		$orders = SalesFlatOrder::whereRaw("date(created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count('entity_id') AS numbers, status, sum(grand_total) AS amount")
			->groupBy('status')
			->get();

		$data                = array();
		$data['total']       = 0;
		$data['totalOrders'] = 0;
		$remove              = array('canceled', 'pending_payment', 'holded', 'cod');
		if (!empty($orders)) {
			foreach ($orders as $value) {
				$data[$value['status']]['orders'] = $value['numbers'];
				$data[$value['status']]['amount'] = $value['amount'];
				if (!in_array($value['status'], $remove)) {
					$data['total'] += $value['amount'];
					$data['totalOrders'] += $value['numbers'];
				}

			}
		}

		return $data;
	}

	/**
	 * get all the orders by payment method
	 *
	 * @return array
	 */

	static function ordersByPaymentMethods($date) {
		$orders = SalesFlatOrder::join('sales_flat_order_payment', 'sales_flat_order.entity_id', '=', 'sales_flat_order_payment.parent_id')
			->whereRaw("status not in ('canceled', 'pending_payment', 'holded')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->groupBy('sales_flat_order_payment.method')
			->OrderBy('sales_flat_order_payment.method', 'ASC')
			->selectRaw("sales_flat_order_payment.method AS method, count(sales_flat_order.entity_id) AS orders, sum(sales_flat_order.grand_total) AS amount")
			->get();
		$data                = array();
		$data['totalOrders'] = 0;
		$data['totalAmount'] = 0;

		if (!empty($orders)) {
			foreach ($orders as $value) {
				$data['order'][$value['method']]  = $value['orders'];
				$data['amount'][$value['method']] = $value['amount'];
				if ($value['method'] != 'free') {
					$data['totalOrders'] += $value['orders'];
					$data['totalAmount'] += $value['amount'];
				}

			}
		}

		return $data;

	}

	/**
	 * get all the delivery by payment method
	 *
	 * @return array
	 */

	static function deliveryByPaymentMethods($date) {
		$orders = SalesFlatOrder::join('sales_flat_order_payment', 'sales_flat_order.entity_id', '=', 'sales_flat_order_payment.parent_id')
			->whereRaw("status in ('delivered')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->groupBy('sales_flat_order_payment.method')
			->OrderBy('sales_flat_order_payment.method', 'ASC')
			->selectRaw("sales_flat_order_payment.method AS method, count(sales_flat_order.entity_id) AS orders, sum(sales_flat_order.grand_total) AS amount")
			->get();
		$data                = array();
		$data['totalOrders'] = 0;
		$data['totalAmount'] = 0;

		if (!empty($orders)) {
			foreach ($orders as $value) {
				$data['order'][$value['method']]  = $value['orders'];
				$data['amount'][$value['method']] = $value['amount'];
				if ($value['method'] != 'free') {
					$data['totalOrders'] += $value['orders'];
					$data['totalAmount'] += $value['amount'];
				}

			}
		}

		return $data;

	}

	/**
	 * get revenue by cities
	 *
	 * @return array
	 */

	static function revenueByCities($date) {
		$orders = SalesFlatOrder::join('sales_flat_order_address', 'sales_flat_order.entity_id', '=', 'sales_flat_order_address.parent_id')
			->whereRaw("status not in ('canceled', 'pending_payment')")
			->where('address_type', 'shipping')
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->groupBy('city')
			->orderBy('amount', 'DESC')
			->selectRaw("city, count(sales_flat_order.entity_id) AS orders, sum(sales_flat_order.grand_total) AS amount")
			->get()	->take(15);
		// ->where('status', 'delivered')
		//->whereRaw("date(sales_flat_order.created_at) >= date(now() - INTERVAL 30 DAY)")

		$data = array();

		if (!empty($orders)) {
			foreach ($orders as $value) {
				$d['city']   = $value['city'];
				$d['orders'] = $value['orders'];
				$d['amount'] = $value['amount'];
				$data[]      = $d;
			}
		}

		return $data;
	}

	/**
	 * get all the customers repeat and new
	 * Also average ticket size
	 *
	 * @return array
	 */

	static function customersCount($date) {
		$orders = SalesFlatOrder::whereRaw("sales_flat_order.status not in ('canceled', 'pending_payment', 'refund_order
')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count(*) AS Total, count(DISTINCT sales_flat_order.customer_id) AS UniqueCustomer,
				sum(if((SELECT COUNT(entity_id) FROM `sales_flat_order` AS OFSD WHERE OFSD.customer_id = sales_flat_order.customer_id AND date(OFSD.created_at) < date(sales_flat_order.created_at) ) > 0 , 1, 0)) AS RepeatCustomer,
				sum(if((SELECT COUNT(entity_id) FROM `sales_flat_order` AS OFSD WHERE OFSD.customer_id = sales_flat_order.customer_id AND date(OFSD.created_at) < date(sales_flat_order.created_at) ) = 0, 1, 0)) AS NewCustomer, AVG(sales_flat_order.grand_total) AS AverageAmount,
				    sum(total_qty_ordered) AS OrdersQty")
			->get();
		$data = array();

		if (!empty($orders)) {
			foreach ($orders as $value) {
				$data['uniqueCustomer']    = $value['UniqueCustomer'];
				$data['total']             = $value['Total'];
				$data['repeatCustomer']    = $value['RepeatCustomer'];
				$data['newCustomer']       = $value['NewCustomer'];
				$data['averageTicketSize'] = $value['AverageAmount'];
				if ($value['Total'] == 0) {
					$value['Total'] = 1;
				}

				$data['averageOrderSize'] = $value['OrdersQty']/$value['Total'];
			}
		}

		return $data;
	}

	/**
	 * get orders count by time slot
	 *
	 * @return array
	 */

	static function ordersByTime($date) {
		$orders = SalesFlatOrder::whereRaw("status not in ('canceled', 'pending_payment', 'refund_order')")
			->whereRaw("date(created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count(entity_id) AS numbers, hour(created_at) AS timeSlot")
			->groupBy("timeSlot")
			->get();
		$data          = array();
		$data['total'] = 0;
		if (!empty($orders)) {
			foreach ($orders as $value) {

				$d['orders']   = $value['numbers'];
				$d['timeSlot'] = $value['timeSlot'];
				$data['total'] += $value['numbers'];
				$data[] = $d;

			}
		}

		return $data;
	}

	/**
	 * get all the products sizes sold
	 *
	 * @return array
	 */

	function ordersSold($date) {

		// $orders = SalesFlatOrderItem::join('sales_flat_order', 'sales_flat_order.entity_id', '=', 'sales_flat_order_item.order_id')
		// 	->whereRaw("sales_flat_order_item.product_type = 'simple'")
		// 	->whereRaw("date(sales_flat_order.created_at) BETWEEN '".$date['startDate']."' AND '".$date['endDate']."'")
		// 	->selectRaw('sum(round(sales_flat_order_item.qty_ordered - sales_flat_order_item.qty_refunded - sales_flat_order_item.qty_canceled )) AS Orders, sales_flat_order_item.name AS ProductName, SUBSTRING_INDEX(sales_flat_order_item.name, '-', -1) AS Size, LEFT(sales_flat_order_item.name, LENGTH(sales_flat_order_item.name) - LOCATE('-', REVERSE(sales_flat_order_item.name))) AS Name, sales_flat_order_item.order_id')
		// 	->groupBy('sales_flat_order_item.name')
		// 	->orderBy("sales_flat_order_item.name", "ASC")
		// 	->get();

		/*	$orders_canceles = SalesFlatOrderItem::join('sales_flat_order', 'sales_flat_order.entity_id', '=', 'sales_flat_order_item.order_id')
		->whereRaw("sales_flat_order_item.product_type = 'configurable'")
		->whereRaw("date(sales_flat_order.created_at) BETWEEN '".$date['startDate']."' AND '".$date['endDate']."'")
		->selectRaw('abs(qty_canceled) AS qty_cancel, sales_flat_order_item.name AS ProductName,
		SUBSTRING_INDEX(sales_flat_order_item.name, '-', -1) AS Size,
		LEFT(sales_flat_order_item.name, LENGTH(sales_flat_order_item.name) - LOCATE('-', REVERSE(sales_flat_order_item.name))) AS Name, sales_flat_order_item.order_id')
		->groupBy('sales_flat_order_item.name')
		->orderBy("sales_flat_order_item.name", "ASC")
		->get();

		$data = array();

		if (!empty($orders_canceles)) {
		foreach ($orders_canceles as $value) {
		$data[] = $value->toArray();
		}
		}
		 */

		$sql = "SELECT sum(round(SFOI.qty_ordered - SFOI.qty_refunded - SFOI.qty_canceled )) AS Orders, SFOI.name AS ProductName,
		                  SUBSTRING_INDEX(SFOI.name, '-', -1) AS Size,
		                  LEFT(SFOI.name, LENGTH(SFOI.name) - LOCATE('-', REVERSE(SFOI.name))) AS Name, SFOI.order_id
		                  FROM `sales_flat_order_item` AS SFOI
		                  INNER JOIN sales_flat_order AS SFO ON SFO.entity_id = SFOI.order_id
		                  WHERE SFOI.product_type = 'simple'

		                  AND date(SFO.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'
		                  GROUP BY SFOI.name
		                  ORDER BY `ProductName`  ASC";

		$sql_cancel = "SELECT   abs(qty_canceled )  AS qty_cancel, SFOI.name AS ProductName,
		                  SUBSTRING_INDEX(SFOI.name, '-', -1) AS Size,
		                  LEFT(SFOI.name, LENGTH(SFOI.name) - LOCATE('-', REVERSE(SFOI.name))) AS Name, SFOI.order_id
		                  FROM `sales_flat_order_item` AS SFOI
		                  INNER JOIN sales_flat_order AS SFO ON SFO.entity_id = SFOI.order_id
		                  WHERE SFOI.product_type = 'configurable'

		                  AND date(SFO.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'
		                  GROUP BY SFOI.name
		                  ORDER BY `ProductName`  ASC";

		$orders         = DB::select($sql);
		$orders_cancels = DB::select($sql_cancel);

		$orders_cancel = array_map(function ($value) {
				//$value_arr=(array)$value;
				$value2[$value->order_id] = $value->qty_cancel;
				return $value2;
			}, $orders_cancels);

		foreach ($orders_cancel as $key  => $order_arr) {
			foreach ($order_arr as $orderID => $qty) {
				$cancled_order[$orderID] = $qty;
				# code...
			}
		}

		$data                  = array();
		$d                     = array();
		$k['sizeTotal']['XS']  = 0;
		$k['sizeTotal']['S']   = 0;
		$k['sizeTotal']['M']   = 0;
		$k['sizeTotal']['L']   = 0;
		$k['sizeTotal']['XL']  = 0;
		$k['sizeTotal']['XXL'] = 0;
		$k['sizeTotal']['3XL'] = 0;
		$c['simple']           = 0;
		$data['total']         = 0;
		$previous              = '';
		$i                     = 0;

		if (!empty($orders)) {

			foreach ($orders as $value) {

				// echo $value->Name . " ";

				if ($previous != $value->Name) {
					// echo ++$i . ". " . $value->Name . " ";
					if (!empty($d)) {
						$data['total'] += $d['total'];
						$data['items'][] = $d;
					}

					$previous = $value->Name;

					$d             = array();
					$d['order_id'] = $value->order_id;
					$d['name']     = $value->Name;
					$d['total']    = 0;

				}

				if (isset($cancled_order[$value->order_id])) {
					$cancel_item = $cancled_order[$value->order_id];
				} else {

					$cancel_item = 0;
				}

				$d[$value->Size] = $value->Orders-$cancel_item;

				$d['total'] = $d['total']+$value->Orders-$cancel_item;

				if (array_key_exists($value->Size, $k['sizeTotal'])) {
					$k['sizeTotal'][$value->Size] += $value->Orders-$cancel_item;
				} else {
					$c['simple'] += $value->Orders-$cancel_item;
					;
				}

			}

			if (!empty($d)) {
				$data['total'] += $d['total'];
				$data['items'][] = $d;
			}
		}

		$k['sizeTotal']['simple'] = $c['simple'];
		$this->sizesSold          = $k['sizeTotal'];
		$data['sql']              = $sql_cancel."\n".$sql;
		return $data;

	}

	/**
	 * get order counts by cancel resons
	 *
	 * @return array
	 */

	static function getCancelReasons($date) {
		$orders = SalesFlatOrder::join('cancelreason', 'sales_flat_order.entity_id', '=', 'cancelreason.order_id')
			->whereRaw("status in ('canceled', 'refund_order', 'rto')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count(distinct entity_id) AS numbers, sum(grand_total) AS amount, cancelreason.reason AS reason")
			->groupBy("cancelreason.reason")
			->get();
		$data                = array();
		$data['total']       = 0;
		$data['totalAmount'] = 0;

		if (!empty($orders)) {
			foreach ($orders as $value) {

				$d['orders'] = $value['numbers'];
				$d['reason'] = $value['reason'];
				$d['amount'] = $value['amount'];
				$data['total'] += $value['numbers'];
				$data['totalAmount'] += $value['amount'];
				$data[] = $d;

			}
		}

		return $data;
	}

	/**
	 * get last 5 orders
	 * @params date range
	 * @return orders
	 */

	static function last5Orders($date) {
		$orders = SalesFlatOrder::join('sales_flat_order_payment', 'sales_flat_order.entity_id', '=', 'sales_flat_order_payment.parent_id')
			->whereRaw("sales_flat_order.status not in ('canceled', 'pending_payment', 'refund_order')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("sales_flat_order.increment_id as orderId, concat(sales_flat_order.customer_firstname, ' ', sales_flat_order.customer_lastname) AS name, sales_flat_order.grand_total as amount, sales_flat_order_payment.method AS method, total_qty_ordered AS quantity")
			->orderBy("created_at", "DESC")
			->get()
			->take(5);
		$data = array();

		if (!empty($orders)) {
			foreach ($orders as $value) {

				$d['orderId']  = $value['orderId'];
				$d['name']     = $value['name'];
				$d['method']   = $value['method'];
				$d['amount']   = $value['amount'];
				$d['quantity'] = $value['quantity'];

				$data[] = $d;

			}
		}

		return $data;
	}

	/**
	 * get orders delivery times
	 *  delivery time taken
	 * @return orders
	 */

	static function ordersDeliveredSLA($date) {
		$orders = SalesFlatOrder::join('sales_flat_shipment', 'sales_flat_order.entity_id', '=', 'sales_flat_shipment.order_id')
			->join('sales_flat_order_status_history', 'sales_flat_order.entity_id', '=', 'sales_flat_order_status_history.parent_id')
			->whereRaw("(sales_flat_order.status = 'delivered' OR sales_flat_order.status = 'refund_order')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count(DISTINCT sales_flat_order.entity_id) AS orders,
                                              DATEDIFF( sales_flat_order.updated_at, sales_flat_order.created_at) AS days")
			->orderBy("days", "DESC")
			->groupBy("days")
			->get();

		$data                = array();
		$data['totalOrders'] = 0;
		$data['7+']          = 0;
		$data['0-1']         = 0;
		$data['2-3']         = 0;
		$data['4-5']         = 0;
		$data['6-7']         = 0;

		if (!empty($orders)) {

			foreach ($orders as $value) {

				if ($value['days'] >= 8) {
					$data['7+'] += $value['orders'];
				} elseif ($value['days'] < 2) {
					$data['0-1'] += $value['orders'];
				} elseif ($value['days'] < 4) {
					$data['2-3'] += $value['orders'];
				} elseif ($value['days'] < 6) {
					$data['4-5'] += $value['orders'];
				} elseif ($value['days'] < 8) {
					$data['6-7'] += $value['orders'];
				}

				$data['totalOrders'] += $value['orders'];
			}

		}

		return $data;
	}

	/**
	 * get orders shipping SLA
	 * time taken for shipping
	 * @return orders
	 */

	static function ordersShippingSLA($date) {
		$orders = SalesFlatOrder::join('sales_flat_shipment', 'sales_flat_order.entity_id', '=', 'sales_flat_shipment.order_id')
			->whereRaw("sales_flat_order.status not in ('canceled', 'holded')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count(DISTINCT sales_flat_order.entity_id) AS orders, DATEDIFF(sales_flat_shipment.created_at, sales_flat_order.created_at) AS days")
			->orderBy("days", "DESC")
			->groupBy("days")
			->get();

		$data                = array();
		$data['totalOrders'] = 0;
		$data['7+']          = 0;
		$data['0-1']         = 0;
		$data['2-3']         = 0;
		$data['4-5']         = 0;
		$data['6-7']         = 0;

		if (!empty($orders)) {

			foreach ($orders as $value) {

				if ($value['days'] >= 8) {
					$data['7+'] += $value['orders'];
				} elseif ($value['days'] < 2) {
					$data['0-1'] += $value['orders'];
				} elseif ($value['days'] < 4) {
					$data['2-3'] += $value['orders'];
				} elseif ($value['days'] < 6) {
					$data['4-5'] += $value['orders'];
				} elseif ($value['days'] < 8) {
					$data['6-7'] += $value['orders'];
				}

				$data['totalOrders'] += $value['orders'];

			}
		}

		return $data;
	}

	/** get top 5 orders
	 * @params date range
	 * @return orders
	 */

	static function top5Orders($date) {
		$orders = SalesFlatOrder::join('sales_flat_order_payment', 'sales_flat_order.entity_id', '=', 'sales_flat_order_payment.parent_id')
			->whereRaw("sales_flat_order.status not in ('canceled', 'pending_payment', 'refund_order')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("DISTINCT sales_flat_order.increment_id as orderId, concat(sales_flat_order.customer_firstname, ' ', sales_flat_order.customer_lastname) AS name, sales_flat_order.grand_total as amount, sales_flat_order_payment.method AS method, total_qty_ordered AS quantity")
			->orderBy("sales_flat_order.grand_total", "DESC")
			->get()
			->take(5);
		$data = array();

		if (!empty($orders)) {
			foreach ($orders as $value) {

				$d['orderId']  = $value['orderId'];
				$d['name']     = $value['name'];
				$d['method']   = $value['method'];
				$d['amount']   = $value['amount'];
				$d['quantity'] = $value['quantity'];

				$data[] = $d;

			}
		}

		return $data;
	}

	static function orderByShipment($date) {

		$orders = SalesFlatOrder::join('sales_flat_shipment', 'sales_flat_order.entity_id', '=', 'sales_flat_shipment.order_id')
			->leftjoin('sales_flat_shipment_track', 'sales_flat_order.entity_id', '=', 'sales_flat_shipment_track.order_id')
			->whereRaw("sales_flat_order.status not in ( 'processing', 'pending_payment', 'canceled', 'cod')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw(" count(sales_flat_order.increment_id) as totalOrders,
											sum(if(sales_flat_shipment_track.carrier_code='bluedart', 1, 0)) AS bluedart,
											sum(if(sales_flat_order.status='delivered' OR  sales_flat_order.status='refund_order', 1, 0)) AS deliveredOrder,
											sum(if((sales_flat_order.status='delivered' OR sales_flat_order.status='refund_order') AND sales_flat_shipment_track.carrier_code='bluedart', 1, 0)) AS bluedartDeliveredOrder
											")
			->get();
		$data                       = array();
		$data['totalOrders']        = 0;
		$data['bluedart']           = 0;
		$data['others']             = 0;
		$data['delivered']          = 0;
		$data['delivered_others']   = 0;
		$data['delivered_bluedart'] = 0;

		if (!empty($orders)) {
			foreach ($orders as $value) {

				$data['totalOrders']        = $value['totalOrders'];
				$data['bluedart']           = $value['bluedart'];
				$data['others']             = $value['totalOrders']-$value['bluedart'];
				$data['delivered']          = $value['deliveredOrder'];
				$data['delivered_bluedart'] = $value['bluedartDeliveredOrder'];
				$data['delivered_others']   = $value['deliveredOrder']-$value['bluedartDeliveredOrder'];

			}
		}

		return $data;
	}

	/**
	 * customers who purchased in last 15 days
	 * @return customers
	 *
	 */

	static function getPurchansedUsers() {

		$customers = SalesFlatOrder::whereRaw("date(created_at) >= date(now() - INTERVAL 15 Day)")
			->select('customer_id as customerId', 'customer_email AS email', 'customer_firstname AS name')
			->groupBy('customer_email')
			->get();
		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer->email;
			}
		}

		return $data;

	}

	/**
	 * customers who purchased in last 15 days
	 * @return customers
	 *
	 */

	static function getUserGotDeliveredYesterday() {

		//"SELECT sales_flat_order.entity_id AS orderId, custdetails.firstname AS customerName, custdetails.email AS customerEmail, custdetails.telephone AS customerMobile FROM `sales_flat_order`
		// INNER JOIN `sales_flat_order_status_history` ON `sales_flat_order`.`entity_id` = `sales_flat_order_status_history`.`parent_id`
		// INNER JOIN sales_flat_order_address AS custdetails ON sales_flat_order.customer_id = custdetails.customer_id
		// WHERE sales_flat_order.status NOT IN ('rto', 'canceled', 'refund_order')
		// AND custdetails.address_type = 'billing'
		// AND sales_flat_order.status IN ('delivered')
		// AND date(sales_flat_order.created_at) = date(now() - INTERVAL 7 DAY) GROUP BY customerMobile ORDER BY orderid DESC";

		$customers = SalesFlatOrder::join('sales_flat_order_status_history', 'sales_flat_order.entity_id', '=', 'sales_flat_order_status_history.parent_id')
			->join('sales_flat_order_address', 'sales_flat_order.billing_address_id', '=', 'sales_flat_order_address.entity_id')
			->whereRaw("sales_flat_order.status NOT IN ('rto', 'canceled', 'refund_order', 'refunded_credit') AND sales_flat_order_status_history.status in ('delivered')")
			->whereRaw("date(sales_flat_order_status_history.created_at) = date(now() - INTERVAL 1 DAY)")
			->whereRaw("sales_flat_order_address.address_type = 'billing'")
		// ->whereRaw("sales_flat_order.entity_id = '18823'")
		//->whereRaw("sales_flat_order.customer_id = '7471'")
			->selectRaw("sales_flat_order.entity_id AS orderId, sales_flat_order.customer_id AS customerId, sales_flat_order_address.firstname AS name, sales_flat_order_address.email AS email, sales_flat_order_address.telephone AS mobile")
			->orderBy("orderId", "DESC")
			->groupBy("sales_flat_order.entity_id")
			->get();

		//dd($customers);

		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	/**
	 * Confirmatio SLA
	 * average time taken for confirmation from time of order creation
	 * @return orders
	 *
	 */

	static function deliveryTimelineOrderConfirm($date) {

		$customers = SalesFlatOrder::join('sales_flat_order_status_history', 'sales_flat_order.entity_id', '=', 'sales_flat_order_status_history.parent_id')
		//->join('sales_flat_shipment', 'sales_flat_order.entity_id', '=', 'sales_flat_shipment.order_id')
			->whereRaw("sales_flat_order_status_history.status IN ('order_confirm')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count(DISTINCT sales_flat_order.entity_id) AS orders, sales_flat_order_status_history.status, DATEDIFF( sales_flat_order_status_history.created_at, sales_flat_order.created_at) AS days")
			->orderBy("days", "ASC")
			->groupBy("days")
			->get();

		//dd($customers);

		$data                = array();
		$data['0-1']         = 0;
		$data['2-3']         = 0;
		$data['4-5']         = 0;
		$data['6-7']         = 0;
		$data['7+']          = 0;
		$data['totalOrders'] = 0;

		if (!empty($customers)) {
			foreach ($customers as $value) {

				if ($value['days'] >= 8) {
					$data['7+'] += $value['orders'];
				} elseif ($value['days'] < 2) {
					$data['0-1'] += $value['orders'];
				} elseif ($value['days'] < 4) {
					$data['2-3'] += $value['orders'];
				} elseif ($value['days'] < 6) {
					$data['4-5'] += $value['orders'];
				} elseif ($value['days'] < 8) {
					$data['6-7'] += $value['orders'];
				}

				$data['totalOrders'] += $value['orders'];
			}
		}

		return $data;
	}

	/**
	 * Shipping SLA
	 * average time taken for shipping from time of confirmation
	 * @return orders
	 *
	 */

	static function deliveryTimelineOrderShipping($date) {

		$customers = SalesFlatOrder::join('sales_flat_order_status_history', 'sales_flat_order.entity_id', '=', 'sales_flat_order_status_history.parent_id')
		//->join('sales_flat_shipment', 'sales_flat_order.entity_id', '=', 'sales_flat_shipment.order_id')
			->whereRaw("sales_flat_order_status_history.status IN ('shipped')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count(DISTINCT sales_flat_order.entity_id) AS orders, sales_flat_order_status_history.status, DATEDIFF(  sales_flat_order_status_history.created_at, (SELECT created_at FROM sales_flat_order_status_history AS SFOS WHERE parent_id = sales_flat_order.entity_id AND status = 'order_confirm' ORDER BY SFOS.entity_id DESC LIMIT 1)) AS days")
			->orderBy("days", "ASC")
			->groupBy("days")
			->get();

		//dd($customers);

		$data                = array();
		$data['totalOrders'] = 0;
		$data['0-1']         = 0;
		$data['2-3']         = 0;
		$data['4-5']         = 0;
		$data['6-7']         = 0;
		$data['7+']          = 0;
		$data['totalOrders'] = 0;

		if (!empty($customers)) {
			foreach ($customers as $value) {

				if ($value['days'] >= 8) {
					$data['7+'] += $value['orders'];
				} elseif ($value['days'] < 2) {
					$data['0-1'] += $value['orders'];
				} elseif ($value['days'] < 4) {
					$data['2-3'] += $value['orders'];
				} elseif ($value['days'] < 6) {
					$data['4-5'] += $value['orders'];
				} elseif ($value['days'] < 8) {
					$data['6-7'] += $value['orders'];
				}

				$data['totalOrders'] += $value['orders'];
			}
		}

		return $data;
	}

	/**
	 * Delivery SLA
	 * average time taken for delivery from time of confirmation
	 * @return orders
	 *
	 */

	static function deliveryTimelineOrderDelivered($date) {

		$customers = SalesFlatOrder::join('sales_flat_order_status_history', 'sales_flat_order.entity_id', '=', 'sales_flat_order_status_history.parent_id')
		//->join('sales_flat_shipment', 'sales_flat_order.entity_id', '=', 'sales_flat_shipment.order_id')
			->whereRaw("sales_flat_order_status_history.status IN ('delivered')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count(DISTINCT sales_flat_order.entity_id) AS orders, sales_flat_order_status_history.status, DATEDIFF( sales_flat_order_status_history.created_at, (SELECT created_at FROM sales_flat_order_status_history AS SFOS WHERE parent_id = sales_flat_order.entity_id AND status = 'order_confirm' ORDER BY SFOS.entity_id DESC limit 1)) AS days")
			->orderBy("days", "ASC")
			->groupBy("days")
			->get();

		//dd($customers);

		$data = array();

		$data['0-1']         = 0;
		$data['2-3']         = 0;
		$data['4-5']         = 0;
		$data['6-7']         = 0;
		$data['7+']          = 0;
		$data['totalOrders'] = 0;

		if (!empty($customers)) {
			foreach ($customers as $value) {

				if ($value['days'] >= 8) {
					$data['7+'] += $value['orders'];
				} elseif ($value['days'] < 2) {
					$data['0-1'] += $value['orders'];
				} elseif ($value['days'] < 4) {
					$data['2-3'] += $value['orders'];
				} elseif ($value['days'] < 6) {
					$data['4-5'] += $value['orders'];
				} elseif ($value['days'] < 8) {
					$data['6-7'] += $value['orders'];
				}

				$data['totalOrders'] += $value['orders'];
			}
		}

		return $data;
	}

	static function getPendingPaymentOrders() {

		$orders = SalesFlatOrder::join('sales_flat_order_payment', 'sales_flat_order.entity_id', '=', 'sales_flat_order_payment.parent_id')
			->where("sales_flat_order.status", "pending_payment")
			->where("sales_flat_order_payment.method", "payubiz")
			->selectRaw("sales_flat_order.entity_id AS orderid, sales_flat_order.increment_id AS trans_id")
			->get();

		$data = array();

		if (!empty($orders)) {
			foreach ($orders as $order) {
				$data[] = $order;
			}
		}

		return $data;
	}

	static function getAllPendingPayments() {

		$orders = SalesFlatOrder::join('sales_flat_order_payment', 'sales_flat_order.entity_id', '=', 'sales_flat_order_payment.parent_id')
			->where("sales_flat_order.status", "pending_payment")
			->selectRaw("sales_flat_order.entity_id AS orderid, sales_flat_order_payment.method AS payment_method, sales_flat_order.increment_id AS increment_id, sales_flat_order.customer_id, sales_flat_order.customer_email, sales_flat_order.grand_total")
			->get();

		$data = array();

		if (!empty($orders)) {
			foreach ($orders as $order) {
				$data[] = $order;
			}
		}

		return $data;
	}

	static function ordersByQuantity($date) {

		$orders = SalesFlatOrder::whereRaw("status NOT IN ('canceled')")
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."' AND total_qty_ordered >= 1")
			->selectRaw("count(DISTINCT sales_flat_order.entity_id) AS orders, total_qty_ordered As quantity")
			->orderBy("orders", "DESC")
			->groupBy("quantity")
			->get();

		//dd($customers);

		$data  = array();
		$total = 0;

		if (!empty($orders)) {
			foreach ($orders as $order) {
				$d['quantity'] = $order['quantity'];
				$d['orders']   = $order['orders'];
				$data[]        = $d;
				$total += $order['orders'];
			}
		}

		if ($total) {
			$data['total'] = $total;
		}

		return $data;
	}

	/**
	 * average time taken for delivery to cities
	 * @return orders
	 */

	static function averageTimeOrdersDeliveredByCity($date) {
		$orders = SalesFlatOrder::join('sales_flat_order_status_history', 'sales_flat_order.entity_id', '=', 'sales_flat_order_status_history.parent_id')
			->join('sales_flat_order_address', 'sales_flat_order.billing_address_id', '=', 'sales_flat_order_address.entity_id')
			->whereRaw("sales_flat_order_status_history.status in ('delivered')")
			->whereRaw("date(sales_flat_order.created_at) between date(now() - INTERVAL 40 DAY) AND date(now() - INTERVAL 5 DAY)")
			->selectRaw("count(DISTINCT sales_flat_order.entity_id) AS orders, sum(DATEDIFF(sales_flat_order_status_history.created_at, sales_flat_order.created_at)) AS days, city")
			->orderBy("orders", "DESC")
			->groupBy("city")
			->get()	->take(20);

		$data = array();

		if (!empty($orders)) {

			foreach ($orders as $value) {

				$d['timeTaken'] = round($value['days']/$value['orders']);
				$d['city']      = $value['city'];
				$data[]         = $d;

			}
		}

		return $data;
	}

	/**
	 * Last 30 days customers
	 * @return customers
	 */

	static function last30DaysCustomers() {
		$customers = SalesFlatOrder::join('sales_flat_order_address', 'sales_flat_order.billing_address_id', '=', 'sales_flat_order_address.entity_id')
			->whereRaw("sales_flat_order.status = 'delivered' AND sales_flat_order.customer_email != '' AND date(sales_flat_order.created_at) > date(now() - INTERVAL 30 DAY)")
			->selectRaw("sales_flat_order.customer_email AS email, sales_flat_order.customer_firstname AS name, sales_flat_order_address.telephone AS mobile, sales_flat_order.customer_id")
			->groupBy("sales_flat_order.customer_email")
		//->limit(1)
			->get();

		//dd($customers);

		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	/**
	 * Exclude 30 days customers
	 * @return customers
	 */

	static function exclude30DaysCustomers() {
		$customers = SalesFlatOrder::join('sales_flat_order_address', 'sales_flat_order.billing_address_id', '=', 'sales_flat_order_address.entity_id')
			->whereRaw("sales_flat_order.status = 'delivered' AND sales_flat_order.customer_email != '' AND date(sales_flat_order.created_at) < date(now() - INTERVAL 30 DAY)")
			->selectRaw("sales_flat_order.customer_email AS email, sales_flat_order.customer_firstname AS name, sales_flat_order_address.telephone AS mobile, sales_flat_order.customer_id")
			->groupBy("sales_flat_order.customer_email")
		//->limit(1)
			->get();

		//dd($customers);

		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	/**
	 * All time 1 time customers (excluding last 30 days)
	 * @return customers
	 */

	static function oneTimeCustomers() {
		$customers = SalesFlatOrder::join('sales_flat_order_address', 'sales_flat_order.billing_address_id', '=', 'sales_flat_order_address.entity_id')
			->whereRaw("sales_flat_order.status = 'delivered' AND sales_flat_order.customer_email != ''")
			->selectRaw("sales_flat_order.customer_email AS email, sales_flat_order.customer_firstname AS name, sales_flat_order_address.telephone AS mobile, sales_flat_order.customer_id")
			->groupBy("sales_flat_order.customer_email")
			->havingRaw('count(DISTINCT sales_flat_order.entity_id) = 1')
		//->limit(1)
			->get();

		//dd($customers);

		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	/**
	 * All time 2 time customers (excluding last 30 days)
	 * @return customers
	 */

	static function twoTimeCustomers() {
		$customers = SalesFlatOrder::join('sales_flat_order_address', 'sales_flat_order.billing_address_id', '=', 'sales_flat_order_address.entity_id')
			->whereRaw("sales_flat_order.status = 'delivered' AND sales_flat_order.customer_email != ''")
			->selectRaw("sales_flat_order.customer_email AS email, sales_flat_order.customer_firstname AS name, sales_flat_order_address.telephone AS mobile, sales_flat_order.customer_id")
			->groupBy("sales_flat_order.customer_email")
			->havingRaw('count(DISTINCT sales_flat_order.entity_id) = 2')
		//->limit(1)
			->get();

		//dd($customers);

		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	/**
	 * All time 3 or 3+ time customers (excluding last 30 days)
	 * @return customers
	 */

	static function threeTimeCustomers() {
		$customers = SalesFlatOrder::join('sales_flat_order_address', 'sales_flat_order.billing_address_id', '=', 'sales_flat_order_address.entity_id')
			->whereRaw("sales_flat_order.status = 'delivered' AND sales_flat_order.customer_email != ''")
			->selectRaw("sales_flat_order.customer_email AS email, sales_flat_order.customer_firstname AS name, sales_flat_order_address.telephone AS mobile, sales_flat_order.customer_id")
			->groupBy("sales_flat_order.customer_email")
			->havingRaw('count(DISTINCT sales_flat_order.entity_id) >= 3')
		//->limit(1)
			->get();

		//dd($customers);

		$data = array();

		if (!empty($customers)) {
			foreach ($customers as $customer) {
				$data[] = $customer;
			}
		}

		return $data;
	}

	// /**
	//  * All time 1 time customers (last 30 days)
	//  * @return customers
	//  */

	// static function oneTimeCustomers30days() {
	// 	$customers = SalesFlatOrder::join('sales_flat_order_address', 'sales_flat_order.billing_address_id', '=', 'sales_flat_order_address.entity_id')
	// 		->whereRaw("sales_flat_order.status = 'delivered' AND sales_flat_order.customer_email != '' AND date(sales_flat_order.created_at) > date(now() - INTERVAL 30 DAY)")
	// 		->selectRaw("sales_flat_order.customer_email AS email, sales_flat_order.customer_firstname AS name, sales_flat_order_address.telephone AS mobile, sales_flat_order.customer_id")
	// 		->groupBy("sales_flat_order.customer_email")
	// 		->havingRaw('count(DISTINCT sales_flat_order.entity_id) = 1')
	// 	//->limit(1)
	// 		->get();

	// 	//dd($customers);

	// 	$data = array();

	// 	if (!empty($customers)) {
	// 		foreach ($customers as $customer) {
	// 			$data[] = $customer;
	// 		}
	// 	}

	// 	return $data;
	// }

	// /**
	//  * All time 2 time customers (last 30 days)
	//  * @return customers
	//  */

	// static function twoTimeCustomers30days() {
	// 	$customers = SalesFlatOrder::join('sales_flat_order_address', 'sales_flat_order.billing_address_id', '=', 'sales_flat_order_address.entity_id')
	// 		->whereRaw("sales_flat_order.status = 'delivered' AND sales_flat_order.customer_email != '' AND date(sales_flat_order.created_at) > date(now() - INTERVAL 30 DAY)")
	// 		->selectRaw("sales_flat_order.customer_email AS email, sales_flat_order.customer_firstname AS name, sales_flat_order_address.telephone AS mobile, sales_flat_order.customer_id")
	// 		->groupBy("sales_flat_order.customer_email")
	// 		->havingRaw('count(DISTINCT sales_flat_order.entity_id) = 2')
	// 	//->limit(1)
	// 		->get();

	// 	//dd($customers);

	// 	$data = array();

	// 	if (!empty($customers)) {
	// 		foreach ($customers as $customer) {
	// 			$data[] = $customer;
	// 		}
	// 	}

	// 	return $data;
	// }

	// /**
	//  * All time 3 or 3+ time customers (last 30 days)
	//  * @return customers
	//  */

	// static function threeTimeCustomers30days() {
	// 	$customers = SalesFlatOrder::join('sales_flat_order_address', 'sales_flat_order.billing_address_id', '=', 'sales_flat_order_address.entity_id')
	// 		->whereRaw("sales_flat_order.status = 'delivered' AND sales_flat_order.customer_email != '' AND date(sales_flat_order.created_at) > date(now() - INTERVAL 30 DAY)")
	// 		->selectRaw("sales_flat_order.customer_email AS email, sales_flat_order.customer_firstname AS name, sales_flat_order_address.telephone AS mobile, sales_flat_order.customer_id")
	// 		->groupBy("sales_flat_order.customer_email")
	// 		->havingRaw('count(DISTINCT sales_flat_order.entity_id) >= 3')
	// 	//->limit(1)
	// 		->get();

	// 	//dd($customers);

	// 	$data = array();

	// 	if (!empty($customers)) {
	// 		foreach ($customers as $customer) {
	// 			$data[] = $customer;
	// 		}
	// 	}

	// 	return $data;
	// }

	static function ordersStatusReport($date) {

		$orders = SalesFlatOrder::join('sales_flat_order_grid', 'sales_flat_order.entity_id', '=', 'sales_flat_order_grid.entity_id')
			->whereRaw("date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("date(sales_flat_order.created_at) as orderDate, GROUP_CONCAT(sales_flat_order.entity_id) AS entity_id, count(sales_flat_order.status) as statuscount, sales_flat_order.status")
			->groupBy("orderDate", 'status')
			->OrderBy('orderDate', 'ASC')
			->get();

		// echo '<pre>';
		// print_r($orders->toArray());

		$data                        = array();
		$data['delayedOrders']       = array();
		$data['deliveryescalations'] = array();
		$i                           = 0;
		$d                           = array();

		if (!empty($orders)) {
			foreach ($orders as $value) {

				$findstatus = array('pending', 'order_confirm', 'holded', 'processing', 'exchange_order', 'pending_payment', 'payment_review', 'shipped', 'urgent_shipping', 'qc_fail', 'qc_hold', 'product_na', 'it', 'fraud', 'undelivered');

				$zeroDaysStatus = array('urgent_shipping', 'qc_fail', 'qc_hold', 'product_na', 'fraud');
				$oneDaysStatus  = array('shipped', 'processing');
				$twoDaysStatus  = array('pending_payment', 'pending');

				if (in_array($value['status'], $findstatus)) {

					$entities = explode(',', $value['entity_id']);

					if ($value['status'] == 'exchange_order') {
						$day = 10;
					} else if (in_array($value['status'], $twoDaysStatus)) {
						$day = 2;
					} else if (in_array($value['status'], $oneDaysStatus)) {
						$day = 1;
					} else if (in_array($value['status'], $zeroDaysStatus)) {
						$day = 0;
					} else {
						$day = 3;
					}

					//print_r($entities);
					foreach ($entities as $entvalue) {

						$ordersval = SalesFlatOrder::join('sales_flat_order_status_history', 'sales_flat_order.entity_id', '=', 'sales_flat_order_status_history.parent_id')
						// ->leftjoin('picking', 'sales_flat_order.entity_id', '=', 'picking.parent_id')
							->whereRaw("date(sales_flat_order.created_at) <= date(now() - INTERVAL ".$day." Day)")
							->whereRaw("sales_flat_order.entity_id = ".$entvalue)
						//->whereRaw("sales_flat_order_status_history.comment IS NOT NULL")
							->selectRaw("date(sales_flat_order.created_at) as orderDate, sales_flat_order.entity_id, sales_flat_order.status as status, sales_flat_order_status_history.comment, sales_flat_order.increment_id, date(sales_flat_order_status_history.created_at) As commentDate, sales_flat_order_status_history.status As commentStatus")
							->selectRaw("(select pickingstatus from picking where picking.parent_id = sales_flat_order.entity_id ORDER BY pickingid DESC ) AS pickingstatus")
							->selectRaw("(select created_time from picking where picking.parent_id = sales_flat_order.entity_id ORDER BY pickingid DESC ) AS pickingDate")
							->OrderBy('sales_flat_order_status_history.created_at', 'DESC')
							->take(1)
							->get();
						// 	echo '<pre>';
						// print_r($ordersval->toArray());
						// die();

						if (!empty($ordersval)) {
							foreach ($ordersval as $val) {
								$d['comment']       = $val['comment'];
								$d['orderDate']     = $val['orderDate'];
								$d['commentDate']   = $val['commentDate'];
								$d['pickingDate']   = $val['pickingDate'];
								$d['pickingStatus'] = $val['pickingstatus'];
								$d['commentStatus'] = SalesOrderStatus::statusVal($val['status']);
								$d['orderId']       = $val['increment_id'];
								if ($d['commentStatus'] == 'Shipped' || $d['commentStatus'] == 'In Transit' || $d['commentStatus'] == 'At Hub') {
									$data['deliveryescalations'][] = $d;
								} else {

									$data['delayedOrders'][] = $d;

								}

							}
						}

						// echo '<pre>';

						// print_r($ordersval->toArray());
					}
				}
				$data['orderStatusReportView'][$value['orderDate']][$value['status']] = $value['statuscount'];

			}

		}

		return $data;
	}
	// SELECT count(DISTINCT sfo.entity_id), cr.reason,
	// date(sfo.created_at) AS order_created_at,
	// count(DISTINCT sfo.customer_id) AS customerCount,
	// date(sfo.updated_at) AS Order_updated_at,
	// GROUP_CONCAT(sfo.customer_id),
	// Sum(if((SELECT count(DISTINCT SFAI.entity_id) AS ordercount FROM sales_flat_order AS SFAI WHERE  SFAI.customer_id = sfo.customer_id AND SFAI.entity_id < sfo.entity_id AND status not in ('canceled', 'rto', 'refund_order')) >= 1, 1, 0)) AS OLD,
	// Sum(if((SELECT count(DISTINCT SFAI.entity_id) AS ordercount FROM sales_flat_order AS SFAI WHERE SFAI.customer_id = sfo.customer_id AND  SFAI.entity_id < sfo.entity_id AND status not in ('canceled', 'rto', 'refund_order')) >= 1, 0, 1)) AS NEW
	// FROM `sales_flat_order` AS sfo
	// INNER JOIN cancelreason AS cr ON sfo.entity_id = cr.order_id
	// WHERE cr.reason = 'rto'
	// AND date(sfo.updated_at) BETWEEN '2018-03-06' AND '2018-12-06'
	// GROUP BY date(sfo.updated_at)

	static function oldNewCustomerWithRto($date) {

		$orderDetail = SalesFlatOrder::join('cancelreason', 'sales_flat_order.entity_id', '=', 'cancelreason.order_id')
			->whereRaw("cancelreason.reason = 'rto'")
			->whereRaw("date(sales_flat_order.updated_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw("count(DISTINCT sales_flat_order.entity_id) AS totalRTO, cancelreason.reason, date(sales_flat_order.created_at) AS order_created_at, count(DISTINCT sales_flat_order.customer_id) AS customerCount, date(sales_flat_order.updated_at) AS Order_updated_at, GROUP_CONCAT(sales_flat_order.customer_id) AS CustomerId, Sum(if((SELECT count(DISTINCT SFAI.entity_id) AS ordercount FROM sales_flat_order AS SFAI WHERE  SFAI.customer_id = sales_flat_order.customer_id AND SFAI.entity_id < sales_flat_order.entity_id AND status not in ('canceled', 'rto', 'refund_order')) >= 1, 1, 0)) AS OLD, Sum(if((SELECT count(DISTINCT SFAI.entity_id) AS ordercount FROM sales_flat_order AS SFAI WHERE SFAI.customer_id = sales_flat_order.customer_id AND  SFAI.entity_id < sales_flat_order.entity_id AND status not in ('canceled', 'rto', 'refund_order')) >= 1, 0, 1)) AS NEW")
			->groupBy("Order_updated_at")
			->get();

		$data = array();

		if (!empty($orderDetail)) {
			foreach ($orderDetail as $value) {
				$data[] = $value->toArray();

			}
		}

		return $data;

	}

	static function getlastDayOrders() {
		$orderNo = SalesFlatOrder::select('entity_id', 'increment_id')
			->where('created_at', '>', Carbon::now()->subDays(30))
			->limit(10)	->get();
		$data = array();

		if (!empty($orderNo)) {
			foreach ($orderNo as $value) {
				$data[] = $value->toArray();

			}
		}

		return $data;
	}
	public function invoiceDetail() {
		return $this->hasOne('Dashboard\Data\Models\JtdInvoice', 'order_id', 'entity_id');
	}

	static function checkCancelOrder($orderID) {
		$checkCancel = SalesFlatOrder::select('status')
			->where('entity_id', '=', $orderID)
			->get();
		$cancel = 0;
		if (!empty($checkCancel)) {
			foreach ($checkCancel as $value) {

				if ($value['status'] == 'canceled') {
					$cancel = 1;
				}
			}
		}

		return $cancel;
	}

	/*** nainika code ***/

	static function yearlyRevenueReport($startyear = '', $endyear = '') {

		$sYear                  = $startyear;
		$eYear                  = $endyear;
		$totOnlineAmt[$sYear][] = 0;
		$totOnlineAmt[$eYear][] = 0;
		if ($startyear == '') {
			 $startyear = '2018-04-01';
		} else {
			  $startyear = $startyear.'-04-01';
		}

		$startYear1     = strtotime($startyear);
		$new_start_date = strtotime('+ 12 month -1 Day', $startYear1);
		 $new_start_date = date('Y-m-d', $new_start_date);

		if ($endyear == '') {
			$endyear = '2019-04-01';
		} else {
			 $endyear = $endyear.'-04-01';
		}

		$endYear1     = strtotime($endyear);
		$new_end_date = strtotime('+ 12 month -1 Day', $endYear1);
		 $new_end_date = date('Y-m-d', $new_end_date);

		$qry1 = "Select MONTH(created_at) as month, YEAR(created_at) as year, sum(grand_total) AS amount,order_currency_code from `sales_flat_order` where created_at between '".$startyear."' and '".$new_start_date."' and status not in ('canceled', 'pending_payment', 'holded', 'refund_order') group by MONTH(created_at),YEAR(created_at),order_currency_code ORDER BY created_at ASC";

		$qry2 = "Select MONTH(created_at) as month, YEAR(created_at) as year, sum(grand_total) AS amount,order_currency_code from `sales_flat_order` where  created_at between '".$endyear."' and '".$new_end_date."' and status not in ('canceled', 'pending_payment', 'holded', 'refund_order') group by MONTH(created_at),YEAR(created_at),order_currency_code ORDER BY created_at ASC";

		 
		$yearlyTurnover1 = DB::select($qry1);
		$yearlyTurnover2 = DB::select($qry2);
		$yearlyTurnover = array_merge($yearlyTurnover1, $yearlyTurnover2);
		$data           = array();

	

		foreach ($yearlyTurnover as $value) {
			$year    = $value->year;
			$month   = $value->month;
			$dateVal = $date = strftime("%F", strtotime($year."-".$month));

			if ($value->order_currency_code == 'USD') {
				$value->amount = $value->amount*68;
			}

			if (date("Y-m", strtotime($dateVal)) <= date("Y-m", strtotime($new_start_date))) {
				$totOnlineAmt[$sYear][] = $value->amount;
			} else {

				$totOnlineAmt[$eYear][] = $value->amount;
			}

			if (isset($data[$value->month][$value->year])) {
				$data[$value->month][$value->year] += $value->amount;
			} else {

				$data[$value->month][$value->year] = $value->amount;
			}
		}
		

		session(['TotOnlineRevenue' => $totOnlineAmt]);

		return $data;
	}

	static function yearlyRevenueReportOffline($startyear = '', $endyear = '') {

		$sYear                   = $startyear;
		$eYear                   = $endyear;
		$totOfflineAmt[$sYear][] = 0;
		$totOfflineAmt[$eYear][] = 0;
		if ($startyear == '') {
			$startyear = '2018-04-01';
		} else {
			$startyear = $startyear.'-04-01';
		}
		$startYear1     = strtotime($startyear);
		$new_start_date = strtotime('+ 12 month -1 Day', $startYear1);
		$new_start_date = date('Y-m-d', $new_start_date);

		if ($endyear == '') {
			$endyear = '2019-04-01';
		} else {
			$endyear = $endyear.'-04-01';
		}

		$endYear1     = strtotime($endyear);
		$new_end_date = strtotime('+ 12 month -1 Day', $endYear1);
		$new_end_date = date('Y-m-d', $new_end_date);
		
		$qry            = "Select MONTH(order_date) as month, YEAR(order_date) as year, sum(order_total) AS amount from `offline_order_details` where order_date between '".$startyear."' and '".$new_start_date."' or order_date between '".$endyear."' and '".$new_end_date."'  group by MONTH(order_date),YEAR(order_date) ORDER BY order_date ASC";
		$yearlyTurnover = DB::select($qry);
		$data           = array();
		foreach ($yearlyTurnover as $value) {
			$year    = $value->year;
			$month   = $value->month;
			$dateVal = $date = strftime("%F", strtotime($year."-".$month));

			if (date("Y-m", strtotime($dateVal)) <= date("Y-m", strtotime($new_start_date))) {
				$totOfflineAmt[$sYear][] = $value->amount;
			} else {

				$totOfflineAmt[$eYear][] = $value->amount;
			}

			$data[$value->month][$value->year] = $value->amount;
		}
		//dd($qry);

		session(['TotOfflineRevenue' => $totOfflineAmt]);

		return $data;
	}

	static function getGlobalCountry($date){
		
		$orders = SalesFlatOrder::join('sales_flat_order_address', 'sales_flat_order.entity_id', '=', 'sales_flat_order_address.parent_id')
			->whereRaw("status not in ('canceled', 'pending_payment', 'holded','refund_order')")
			->where('address_type','=', 'shipping')
			->whereRaw("order_currency_code = 'USD' and	date(sales_flat_order.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->groupBy('country_id')
			->orderBy('orders', 'DESC')
			->selectRaw("country_id, count(sales_flat_order.entity_id) AS orders,sum(sales_flat_order.grand_total) as total")
			->get();
		// ->where('status', 'delivered')
		//->whereRaw("date(sales_flat_order.created_at) >= date(now() - INTERVAL 30 DAY)")

		$data = array();

		if (!empty($orders)) {
			foreach ($orders as $value) {
				$country_code = strtolower($value['country_id']);
				$qry = "SELECT name from countries WHERE code = '".$country_code."'";
		   		 $country = DB::select($qry);
				$d['country_id']   = $country[0]->name.'('.$value['country_id'].')';
				$d['orders'] = $value['orders'];
				$d['total']  = number_format($value['total'],2)." $";
				$data[]      = $d;
			}
		}

		return $data;
	}

	static function getAffiliateValidationReport() {
		$qry = "SELECT increment_id AS OrderId, status, campaign AS CompaingId, created_at AS OrderDate, customer_id AS CustomerId, (case when ((SELECT count(customer_id) FROM sales_flat_order WHERE customer_id = CustomerId AND status IN ('delivered', 'partial_refund')) = 1) THEN 1 ELSE 0 END) as New, (case when ((SELECT count(customer_id) FROM sales_flat_order WHERE customer_id = CustomerId AND status IN ('delivered', 'partial_refund')) = 1) THEN 0 ELSE 1 END) as Old FROM sales_flat_order INNER JOIN `utm_campaign` ON FIND_IN_SET(entity_id, orderid) > 0 WHERE date(created_at) BETWEEN date(NOW()-INTERVAL 45 Day) AND date(NOW()-INTERVAL 15 Day) AND orderid != ''";

		
		$report = DB::select($qry);

		$data = array();

		if (!empty($report)) {
			foreach ($report as $value) {
				$data[] = $value;

			}
		}

		return $data;

	}

	static function getProductFGSteals($date){

		$productIds = $qry = array();
 		$productIds = SalesFlatOrder::whereRaw("created_at between '".$date['startDate']." 00:00:00' AND '".$date['endDate']." 23:59:59' AND  base_discount_amount != '0.0000' ")
			->selectRaw(" entity_id ")
			->get()->toArray();	

			if( !empty($productIds) ){
				foreach ($productIds as $key => $value) {
				$ent_id = $value['entity_id'];
				$qry = SalesFlatOrderItem::whereRaw("order_id = '".$ent_id."'")->selectRaw("sum(round(qty_ordered - qty_refunded - qty_canceled )) AS qty, product_id AS ProductId")->get()->toArray();
				}
			}

		return $qry;

	}


	

}
