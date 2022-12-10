<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class SalesFlatInvoice extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'sales_flat_invoice';

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

	static function deliveredByInvoicedDateCod($date) {

		$arraydata = SalesFlatInvoice::join("sales_flat_order", "sales_flat_order.entity_id", "=", "sales_flat_invoice.order_id")
			->join("sales_flat_order_payment", "sales_flat_order.entity_id", "=", "sales_flat_order_payment.parent_id")
			->whereRaw("sales_flat_order.status = 'delivered' AND sales_flat_order_payment.method = 'cashondelivery' AND date(sales_flat_invoice.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw('date(sales_flat_invoice.created_at) AS invoicedDate, COUNT(*) AS OrderCount, ROUND(SUM(sales_flat_invoice.total_qty)) AS qty, ROUND(SUM(sales_flat_invoice.grand_total), 2) AS Amount')
			->groupBy("invoicedDate")
			->get();

		//dd($arraydata);

		$data = array();

		if (!empty($arraydata)) {
			foreach ($arraydata as $array) {
				$data[] = $array->toArray();
			}
		}

		return $data;
	}

	static function deliveredByInvoicedDatePrepaid($date) {

		$arraydata = SalesFlatInvoice::join("sales_flat_order", "sales_flat_order.entity_id", "=", "sales_flat_invoice.order_id")
			->join("sales_flat_order_payment", "sales_flat_order.entity_id", "=", "sales_flat_order_payment.parent_id")
			->whereRaw("sales_flat_order.status = 'delivered' AND sales_flat_order_payment.method != 'cashondelivery' AND date(sales_flat_invoice.created_at) between '".$date['startDate']."' AND '".$date['endDate']."'")
			->selectRaw('date(sales_flat_invoice.created_at) AS invoicedDate, COUNT(*) AS OrderCount, ROUND(SUM(sales_flat_invoice.total_qty)) AS qty, ROUND(SUM(sales_flat_invoice.grand_total), 2) AS Amount')
			->groupBy("invoicedDate")
			->get();

		//dd($arraydata);

		$data = array();

		if (!empty($arraydata)) {
			foreach ($arraydata as $array) {
				$data[] = $array->toArray();
			}
		}

		return $data;
	}

	static function getJtdBydate($stdate,$edate){
		
		/* query for specific date range
		$orderNo = SalesFlatInvoice::join("jtd_invoice", "jtd_invoice.order_id", "=", "sales_flat_invoice.order_id")
		->selectRaw('sales_flat_invoice.created_at , jtd_invoice.entity_id,jtd_invoice.order_id, jtd_invoice.invoice_no')
		->whereRaw("jtd_invoice.filename is NULL and sales_flat_invoice.created_at between '".$stdate."' and '".$edate."' ")
		->get(); 
         */

		// after 2019 invoice
         $orderNo = SalesFlatInvoice::join("jtd_invoice", "jtd_invoice.order_id", "=", "sales_flat_invoice.order_id")
		->selectRaw('sales_flat_invoice.created_at , jtd_invoice.entity_id,jtd_invoice.order_id, jtd_invoice.invoice_no')
		->whereRaw("jtd_invoice.filename is NULL and Year(sales_flat_invoice.created_at) >='2019'  ORDER BY `jtd_invoice`.`entity_id` DESC")
		->get(); 
         $data = array();
 
		if (!empty($orderNo)) {
			foreach ($orderNo as $value) {
				$data[$value->order_id] = $value->toArray();

			}
		}
 		return $data;
		
	}
	static function getJtdByorder($orderID){
		
		/* query for specific date range
		$orderNo = SalesFlatInvoice::join("jtd_invoice", "jtd_invoice.order_id", "=", "sales_flat_invoice.order_id")
		->selectRaw('sales_flat_invoice.created_at , jtd_invoice.entity_id,jtd_invoice.order_id, jtd_invoice.invoice_no')
		->whereRaw("jtd_invoice.filename is NULL and sales_flat_invoice.created_at between '".$stdate."' and '".$edate."' ")
		->get(); 
         */

		// after 2019 invoice
         $orderNo = SalesFlatInvoice::join("jtd_invoice", "jtd_invoice.order_id", "=", "sales_flat_invoice.order_id")
		->selectRaw('sales_flat_invoice.created_at , jtd_invoice.entity_id,jtd_invoice.order_id, jtd_invoice.invoice_no')
		->whereRaw("jtd_invoice.filename is NULL and Year(sales_flat_invoice.created_at) >='2019' and jtd_invoice.order_id = $orderID ORDER BY `jtd_invoice`.`entity_id` DESC ")
		->limit(1)->get(); 
         $data = array();
		if (!empty($orderNo)) {
			foreach ($orderNo as $value) {
				$data[$value->order_id] = $value->toArray();

			}
		}
 		return $data;
		
	}

	

}
