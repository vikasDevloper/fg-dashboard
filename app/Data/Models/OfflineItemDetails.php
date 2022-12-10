<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineItemDetails extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'offline_item_details';

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

	/**
	 * Get Style Number List
	 *
	 *
	 */
	static function getOfflineStyleNumber() {

		$productStyleID = OfflineItemDetails::whereRaw("item_name Not like '%Test%' AND item_name IS NOT NULL ")
			->selectRaw('DISTINCT item_name as styleNumber')
			->get();

		$data = '';

		if (!empty($productStyleID)) {
			foreach ($productStyleID as $styleNumber) {
				$data[] = $styleNumber['styleNumber'];
			}
		}

		return $data;
	}

	/**
	 * Get offline sale quantity by style number SKU wise
	 *
	 *
	 */
	static function getOfflineSaleQuantity($styleNumber) {

		$offlineSaleQty = OfflineItemDetails::whereRaw("item_name = '".$styleNumber."'")
			->selectRaw('item_name, item_code, item_size, Sum(item_qty) as OfflineQuantity')
			->GroupBy('item_code')
			->get();

		$data = '';

		if (!empty($offlineSaleQty)) {
			foreach ($offlineSaleQty as $quantity) {
				$data[] = $quantity->toArray();
			}
		}

		return $data;
	}

	/**
	 * Get offline sale quantity by style number
	 *
	 *
	 */
	static function getOfflineSaleQuantity1($styleNumber) {

		$offlineSaleQty = OfflineItemDetails::whereRaw("item_name = '".$styleNumber."'")
			->selectRaw('item_name, Sum(item_qty) as OfflineQuantity')
			->get();

		$data = array();

		if (!empty($offlineSaleQty)) {
			foreach ($offlineSaleQty as $value) {

				$data['OfflineQuantity'] = $value['OfflineQuantity'];
				$data['style_no']        = $value['item_name'];
			}
		}

		return $data;
	}

	static function getOfflineTotalByProductIds($styleNumber) {

		$offlineSaleQty = OfflineItemDetails::whereRaw("item_name = '".$styleNumber."'")
			->selectRaw('sum(item_gross- item_cgst - item_sgst - item_igst) AS TotalSaleValue, SUM(ROUND(item_qty))')
			->get();

		$data = array();

		if (!empty($offlineSaleQty)) {
			foreach ($offlineSaleQty as $value) {

				$data['totOfflineSale']  = $value['TotalSaleValue'];
				$data['totQty']          = $value['item_name'];
			}
		}

		return $data;
	}
}
