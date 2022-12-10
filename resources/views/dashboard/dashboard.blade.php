@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col-xs-12 col-md-8"><p style="font-size: 20px; color: #6389a8;">Page will auto refresh in <span id="timer" style="font-size: 24px; font-weight: bold; color: #9c3a7a;"></span></p></div>
  <div class="colxs-12 col-md-4">
    <form action="/" id="filter" name="filter" method="get">
      <button class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">Apply</button>
      <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; margin-right: 15px;">
          <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
          <span></span> <b class="caret"></b>
      </div>
      <input type="hidden" name="start-date" id="start-date">
      <input type="hidden" name="end-date" id="end-date">

    </form>
  </div>
</div>



<div class="row table-responsive" style="background-color: #f8f8f8;">

  <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>COD</th>
          <th>Order Confirmed</th>
          <th>Processing</th>
          <th>On Hold</th>
          <th>Ready To Ship</th>
          <th>Shipped</th>
          <th>In Transit</th>
          <th>Delivered</th>
          <th>Pending Payments</th>
          <th>Canceled</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Total Number of Orders : </td>
          <td><?php echo isset($data['unDeliveredOrder']['pending']['orders'])?$data['unDeliveredOrder']['pending']['orders']:0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['order_confirm']['orders'])?$data['unDeliveredOrder']['order_confirm']['orders']:0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['processing']['orders'])?$data['unDeliveredOrder']['processing']['orders']:0;?></td>
           <td><?php echo isset($data['unDeliveredOrder']['holded']['orders'])?$data['unDeliveredOrder']['holded']['orders']:0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['readytoship']['orders'])?$data['unDeliveredOrder']['readytoship']['orders']:0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['shipped']['orders'])?$data['unDeliveredOrder']['shipped']['orders']:0;?></td>
           <td><?php echo isset($data['unDeliveredOrder']['it']['orders'])?$data['unDeliveredOrder']['it']['orders']:0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['delivered']['orders'])?$data['unDeliveredOrder']['delivered']['orders']:0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['pending_payment']['orders'])?$data['unDeliveredOrder']['pending_payment']['orders']:0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['canceled']['orders'])?$data['unDeliveredOrder']['canceled']['orders']:0;?></td>
        </tr>
         <tr>
          <td>Total Revenue of Orders : </td>
          <td><?php echo isset($data['unDeliveredOrder']['pending']['amount'])?number_format($data['unDeliveredOrder']['pending']['amount'], 2):0;?></td>

          <td><?php echo isset($data['unDeliveredOrder']['order_confirm']['amount'])?number_format($data['unDeliveredOrder']['order_confirm']['amount'], 2):0;?></td>

           <td><?php echo isset($data['unDeliveredOrder']['processing']['amount'])?number_format($data['unDeliveredOrder']['processing']['amount'], 2):0;?></td>

           <td><?php echo isset($data['unDeliveredOrder']['holded']['amount'])?number_format($data['unDeliveredOrder']['holded']['amount'], 2):0;?></td>

           <td><?php echo isset($data['unDeliveredOrder']['readytoship']['amount'])?number_format($data['unDeliveredOrder']['readytoship']['amount'], 2):0;?></td>

          <td><?php echo isset($data['unDeliveredOrder']['shipped']['amount'])?number_format($data['unDeliveredOrder']['shipped']['amount'], 2):0;?></td>

           <td><?php echo isset($data['unDeliveredOrder']['it']['amount'])?number_format($data['unDeliveredOrder']['it']['amount'], 2):0;?></td>

          <td><?php echo isset($data['unDeliveredOrder']['delivered']['amount'])?number_format($data['unDeliveredOrder']['delivered']['amount'], 2):0;?></td>

          <td><?php echo isset($data['unDeliveredOrder']['pending_payment']['amount'])?number_format($data['unDeliveredOrder']['pending_payment']['amount'], 2):0;?></td>
           <td><?php echo isset($data['unDeliveredOrder']['canceled']['amount'])?number_format($data['unDeliveredOrder']['canceled']['amount'], 2):0;?></td>
        </tr>

      </tbody>
  </table>
  </div>

  <div class="row">
    <div class="table-responsive col-md-12">
      <table class="table">
        <thead>
          <tr>
            <th>Total Revenue(Only Online)</th>
            <th>Global Revenue(In USD)</th>
            <th>Total Session</th>
            <th>Total Uers</th>
            <th>Total Page Views</th>
            <th>Conversion rate</th>
            <th>Revenue per hit</th>
            <th>Adwords CPC</th>
            <th>Adwords Cost Per Conversion</th>
            <th>Adwords CTR</th>
            <th>Adwords Clicks</th>
            <th>Adwords Cost</th>
            <th>Offline Revenue</th>
            <th>Offline Qty</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo isset($data['unDeliveredOrder']['total'])?number_format($data['unDeliveredOrder']['total'], 2):0;?></td>
            <td><?php echo isset($data['unDeliveredOrder']['globalAmount'])?number_format($data['unDeliveredOrder']['globalAmount'], 2):0;?></td>

            <td><?php echo isset($data['analyticsData']['ga:sessions'])?$data['analyticsData']['ga:sessions']:0;?></td>
            <td><?php echo isset($data['analyticsData']['ga:users'])?$data['analyticsData']['ga:users']:0;?></td>
            <td><?php echo isset($data['analyticsData']['ga:pageviews'])?$data['analyticsData']['ga:pageviews']:0;?></td>

            <td>
