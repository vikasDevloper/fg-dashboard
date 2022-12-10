<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class SalesFlatCreditMemoGrid extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'sales_flat_creditmemo_grid';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;

	static function rtoData($date) {

		// $rto_orders = SalesFlatCreditMemoGrid::join('sales_flat_order_grid', 'sales_flat_creditmemo_grid.order_increment_id', '=', 'sales_flat_order_grid.increment_id')
		// 	->whereRaw("date(sales_flat_creditmemo_grid.created_at) between '".$date."' AND '".$date."'")
		// 	->whereRaw("sales_flat_order_grid.status in ('rto')")
		// 	->selectRaw("sales_flat_creditmemo_grid.order_increment_id, sales_flat_creditmemo_grid.grand_total, sales_flat_creditmemo_grid.billing_name, sales_flat_creditmemo_grid.base_currency_code")
		// 	->toSql();

		$rto_orders = SalesFlatCreditMemoGrid::join('sales_flat_order_grid', 'sales_flat_creditmemo_grid.order_increment_id', '=', 'sales_flat_order_grid.increment_id')
			->join('sales_flat_order_payment', 'sales_flat_creditmemo_grid.order_id', '=', 'sales_flat_order_payment.parent_id')
			->whereRaw("date(sales_flat_creditmemo_grid.created_at) >= date(now() - INTERVAL 24 hour)")
			->whereRaw("sales_flat_order_grid.status in ('rto')")
			->selectRaw("sales_flat_creditmemo_grid.order_increment_id, sales_flat_creditmemo_grid.grand_total, sales_flat_creditmemo_grid.billing_name, sales_flat_creditmemo_grid.base_currency_code, sales_flat_order_payment.method")
			->get();

		// dd($rto_orders);

		$data = array();

		$data = '<style> table { border-collapse: collapse; width: 100%; } td, th { border: 1px solid #dddddd; text-align: left; padding: 8px; } tr:nth-child(even) { background-color: #dddddd; }</style>';

		$data .= '<p>Hi Team,</p>';
		$data .= '<p>List of RTOs Created Today.</p>';

		$data .= '<table class="rtomailorder">
			    <tr>
			        <th style="text-align:left;" width="100px; ">Sr. No </th>
			        <th style="text-align:left;" width="150px; ">Order # </th>
			        <th style="text-align:left;" width="150px; ">Name </th>
			        <th style="text-align:left;" width="200px; ">Order Total</th>
			        <th style="text-align:left;" width="200px; ">Payment Method</th>
			    </tr>';

		$i = 1;

		if (!empty($rto_orders)):

		foreach ($rto_orders as $credit_memo_list):
		$data .= "<tr>
						<td>".$i."</td>
						<td>".$credit_memo_list['order_increment_id']."</td>
						<td>".$credit_memo_list['billing_name']."</td>
						<td>".$credit_memo_list['base_currency_code'].' '.number_format($credit_memo_list['grand_total'], 2)."</td>
						<td>".$credit_memo_list['method']."</td>";
		$data .= "</tr>";

		$i++;
		endforeach;
		$data .= '</table>';
		 else :
		$data .= 'No Record Found.';
		endif;

		return $data;
	}

}
