<?php
/**
 * User: Komal Bhagat
 * Date: 16/03/18
 * Time: 2:00 PM
 */
namespace Dashboard\Classes\Helpers;
use Dashboard\Data\Models\CataloginventoryStockItem;
use Dashboard\Data\Models\CatalogProductEntity;
use Dashboard\Data\Models\CatalogProductEntityVarchar;
use Dashboard\Data\Models\CatalogProductSuperLink;

use Dashboard\Data\Models\SalesFlatOrderItem;

use Illuminate\Support\Facades\View;

class CreateMailTemplates {

	//put your code here
	private $site;

	/*
	 *	create template from blade file
	 */

	public function __construct() {
		$this->site = config('app.site_url');
	}

	public function createTemplateView($template_name, $template_data) {
		$emailContent = '';
		try {
			$emailContent = View::make($template_name)->with($template_data);
		} catch (\Exception $e) {
			echo $e->getMessage();
			return false;
		}

		return $emailContent;
	}

	/*
	 *  create under 1500 mail
	 */

	public function createMailUnder1500($utm_campaign) {

		$emailContent = '';

		$template_name = 'emails.transactional.under1500';
		$mcPreviewText = '';
		$homePageUrl   = config('app.site_url')."?utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;
		$under1500Url  = config('app.new_arrivals_url')."?price=38%2C37&utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;

		//get under 1500 kurtas
		$topwearPrice    = 1500;
		$bottomwearPrice = 1000;
		$limit           = 15;
		$kurtasList      = CatalogProductEntity::under1500Kurta($limit, $topwearPrice);
		$bottomsList     = CatalogProductEntity::under1000Bottom($limit, $bottomwearPrice);

		if (!empty($kurtasList) && !empty($bottomsList)) {

			$kurtasUnder1500 = array();
			$bottomUnder1000 = array();
			$kurtasCount     = 0;

			foreach ($kurtasList as $kurtas) {

				$associatedProducts = CatalogProductSuperLink::getAssociatedProducts($kurtas['product_id']);
				if (!empty($associatedProducts)) {
					$associatedQuantity      = 0;
					$associatedQuantityCount = 0;
					$lowQuantity             = 0;
					foreach ($associatedProducts as $associated) {
						$qty = CataloginventoryStockItem::getProductQuantity($associated['associatedProduct']);
						$associatedQuantity += $qty;
						if ($qty == 0) {
							$associatedQuantityCount++;
						}
					}

					if ($associatedQuantityCount >= 3) {
						continue;
					}

					$tunder1500Kurtas               = array();
					$tunder1500Kurtas['qty']        = $associatedQuantity;
					$productUrl                     = CatalogProductEntityVarchar::getProductUrlByProductId($kurtas['product_id']);
					$tunder1500Kurtas['productUrl'] = $this->site.'/'.$productUrl."?utm_source=email&utm_medium=cps&utm_campaign=".$utm_campaign."&utm_location=-1";

					$productName                         = CatalogProductEntityVarchar::getProductNameByProductId($kurtas['product_id']);
					$imageUrl                            = CatalogProductEntityVarchar::getProductImageUrlByProductId($kurtas['product_id']);
					$tunder1500Kurtas['productImageUrl'] = $this->site.'/media/catalog/product'.$imageUrl;

					if (strlen($productName) < 20) {
						$tunder1500Kurtas['productName'] = $productName;
					} else {
						$tunder1500Kurtas['productName'] = substr($productName, 0, 17).'...';
					}

					$tunder1500Kurtas['productPrice'] = (int) $kurtas['price'];

					$kurtasUnder1500[] = $tunder1500Kurtas;

					$kurtasCount++;

					if ($kurtasCount == 4) {
						break;
					}
				}
			}

			$bottomsTopSellers = array();
			$bottomsCount      = 0;

			foreach ($bottomsList as $bottom) {

				$associatedProducts = CatalogProductSuperLink::getAssociatedProducts($bottom['product_id']);

				if (!empty($associatedProducts)) {
					$associatedQuantity      = 0;
					$associatedQuantityCount = 0;
					$lowQuantity             = 0;
					foreach ($associatedProducts as $associated) {
						$qty = CataloginventoryStockItem::getProductQuantity($associated['associatedProduct']);
						$associatedQuantity += $qty;
						if ($qty == 0) {
							$associatedQuantityCount++;
							if ($associatedQuantityCount == 3) {
								$lowQuantity = 1;
							}
						}
					}

					if ($lowQuantity == 1) {
						continue;
					}

					$under1000Bottom               = array();
					$under1000Bottom['qty']        = $associatedQuantity;
					$productUrl                    = CatalogProductEntityVarchar::getProductUrlByProductId($kurtas['product_id']);
					$under1000Bottom['productUrl'] = $this->site.'/'.$productUrl."?utm_source=email&utm_medium=cps&utm_campaign=".$utm_campaign."&utm_location=-1";

					$productName                        = CatalogProductEntityVarchar::getProductNameByProductId($kurtas['product_id']);
					$imageUrl                           = CatalogProductEntityVarchar::getProductImageUrlByProductId($kurtas['product_id']);
					$under1000Bottom['productImageUrl'] = $this->site.'/media/catalog/product'.$imageUrl;

					if (strlen($productName) < 23) {
						$under1000Bottom['productName'] = $productName;
					} else {
						$under1000Bottom['productName'] = substr($productName, 0, 20).'...';
					}

					$under1000Bottom['productPrice'] = (int) $kurtas['price'];

					$bottomUnder1000[] = $under1000Bottom;

					$bottomsCount++;

					if ($bottomsCount == 4) {
						break;
					}
				}
			}

			try {
				$template_data = array(
					'siteUrl'          => $this->site,
					'mcPreviewText'    => $mcPreviewText,
					'homePageUrl'      => $homePageUrl,
					'under1500Url'     => $under1500Url,
					'under1500Kurtas'  => $kurtasUnder1500,
					'under1000Bottoms' => $bottomUnder1000,
				);
				$emailContent = $this->createTemplateView($template_name, $template_data);

			} catch (\Exception $e) {
				return false;
			}
		}
		//echo $emailContent;
		// exit();
		return $emailContent;

	}