<?php
$ConversionRate = 0;
$convFont       = '';

if (isset($data['analyticsData']['ga:sessions']) && isset($data['customers']['uniqueCustomer'])) {
	if (($data['analyticsData']['ga:sessions'] > 0) && ($data['customers']['uniqueCustomer'] > 0)) {
		$ConversionRate = number_format($data['customers']['uniqueCustomer']/$data['analyticsData']['ga:sessions']*100, 2);
		$convFont       = '';
		if ($ConversionRate < .80) {
			$convFont = 'red';
		}
	}
}

echo '<span style="color: '.$convFont.'">'.$ConversionRate.'%</span>';?>
</td>
            <td>
<?php
$revenuePerHit = 0;
$convFont      = '';
if (isset($data['analyticsData']['ga:sessions']) && isset($data['unDeliveredOrder']['total'])) {
	if (($data['analyticsData']['ga:sessions'] > 0) && ($data['unDeliveredOrder']['total'] > 0)) {
		$revenuePerHit = number_format($data['unDeliveredOrder']['total']/$data['analyticsData']['ga:sessions'], 2);
	}
}

if ($revenuePerHit <= 30) {
	$convFont = 'red';
}
echo '<span style="color: '.$convFont.'">'.$revenuePerHit.'</span>';
?>
            </td>

            <td><?php echo isset($data['analyticsData']['ga:CPC'])
?number_format($data['analyticsData']['ga:CPC'], 2):0;?>
            </td>

            <td><?php echo isset($data['analyticsData']['transactionsData']['transactions']) && !empty($data['analyticsData']['transactionsData']['transactions'])
?number_format($data['analyticsData']['ga:adCost']/$data['analyticsData']['transactionsData']['transactions'], 2):0;
?>
            </td>

            <td><?php echo isset($data['analyticsData']['ga:CTR'])
?number_format($data['analyticsData']['ga:CTR'], 2):0;?>
            </td>
            <td><?php echo isset($data['analyticsData']['ga:adClicks'])
?number_format($data['analyticsData']['ga:adClicks'], 2):0;?>
            </td>
            <td><?php echo isset($data['analyticsData']['ga:adCost'])
?number_format($data['analyticsData']['ga:adCost'], 2):0;?>
            </td>
              <td><?php echo isset($data['offlineDetails']['totalAmount'])?number_format($data['offlineDetails']['totalAmount'], 2):0;?></td>
            <td><?php echo isset($data['offlineDetails']['totalQty'])?$data['offlineDetails']['totalQty']:0;?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

<div class="row" style="background-color: #f8f8f8;">
  <div class="table-responsive col-md-6">
    <h4>Orders by Payment Methods</h4>
     @if(!empty($data['ordersByPaymentMethods']) && !empty($data['ordersByPaymentMethods']['order']))
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            @foreach($data['ordersByPaymentMethods']['order'] AS $key => $value)
              <th>{{$key}}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          <tr>
            <td> Total Orders : </td>
            @foreach($data['ordersByPaymentMethods']['order'] AS $key => $value)
              <td><?php echo isset($value)?$value:0;
