<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;
use Dashboard\Http\Controllers\Controller;
use Dashboard\Data\Models\SalesFlatOrder;
use Dashboard\Data\Models\SalesFlatOrderItem;
use Dashboard\Data\Models\SalesFlatOrderGrid;


class RevertOrder extends Controller
{
    public function confirmOrder($orderID = "")
    {
    	if($orderID!=""){
    	SalesFlatOrder::RevertCancel($orderID);
    	SalesFlatOrderItem::RevertCancelItem($orderID);
    	SalesFlatOrderGrid::where('entity_id',$orderID)
    	->update(['status' => 'order_confirm']);
        echo "Updated $orderID";
    }
    else

        echo "<div style='font-family : test'>no order selected EXHIBITIONS</div>";
    	echo "no order selected".$orderID;
        echo $style = "<style>
          @font-face {
		     font-family: 'test';
		     src:  url('/fonts/AvenirNextLTPro-Regular.otf');
		       /*font-weight: 400;*/
		       /*font-style: normal;*/
		   }
		  
        </style>
        ";
    }
}