	/*
	 *  create fgSteal mail
	 */

	public function createMailFgSteal($utm_campaign) {

		$emailContent = '';

		$template_name = 'emails.transactional.fg-steals';
		$mcPreviewText = 'Handpicked styles only for you!';
		$homePageUrl   = config('app.site_url')."?utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;
		$fgStealUrl    = config('app.fg_steal_url')."?utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;

		//get topseller steal for 30days
		$dayInterval = 30;
		$limit       = 30;

		$stealList = SalesFlatOrderItem::topSellerSteal($dayInterval, $limit);

		if (!empty($stealList)) {

			$stealTopSellers = array();
			$stealCount      = 0;

			foreach ($stealList as $steals) {

				$associatedProducts = CatalogProductSuperLink::getAssociatedProducts($steals['product_id']);
				if (!empty($associatedProducts)) {
					$associatedQuantity      = 0;
					$associatedQuantityCount = 0;
					$lowQuantity             = 0;
					foreach ($associatedProducts as $associated) {
						$qty = CataloginventoryStockItem::getProductQuantity($associated['associatedProduct']);
						$associatedQuantity += $qty;
						if ($qty == 0) {
							$associatedQuantityCount++;
						}
					}
					//check inventory
					if ($associatedQuantityCount >= 4) {
						continue;
					}

					$topSellerSteals                    = array();
					$topSellerSteals['qty']             = $associatedQuantity;
					$topSellerSteals['productUrl']      = $this->site.'/'.$steals['URL']."?utm_source=email&utm_medium=cps&utm_campaign=".$utm_campaign."&utm_location=-1";
					$topSellerSteals['productImageUrl'] = $this->site.'/media/catalog/product'.$steals['ImageUrl'];

					if (strlen($steals['product_name']) < 20) {
						$topSellerSteals['productName'] = $steals['product_name'];
					} else {
						$topSellerSteals['productName'] = substr($steals['product_name'], 0, 17).'...';
					}

					$topSellerSteals['productSpecialPrice'] = (int) $steals['special_price'];
					$topSellerSteals['productPrice']        = (int) $steals['Price'];

					$stealTopSellers[] = $topSellerSteals;

					$stealCount++;

					if ($stealCount == 6) {
						break;
					}
				}
			}

			//dd($stealTopSellers);

			try {
				$template_data = array(
					'siteUrl'         => $this->site,
					'mcPreviewText'   => $mcPreviewText,
					'homePageUrl'     => $homePageUrl,
					'fgStealUrl'      => $fgStealUrl,
					'topSellerSteals' => $stealTopSellers,
				);
				$emailContent = $this->createTemplateView($template_name, $template_data);

			} catch (\Exception $e) {
				return false;
			}
		}
		//echo $emailContent;
		// exit();
		return $emailContent;

	}

	/*
	 *  create topseller mail
	 */