?>  (<?php echo $data['ordersByPaymentMethods']['totalOrders'] > 0?number_format(($value/$data['ordersByPaymentMethods']['totalOrders'])*100, 2):0;?>%)</td>
             @endforeach
          </tr>

           <tr>
            <td> Orders Amount: </td>
             @foreach($data['ordersByPaymentMethods']['amount'] AS $key => $value)
              <td><?php echo isset($value)?number_format($value, 2):0;
?> (<?php echo $data['ordersByPaymentMethods']['totalAmount'] > 0?number_format(($value/$data['ordersByPaymentMethods']['totalAmount'])*100, 2):0;?>%)</td>
            @endforeach
          </tr>
        </tbody>
      </table>
    @endif

      <div>
         <h4> Global Order By Countries </h4>
    @if(isset($data['globalCountry']) && !empty($data['globalCountry']))
    <table class="table">
      <thead>
        <tr>
          <th>Country</th>
          <th>No of Orders</th>
          <th>Total Amount</th>
        </tr>
      </thead>
      <tbody>
      <?php 
        foreach ($data['globalCountry'] as $key => $value) {?>
            <tr>
              <td>{{ $value['country_id'] }}</td>
              <td>{{ $value['orders']}}</td>
              <td>{{ $value['total']}}</td>
            </tr>
      <?php }?>
      </tbody>
      </table>
      @endif

      </div>

           <div>
         <h4> Sales By category </h4>
         @php
       //  print_r($data['saleCategory']);
         @endphp
    @if(isset($data['saleCategory']) && !empty($data['saleCategory']))
    <table class="table">
      <thead>
        <tr>
          <th>Category</th>
          <th>No of Orders</th>
        </tr>
      </thead>
      <tbody>
      <?php 
        foreach ($data['saleCategory'] as  $prodArr) {?>
            <tr>
              <td>{{ $prodArr['name'] }}</td>
              <td>{{ $prodArr['count'] . ' (' . round($prodArr['prcnt_val'],2) . '% )'}}</td>
            </tr>
      <?php }?>
      </tbody>
      </table>
      @endif

      </div>
   
    @if(!empty($data['deliveryByPaymentMethods']['order']))
     <h4>Orders Delivered by Payment Methods</h4>
      <table class="table">
          <thead>
            <tr>
              <th>#</th>
              @foreach($data['deliveryByPaymentMethods']['order'] AS $key => $value)
                <th>{{$key}}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            <tr>
              <td> Delivered Orders : </td>
              @foreach($data['deliveryByPaymentMethods']['order'] AS $key => $value)
                <td><?php echo isset($value)?$value:0;
?>  (<?php echo $data['deliveryByPaymentMethods']['totalOrders'] > 0?number_format(($value/$data['deliveryByPaymentMethods']['totalOrders'])*100, 2):0;?>%)</td>
              @endforeach
            </tr>

            <tr>
              <td> Delivered Orders Amount: </td>
              @foreach($data['deliveryByPaymentMethods']['amount'] AS $key => $value)
                <td><?php echo isset($value)?number_format($value, 2):0;
?> (<?php echo $data['deliveryByPaymentMethods']['totalAmount'] > 0?number_format(($value/$data['deliveryByPaymentMethods']['totalAmount'])*100, 2):0;?>%)</td>
              @endforeach
            </tr>

          </tbody>
      </table>
    @endif


  </div>

    <div class="table-responsive col-md-6">
       <h4> Orders </h4>
      <table class="table">
          <thead>
            <tr>

              <th>Total Orders</th>
              <th>Unique Customers</th>
              <th>New Customers</th>
              <th>Repeat Customers</th>
              <th>Average Ticket Size</th>
              <th>Average Order Qty</th>

            </tr>
          </thead>
          <tr>

            <td><?php echo isset($data['customers']['total'])?$data['customers']['total']:0;?></td>
            <td><?php echo isset($data['customers']['uniqueCustomer'])?$data['customers']['uniqueCustomer']:0;?></td>
            <td>
<?php if (isset($data['customers']['total']) && isset($data['customers']['newCustomer'])
	 && $data['customers']['total'] != 0) {
	echo $customersCal = $data['customers']['newCustomer'];
	echo ' ('.number_format(($customersCal/$data['customers']['total'])*100, 2).'%)';
}
?>
                          </td>
                          <td><?php
echo $data['customers']['repeatCustomer'];

if (isset($data['customers']['total']) && isset($data['customers']['repeatCustomer'])
	 && $data['customers']['total'] != 0) {
	echo ' ('.number_format(($data['customers']['repeatCustomer']/$data['customers']['total'])*100, 2).'%)';
}

?>
            </td>

            <td <?php echo $data['customers']['averageTicketSize'] < 2500?'style="color:red;"':0;
?> ><?php echo number_format($data['customers']['averageTicketSize'], 2);
?></td>
<td> {{number_format($data['customers']['averageOrderSize'], 2)}} </td>
          </tr>
      </table>

       <h4> User's funnel </h4>
       <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Cart</th>
            <th>Checkout</th>
            <th>Success</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Pageviews</td>
            <td><?php
            if(isset($data['analyticsPageviewsData']['cart']))
echo $data['analyticsPageviewsData']['cart'];

if (isset($data['analyticsData']['ga:users']) && !empty($data['analyticsData']['ga:users'])) {

	echo ' ('.number_format(($data['analyticsPageviewsData']['cart']/$data['analyticsData']['ga:users'])*100, 2).'%)';

}

?></td>
                            <td><?php 
                          if(isset($data['analyticsPageviewsData']['checkout']))

                            echo $data['analyticsPageviewsData']['checkout'];

if (isset($data['analyticsPageviewsData']['checkout']) && !empty($data['analyticsPageviewsData']['cart'])) {

	echo ' ('.number_format(($data['analyticsPageviewsData']['checkout']/$data['analyticsPageviewsData']['cart'])*100, 2).'%)';

}
?>
                            </td>
                            <td><?php 
                          if(isset($data['analyticsPageviewsData']['success']))
                            
                            echo $data['analyticsPageviewsData']['success'];