	public function createMailTopSeller($utm_campaign) {
		$emailContent  = '';
		$template_name = 'emails.transactional.top-sellers';
		$mcPreviewText = '';
		$homePageUrl   = config('app.site_url')."?utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;
		$topSellersUrl = config('app.top_sellers_url')."?utm_source=email&utm_medium=cps&utm_location=-1&utm_campaign=".$utm_campaign;

		//get topseller kurtas for 15days
		$dayInterval = 15;
		$limit       = 15;

		$kurtasList = SalesFlatOrderItem::topSellerKurta($dayInterval, $limit);

		$bottomsList = SalesFlatOrderItem::topSellerBottom($dayInterval, $limit);

		if (!empty($kurtasList) && !empty($bottomsList)) {

			$kurtasTopSellers = array();
			$kurtasCount      = 0;

			foreach ($kurtasList as $kurtas) {

				$associatedProducts = CatalogProductSuperLink::getAssociatedProducts($kurtas['product_id']);
				if (!empty($associatedProducts)) {
					$associatedQuantity      = 0;
					$associatedQuantityCount = 0;
					$lowQuantity             = 0;
					foreach ($associatedProducts as $associated) {
						$qty = CataloginventoryStockItem::getProductQuantity($associated['associatedProduct']);
						$associatedQuantity += $qty;
						if ($qty == 0) {
							$associatedQuantityCount++;
						}
					}

					if ($associatedQuantityCount >= 3) {
						continue;
					}

					$topSellerKurtas                    = array();
					$topSellerKurtas['qty']             = $associatedQuantity;
					$topSellerKurtas['productUrl']      = $this->site.'/'.$kurtas['URL']."?utm_source=email&utm_medium=cps&utm_campaign=".$utm_campaign."&utm_location=-1";
					$topSellerKurtas['productImageUrl'] = $this->site.'/media/catalog/product'.$kurtas['ImageUrl'];

					if (strlen($kurtas['product_name']) < 20) {
						$topSellerKurtas['productName'] = $kurtas['product_name'];
					} else {
						$topSellerKurtas['productName'] = substr($kurtas['product_name'], 0, 17).'...';
					}

					$topSellerKurtas['productPrice'] = (int) $kurtas['Price'];

					$kurtasTopSellers[] = $topSellerKurtas;

					$kurtasCount++;

					if ($kurtasCount == 4) {
						break;
					}
				}
			}

			$bottomsTopSellers = array();
			$bottomsCount      = 0;

			foreach ($bottomsList as $bottom) {

				$associatedProducts = CatalogProductSuperLink::getAssociatedProducts($bottom['product_id']);

				if (!empty($associatedProducts)) {
					$associatedQuantity      = 0;
					$associatedQuantityCount = 0;
					$lowQuantity             = 0;
					foreach ($associatedProducts as $associated) {
						$qty = CataloginventoryStockItem::getProductQuantity($associated['associatedProduct']);
						$associatedQuantity += $qty;
						if ($qty == 0) {
							$associatedQuantityCount++;
							if ($associatedQuantityCount == 3) {
								$lowQuantity = 1;
							}
						}
					}

					if ($lowQuantity == 1) {
						continue;
					}

					$topSellerBottom                    = array();
					$topSellerBottom['qty']             = $associatedQuantity;
					$topSellerBottom['productUrl']      = $this->site.'/'.$bottom['URL']."?utm_source=email&utm_medium=cps&utm_campaign=".$utm_campaign."&utm_location=-1";
					$topSellerBottom['productImageUrl'] = $this->site.'/media/catalog/product'.$bottom['ImageUrl'];

					if (strlen($bottom['product_name']) < 23) {
						$topSellerBottom['productName'] = $bottom['product_name'];
					} else {
						$topSellerBottom['productName'] = substr($bottom['product_name'], 0, 20).'...';
					}

					$topSellerBottom['productPrice'] = (int) $bottom['Price'];

					$bottomsTopSellers[] = $topSellerBottom;

					$bottomsCount++;

					if ($bottomsCount == 4) {
						break;
					}
				}
			}

			try {
				$template_data = array(
					'siteUrl'          => $this->site,
					'mcPreviewText'    => $mcPreviewText,
					'homePageUrl'      => $homePageUrl,
					'topSellersUrl'    => $topSellersUrl,
					'topSellerKurtas'  => $kurtasTopSellers,
					'topSellerBottoms' => $bottomsTopSellers,
				);
				$emailContent = $this->createTemplateView($template_name, $template_data);

			} catch (\Exception $e) {
				return false;
			}
		}
		//echo $emailContent;
		// exit();
		return $emailContent;

	}
}