if (isset($data['analyticsPageviewsData']['success']) && !empty($data['analyticsPageviewsData']['checkout'])) {

	echo ' ('.number_format(($data['analyticsPageviewsData']['success']/$data['analyticsPageviewsData']['checkout'])*100, 2).'%)';

}
?>

            </td>
          </tr>
        </tbody>
      </table>


      <h4> Customer segmentation </h4>

      <table class="table">
          <thead>
            <tr>
              {{-- <th>#</th> --}}
              <th>Online Registered (Email)</th>
              <th>Online Buyers (Email)</th>
              <th>Offline Buyers (Mobile)</th>
              <th>Subscribers (Email)</th>
              <th>Subscribers (Mobile)</th>
            </tr>
          </thead>
          <tbody>
              <tr>
                {{-- <td>Customers</td> --}}
                <td><?php echo $data['onlineCustomers'][0];?></td>
                <td><?php echo $data['onlineCustomers'][1];?></td>
                <td><?php echo isset($data['offlineCustomers']) ? $data['offlineCustomers'] : 0;?></td>
                <td><?php echo isset($data['allSubscribers']) ? $data['allSubscribers'] : 0;?></td>
                <td><?php echo isset($data['allSubscribersMobile']) ? $data['allSubscribersMobile'] : 0; ?></td>
              </tr>
          </tbody>
      </table>
    </div>
   </div>

 <div class="row">
    <div class="table-responsive col-md-6">
    <h4> Top 5 orders </h4>
      <table class="table">
        <thead>
          <tr>
            <th>Order Number</th>
            <th>Customer Name</th>
            <th>Order Value</th>
            <th>Quantity</th>
            <th>Payment Mode</th>
          </tr>
        </thead>
        <tbody>

<?php foreach ($data['top5Orders'] as $key => $value) {?>
						          <tr>
						              <td><?php echo $value['orderId'];?></td>
						              <td><?php echo $value['name'];?></td>
						              <td><?php echo $value['amount'];?></td>
						              <td><?php echo number_format($value['quantity'], 0);?></td>
						              <td><?php echo $value['method'];?></td>
						          </tr>
	<?php }?>
</tbody>
      </table>
    </div>
    <div class="table-responsive col-md-6">
      <h4> Last 5 orders </h4>
      <table class="table">

        <thead>
          <tr>
            <th>Order Number</th>
            <th>Customer Name</th>
            <th>Order Value</th>
            <th>Quantity</th>
            <th>Payment Mode</th>
          </tr>
        </thead>

        <tbody>

<?php foreach ($data['last5Orders'] as $key => $value) {?>
						          <tr>
						            <td><?php echo $value['orderId'];?></td>
						            <td><?php echo $value['name'];?></td>
						            <td><?php echo $value['amount'];?></td>
						            <td><?php echo number_format($value['quantity'], 0);?></td>
						            <td><?php echo $value['method'];?></td>
						          </tr>
	<?php }?>
</tbody>
    </table>
  </div>
</div>

<div class="row" style="background-color: #f8f8f8;">
  <div class="table-responsive col-md-6">
    <h4> Timewise orders </h4>
    <div style="height: 550px; overflow: scroll;">
    <table class="table">
      <thead>
        <tr>
          <th>Time Slot</th>
          <th>Orders</th>
        </tr>
      </thead>
      <tbody>
<?php foreach ($data['ordersByTime'] as $value) {?>
						        <?php if ($value['orders'] > 0) {?>
												          <tr>
												            <td><?php echo $value['timeSlot'];?></td>
												            <td><?php echo $value['orders'];
		echo ' ('.number_format(($value['orders']/$data['ordersByTime']['total'])*100, 2).'%)';
		?></td>
												          </tr>
		<?php }
}?>
</tbody>
    </table>
  </div>
 </div>

  <div class="table-responsive col-md-6" >
  <h4> Products Sold </h4>

  <div style="height: 400px; overflow: scroll;">
    <span style="font-size: 10px; float: right;"> (Scroll to see more)</span>
     <table class="table">
        <thead>
          <tr>
            <th>Product Name</th>
            <th>XXS</th>
            <th>XS</th>
            <th>S</th>
            <th>M</th>
            <th>L</th>
            <th>XL</th>
            <th>XXL</th>
            <th>3XL</th>
            <th>Total</th>
          </tr>
        </thead>
        @if(isset($data['ordersSold']['items']) && !empty($data['ordersSold']['items']))
        <tbody>
<?php $totalPieces = 0;
//echo '<pre>';print_r($data['ordersSold']);die();
foreach ($data['ordersSold']['items'] as $value) {

	?>

  @if($value['total'] <= 0)
                @continue
              @endif

<?php $totalPieces = $data['ordersSold']['total'];  ?>
						        <tr>

						          <td><a href="<?php echo config("app.site_url").config("app.seperator").str_replace(' ', '-', strtolower($value['name'])).'.html';?>" target="_blank"><?php echo isset($value['name'])?$value['name']:'';
	?></a></td>
                      <td><?php echo isset($value['XXS'])?$value['XXS']:0;?></td>
						          <td><?php echo isset($value['XS'])?$value['XS']:0;?></td>
						          <td><?php echo isset($value['S'])?$value['S']:0;?></td>
						          <td><?php echo isset($value['M'])?$value['M']:0;?></td>
						          <td><?php echo isset($value['L'])?$value['L']:0;?></td>
						          <td><?php echo isset($value['XL'])?$value['XL']:0;?></td>
						          <td><?php echo isset($value['XXL'])?$value['XXL']:0;?></td>
						          <td><?php echo isset($value['3XL'])?$value['3XL']:0;?></td>
						          <td><?php echo isset($value['total'])?$value['total'].' ('.number_format(($value['total']/$totalPieces)*100, 2).'%) ':0;?></td>

						        </tr>
	<?php }?>
          <tr>


            <td>Total</td>
            <td><?php echo isset($data['sizesTotal']['XXS'])?$data['sizesTotal']['XXS']:0;?></td>
            <td><?php echo isset($data['sizesTotal']['XS'])?$data['sizesTotal']['XS']:0;?></td>
            <td><?php echo isset($data['sizesTotal']['S'])?$data['sizesTotal']['S']:0;?></td>
            <td><?php echo isset($data['sizesTotal']['M'])?$data['sizesTotal']['M']:0;?></td>
            <td><?php echo isset($data['sizesTotal']['L'])?$data['sizesTotal']['L']:0;?></td>
            <td><?php echo isset($data['sizesTotal']['XL'])?$data['sizesTotal']['XL']:0;?></td>
            <td><?php echo isset($data['sizesTotal']['XXL'])?$data['sizesTotal']['XXL']:0;?></td>
            <td><?php echo isset($data['sizesTotal']['3XL'])?$data['sizesTotal']['3XL']:0;?></td>
            <td><?php echo isset($totalPieces)? $totalPieces:0;?></td>

          </tr>
        <tr>
         <td>Total Pieces Ordered</td>
         <td><?php echo $totalPieces;?></td>
        </tr>

        </tbody>
        @endif
    </table>

  </div>

    <h4> Subscribers by Date </h4>
    @if(isset($data['subscribers']) && !empty($data['subscribers']))
    @if(!empty($data['analyticsData']['ga:sessions']) && ($data['analyticsData']['ga:sessions'] > 0))

<table class="table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Subscribers</th>
        </tr>
      </thead>
      <tbody>
<?php foreach ($data['subscribers'] as $key => $value) {?>
						            <tr>
						              <td><?php echo $key;?></td>
						              <td><?php echo $value.' ( '.number_format(($value/$data['analyticsData']['ga:sessions'])*100, 2).'% )';?></td>
						            </tr>
	<?php }?>
</tbody>
      </table>
      @endif
      @endif


  </div>
</div>
@php
//print_r($data['fgstealsCategory']);
@endphp
<div class="row">
  <div class="table-responsive col-md-6"> 
    <h4>FG STEALS Category</h4>
      <div style="height: 400px; overflow: scroll;">
       @foreach ($data['fgstealsCategory'] as $value)
            {{ $value['qty'] }}
          @endforeach
      </div>
    </div>
</div>
<?php /*   <div class="row">
<div class="table-responsive col-md-6">
<h4>Orders By Campaign </h4>
@if(!empty($data['utmConversions']))
<div style="height: 400px; overflow: scroll;">
<table class="table">
<thead>
<tr>
<th>Utm Campaign</th>
<th>Utm Source</th>
<th>Orders</th>
</tr>
</thead>
<tbody>
<?php foreach ($data['utmConversions'] as $value) {?>
<tr>
<td><?php echo $value['campaign'];?></td>
<td><?php echo $value['source'];?></td>
<td><?php echo $value['orders'];?></td>
</tr>
<?php }?></tbody>
</table>
</div>
@endif
</div>

<div class="table-responsive col-md-6">
<h4>Revenue By City </h4>
<div style="height: 400px; overflow: scroll;">
<table class="table">
<thead>
<tr>
<th>City</th>
<th>Revenue</th>
<th>Orders</th>
</tr>
</thead>
<tbody>
<?php foreach ($data['revenueByCities'] as $value) {?>
<tr>

<td><?php echo $value['city'];?></td>
<td><?php echo isset($data['unDeliveredOrder']['total'])?number_format($value['amount'], 2).' ('.number_format(($value['amount']/$data['unDeliveredOrder']['total'])*100, 2).'%)':$value['amount'];?></td>
<td><?php echo $value['orders'];?></td>

</tr>
<?php }?>
</tbody>
</table>
</div>
</div>
</div> */?>

<?php /* <div class="row" style="background-color: #f8f8f8;">
<div class="table-responsive col-md-6">
<h4> Last 5 Search Terms </h4>
<table class="table">
<thead>
<tr>
<th>Search Term</th>
<th>Results</th>
<th>Number of Uses</th>
</tr>
</thead>
<tbody>
<?php foreach ($data['lastestSearchTerms'] as $value) {?>
<tr>

<td><?php echo $value['searchTerm'];?></td>
<td><?php echo $value['numberOfResults'];?></td>
<td><?php echo $value['numberofUses'];?></td>

</tr>
<?php }?>
</tbody>
</table>
</div>
<div class="table-responsive col-md-6">
<H4> Top 5 Search Terms </H4>
<table class="table">

<thead>
<tr>
<th>Search Term</th>
<th>Results</th>
<th>Number of Uses</th>
</tr>
</thead>
<tbody>
<?php foreach ($data['popularSearchTerms'] as $value) {?>

<tr>

<td><?php echo $value['searchTerm'];?></td>
<td><?php echo $value['numberOfResults'];?></td>
<td><?php echo $value['numberofUses'];?></td>

</tr>
<?php }?>
</tbody>
</table>
</div>
</div> */?>

    <!-- Step 1: Create the containing elements. -->

<div id="embed-api-auth-container"></div>
<div id="chart-container"></div>
<div id="view-selector-container"></div>

@endsection

@section('scripts')
<script language="javascript" type="text/javascript">

function startTimer() {
  var presentTime = document.getElementById('timer').innerHTML;
  var timeArray = presentTime.split(/[:]+/);
  var m = timeArray[0];
  var s = checkSecond((timeArray[1] - 1));
  if(s==59){m=m-1}
  //if(m<0){alert('timer completed')}

  document.getElementById('timer').innerHTML =
    m + ":" + s;
  setTimeout(startTimer, 1000);
}

function checkSecond(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}

$(document).ready(function() {
  document.getElementById('timer').innerHTML =
  05 + ":" + 00;
  startTimer();
  setTimeout("location.reload(true)", 300000);
});
</script>
<script type="text/javascript">
  $(function() {

      var start = moment('<?php echo $data['startDate'];?>');
      var end = moment('<?php echo $data['endDate'];?>');

      // end1 = end;

      function cb(start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          $('#start-date').val(start.format('YYYY-MM-DD'));
          $('#end-date').val(end.format('YYYY-MM-DD'));
          // if(end1.format('YYYY-M-D') != end.format('YYYY-M-D')){
          //    end1 = end;
          //    $('#filter').submit();
          // }
      }

      $('#reportrange').daterangepicker({
          startDate: start,
          endDate: end,
          ranges: {
             'Today': [moment(), moment()],
             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
             'Last 30 Days': [moment().subtract(29, 'days'), moment()],
             'This Month': [moment().startOf('month'), moment().endOf('month')],
             'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          }
      }, cb);

      cb(start, end);

  });
</script>

<script>
  (function(w,d,s,g,js,fs){
  g=w.gapi||(w.gapi={});
g.analytics={q:[],ready:function(f){this.q.push(f);}};
  js=d.createElement(s);
fs=d.getElementsByTagName(s)[0];
  js.src='https://apis.google.com/js/platform.js';
  fs.parentNode.insertBefore(js,fs);
js.onload=function(){g.load('analytics');};
  }(window,document,'script'));
</script>

<script>

  gapi.analytics.ready(function() {

  /**
  * Authorize the user immediately if the user has already granted access.
  * If no access has been created, render an authorize button inside the
  * element with the ID "embed-api-auth-container".
  */
  gapi.analytics.auth.authorize({
  container: 'embed-api-auth-container',
  clientid: '503887013711-ma5dj9j9mj6951b5us1p98lp8hmnjcff.apps.googleusercontent.com'
  });


  /**
  * Create a new ViewSelector instance to be rendered inside of an
  * element with the id "view-selector-container".
  */
  var viewSelector = new gapi.analytics.ViewSelector({
  container: 'view-selector-container'
  });

  // Render the view selector to the page.
  viewSelector.execute();


  /**
  * Create a new DataChart instance with the given query parameters
  * and Google chart options. It will be rendered inside an element
  * with the id "chart-container".
  */
  var dataChart = new gapi.analytics.googleCharts.DataChart({
  query: {
    metrics: 'ga:sessions',
    dimensions: 'ga:date',
    'start-date': '30daysAgo',
    'end-date': 'yesterday'
  },
  chart: {
    container: 'chart-container',
    type: 'LINE',
    options: {
      width: '100%'
    }
  }
  });


  /**
  * Render the dataChart on the page whenever a new view is selected.
  */
  viewSelector.on('change', function(ids) {
  dataChart.set({query: {ids: ids}}).execute();
  });

  });
</script>
@endsection

@push('script')
<script>

 // axios.get('/oauth/clients')
   // .then(response => {
  //      console.log(response.data);
  //  });

  /*const data = {
    name: 'Client Name',
    redirect: 'http://devdashboard.faridagupta.com/api/callback'
};

axios.post('/oauth/clients', data)
    .then(response => {
        console.log(response.data);
    })
    .catch (response => {
        // List errors on response...
    });*/
  
</script>

@endpush
