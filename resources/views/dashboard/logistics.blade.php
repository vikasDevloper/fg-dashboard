@extends('layouts.app')

@section('content')
<div class="row">
  <!--   <h2 class="pull-left" style="width: 65%">Dashboard</h2>  -->
  <form action="{{ route('logistics-dashboard') }}" id="filter" name="filter" method="get">
    <button class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 10%">Apply</button>
    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 29%">
      <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
      <span></span> <b class="caret"></b>
    </div>
    <input type="hidden" name="start-date" id="start-date">
    <input type="hidden" name="end-date" id="end-date">

  </form>
</div>
<br>

<div class="row table-responsive" style="background-color: rgb(248, 248, 248);">
  <div class="table-responsive col-md-12">
  <h4>Order Snapshot</h4>
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Orders</th>
        <th>Order Confirmed</th>
        <th>Processing</th>

        <th>Ready To Ship</th>
        <th>Shipped</th>
        <th>In Transit</th>
        <th>Delivered</th>
        <th>Refunded Order</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Total Number of Orders : </td>
        <td><?php echo isset($data['unDeliveredOrder']["totalOrders"])?$data['unDeliveredOrder']["totalOrders"]:0;?></td>
        <td><?php echo isset($data['unDeliveredOrder']['order_confirm']['orders'])?$data['unDeliveredOrder']['order_confirm']['orders']:0;?></td>


        <td><?php echo isset($data['unDeliveredOrder']['processing']['orders'])?$data['unDeliveredOrder']['processing']['orders']:0;?>
          (<?php echo isset($data['unDeliveredOrder']['processing']['orders'])?number_format(($data['unDeliveredOrder']['processing']['orders']/$data['unDeliveredOrder']["totalOrders"])*100, 2):0;?>%)
        </td>


        <td><?php echo isset($data['unDeliveredOrder']['readytoship']['orders'])?$data['unDeliveredOrder']['readytoship']['orders']:0;?>

          (<?php echo isset($data['unDeliveredOrder']['readytoship']['orders'])?number_format(($data['unDeliveredOrder']['readytoship']['orders']/$data['unDeliveredOrder']["totalOrders"])*100, 2):0;?>%)

        </td>

        <td><?php echo isset($data['unDeliveredOrder']['shipped']['orders'])?$data['unDeliveredOrder']['shipped']['orders']:0;?>
         (<?php echo isset($data['unDeliveredOrder']['shipped']['orders'])?number_format(($data['unDeliveredOrder']['shipped']['orders']/$data['unDeliveredOrder']["totalOrders"])*100, 2):0;?>%)
       </td>

       <td><?php echo isset($data['unDeliveredOrder']['it']['orders'])?$data['unDeliveredOrder']['it']['orders']:0;?>
        (<?php echo isset($data['unDeliveredOrder']['it']['orders'])?number_format(($data['unDeliveredOrder']['it']['orders']/$data['unDeliveredOrder']["totalOrders"])*100, 2):0;?>%)
      </td>

      <td><?php echo isset($data['unDeliveredOrder']['delivered']['orders'])?$data['unDeliveredOrder']['delivered']['orders']:0;?>
        (<?php echo isset($data['unDeliveredOrder']['delivered']['orders'])?number_format(($data['unDeliveredOrder']['delivered']['orders']/$data['unDeliveredOrder']["totalOrders"])*100, 2):0;?>%)
      </td>

      <td><?php echo isset($data['unDeliveredOrder']['refund_order']['orders'])?$data['unDeliveredOrder']['refund_order']['orders']:0;?>
        (<?php echo isset($data['unDeliveredOrder']['refund_order']['orders'])?number_format(($data['unDeliveredOrder']['refund_order']['orders']/$data['unDeliveredOrder']["totalOrders"])*100, 2):0;?>%)
      </td>
    </tr>
  </tbody>
</table>
</div>
</div>

<br>

<div class="row">
  <div class="table-responsive col-md-6" style="border-right: 1px solid #ddd;">
    <h4>New/VS Repeat customer</h4>
    <table class="table">
      <thead>
        <tr>
          <th>Total Orders</th>
          <th>Unique Customers</th>
          <th>New Customers</th>
          <th>Repeat Customers</th>
        </tr>
      </thead>
        <tr>

          <td><?php echo isset($data['customers']['total'])?$data['customers']['total']:0;?></td>
          <td><?php echo isset($data['customers']['uniqueCustomer'])?$data['customers']['uniqueCustomer']:0;?></td>
          <td>
<?php if (isset($data['customers']['total']) && isset($data['customers']['repeatCustomer'])
	 && $data['customers']['total'] != 0) {
	echo $customersCal = $data['customers']['total']-$data['customers']['repeatCustomer'];
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
        </tr>
    </table>
  </div>

  <div class="table-responsive col-md-6">
    <h4>Prepaid/ COD Break up</h4>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>COD</th>
          <th>Prepaid</th>
          <th>Store Credit</th>
        </tr>
      </thead>
      <tbody>

        <tr>
          <td> Total Orders : </td>
          <td><?php echo isset($data['ordersByPaymentMethods']['order']['cashondelivery'])?$data['ordersByPaymentMethods']['order']['cashondelivery']:0;
?>  (<?php echo isset($data['ordersByPaymentMethods']['order']['cashondelivery'])?number_format(($data['ordersByPaymentMethods']['order']['cashondelivery']/$data['ordersByPaymentMethods']['totalOrders'])*100, 2):0;?>%)</td>
          <td><?php echo isset($data['ordersByPaymentMethods']['order']['payubiz'])?$data['ordersByPaymentMethods']['order']['payubiz']:0;
?>  (<?php echo isset($data['ordersByPaymentMethods']['order']['payubiz'])?number_format(($data['ordersByPaymentMethods']['order']['payubiz']/$data['ordersByPaymentMethods']['totalOrders'])*100, 2):0;?>%)</td>
          <td><?php echo isset($data['ordersByPaymentMethods']['order']['free'])?$data['ordersByPaymentMethods']['order']['free']:0;?> </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<br>

<div class="row" style="background-color: rgb(248, 248, 248);">
  <div class="table-responsive col-md-6" style="border-right: 1px solid #ddd;">
    <h4>Shipping and Delivered SLA from order confirm date</h4>
    @if(count($data['deliveryTimelineOrderConfirm']) > 0)
    <table class="table">
      <thead>
        <tr>
          <th>Days</th>
          <th>Confirm SLA (% Confirm SLA)</th>
          <th>Picked SLA (% Picked SLA)</th>
          <th>Packed SLA (% Packed SLA)</th>
          <th>Shipping SLA (% Shipping SLA)</th>
          <th>Delivery SLA (% Delivery SLA)</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data['deliveryTimelineOrderConfirm'] as $key => $orderConfirm)

          <tr>

            <td>{{$key}}</td>
            <td>{{ isset($data['deliveryTimelineOrderPicked'][$key] ) && ($data['deliveryTimelineOrderPicked']['totalOrders'] > 0) ? $orderConfirm . ' ( ' . number_format( $orderConfirm / $data['deliveryTimelineOrderConfirm']['totalOrders']*100, 1 ) . '% )' : 0 }}</td>

            {{-- <td>{{ $orderConfirm . ' ( ' . number_format( $orderConfirm / $data['deliveryTimelineOrderConfirm']['totalOrders']*100, 1 ) . '% )' }}</td> --}}

            <td>{{ isset($data['deliveryTimelineOrderPicked'][$key] ) && ($data['deliveryTimelineOrderPicked']['totalOrders'] > 0) ? $data['deliveryTimelineOrderPicked'][$key] . ' ( ' . number_format($data['deliveryTimelineOrderPicked'][$key]/$data['deliveryTimelineOrderPicked']['totalOrders']*100, 1) . '% )' : 0 }}</td>

            <td>{{ isset($data['deliveryTimelineOrderPacked'][$key]) && ($data['deliveryTimelineOrderPacked']['totalOrders'] > 0) ? $data['deliveryTimelineOrderPacked'][$key] . ' ( ' . number_format($data['deliveryTimelineOrderPacked'][$key] / $data['deliveryTimelineOrderPacked']['totalOrders']*100, 1) . '% )' : 0 }}</td>

            <td>{{ isset($data['deliveryTimelineOrderShipping'][$key] ) && ($data['deliveryTimelineOrderShipping']['totalOrders'] > 0) ? $data['deliveryTimelineOrderShipping'][$key] . ' ( ' . number_format($data['deliveryTimelineOrderShipping'][$key] / $data['deliveryTimelineOrderShipping']['totalOrders']*100, 1) . '% )' : 0 }}</td>

            <td>{{ isset($data['deliveryTimelineOrderDelivered'][$key]) && ($data['deliveryTimelineOrderDelivered']['totalOrders'] > 0) ? $data['deliveryTimelineOrderDelivered'][$key] . ' ( ' . number_format($data['deliveryTimelineOrderDelivered'][$key]/ $data['deliveryTimelineOrderDelivered']['totalOrders']*100, 1) . '% )' : 0 }}</td>

          </tr>
        @endforeach
      </tbody>
    </table>
    @endif
  </div>


  <div class="table-responsive col-md-6">
   <h4>Shipping and Delivered SLA from created date</h4>
     @if ($data['deliverySla']['totalOrders'] > 0)
    <table class="table">
      <thead>
        <tr>
          <th>Delivery time</th>
          <th>Delivery SLA (% Delivery SLA)</th>
          <th>Shipping SLA (% Shipping SLA)</th>
        </tr>

      </thead>
      <tbody>
        <tr>
          <td>0 to 1 day</td>

          <td><?php echo isset($data['deliverySla']['0-1'])?$data['deliverySla']['0-1'].' ( '.number_format($data['deliverySla']['0-1']/$data['deliverySla']['totalOrders']*100, 2).'% )':0;?></td>

          <td><?php echo isset($data['shippingSla']['0-1'])?$data['shippingSla']['0-1'].' ( '.number_format($data['shippingSla']['0-1']/$data['shippingSla']['totalOrders']*100, 2).'% )':0;?></td>
        </tr>
        <tr>
          <td>2 to 3 day</td>
          <td><?php echo isset($data['deliverySla']['2-3'])?$data['deliverySla']['2-3'].' ( '.number_format($data['deliverySla']['2-3']/$data['deliverySla']['totalOrders']*100, 2).'% )':0;?></td>

          <td><?php echo isset($data['shippingSla']['2-3'])?$data['shippingSla']['2-3'].' ( '.number_format($data['shippingSla']['2-3']/$data['shippingSla']['totalOrders']*100, 2).'% )':0;?></td>
        </tr>
        <tr>
          <td>4 to 5 day</td>
          <td><?php echo isset($data['deliverySla']['4-5'])?$data['deliverySla']['4-5'].' ( '.number_format($data['deliverySla']['4-5']/$data['deliverySla']['totalOrders']*100, 2).'% )':0;?></td>

          <td><?php echo isset($data['shippingSla']['4-5'])?$data['shippingSla']['4-5'].' ( '.number_format($data['shippingSla']['4-5']/$data['shippingSla']['totalOrders']*100, 2).'% )':0;?></td>
        </tr>
        <tr>
          <td>6 to 7 day</td>
          <td><?php echo isset($data['deliverySla']['6-7'])?$data['deliverySla']['6-7'].' ( '.number_format($data['deliverySla']['6-7']/$data['deliverySla']['totalOrders']*100, 2).'% )':0;?></td>

          <td><?php echo isset($data['shippingSla']['6-7'])?$data['shippingSla']['6-7'].' ( '.number_format($data['shippingSla']['6-7']/$data['shippingSla']['totalOrders']*100, 2).'% )':0;?></td>
        </tr>
        <tr>
          <td> 7 Day+</td>
          <td><?php echo isset($data['deliverySla']['7+'])?$data['deliverySla']['7+'].' ( '.number_format($data['deliverySla']['7+']/$data['deliverySla']['totalOrders']*100, 2).'% )':0;?></td>

          <td><?php echo isset($data['shippingSla']['7+'])?$data['shippingSla']['7+'].' ( '.number_format($data['shippingSla']['7+']/$data['shippingSla']['totalOrders']*100, 2).'% )':0;?></td>
        </tr>
        <tr>
          <td> Total>> </td>
          <td><?php echo isset($data['deliverySla']['totalOrders'])?$data['deliverySla']['totalOrders']:0;?></td>
          <td><?php echo isset($data['shippingSla']['totalOrders'])?$data['shippingSla']['totalOrders']:0;?></td>
        </tr>

      </tbody>
    </table>
    @endif
  </div>
</div>

<br>

<div class="row">
  <div class="table-responsive col-md-6" style="border-right: 1px solid #ddd; height: 365px; overflow: scroll;">
    <h4>Quantity wise no of orders <span style="font-size: 10px;"> scroll to see more..&nbsp;
&nbsp;
</span></h4>
    @if(count($data['ordersByQuantity']) > 0)
      <table class="table">
        <thead>
          <tr>
            <th>Quantity</th>
            <th>No. of Orders</th>
          </tr>
        </thead>
        <tbody>

        @foreach($data['ordersByQuantity'] as $ordersQuanity)
            @if(isset($ordersQuanity['quantity']))
              <tr>
                <td>{{(int)$ordersQuanity['quantity']}}</td>
                <td>{{$ordersQuanity['orders']}} ( {{number_format(($ordersQuanity['orders']/$data['ordersByQuantity']['total'])*100, 2)}}% )</td>
              </tr>
            @endif
          @endforeach
            <tr>
                <td>Total Orders</td>
                <td>{{$data['ordersByQuantity']['total']}}</td>
            </tr>
        </tbody>
      </table>
    @endif
  </div>

  <div class="table-responsive col-md-6" style="height: 365px; overflow: scroll;">
     @if (count($data['averageTimeOrdersDeliveredByCity']) > 0)
      <h4>Citywise Delivery times<!--30 days period (starting today - 5 days)-->
<span style="font-size: 10px;"> scroll to see more..
</span></h4>
      <table class="table">
        <thead>
          <tr>
            <th>Average Time Taken</th>
            <th>City</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data['averageTimeOrdersDeliveredByCity'] as $orders)
            <tr>
              <td>{{$orders['timeTaken']}}</td>
              <td>{{$orders['city']}}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif

  </div>
</div>

<br>

<div class="row" style="background-color: rgb(248, 248, 248);">
  <div class="table-responsive col-md-6" style="border-right: 1px solid #ddd;">
    <h4>Shipping service used to deliver</h4>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Total</th>
          <th>Bluedart</th>
          <th>Others</th>
        </tr>
      </thead>
      <tbody>

        <tr>
          <td>Orders</td>
          <td><?php echo $data['shipping']['totalOrders'];?></td>
          <td><?php echo $data['shipping']['bluedart'];?></td>
          <td><?php echo ($data['shipping']['others']);?></td>
        </tr>

        <tr>
          <td>Delivered</td>

          <td><?php echo $data['shipping']['delivered'];?></td>
          <td><?php echo $data['shipping']['delivered_bluedart'];?></td>
          <td><?php echo $data['shipping']['delivered_others'];?></td>
        </tr>
      </tbody>
    </table>

  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(function() {
    var start   = moment('{{$data['startDate']}}');
    var end     = moment('{{$data['endDate']}}');
    // end1 = end;
    function cb(start, end) {
      $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      $('#start-date').val(start.format('YYYY-MM-DD'));
      $('#end-date').val(end.format('YYYY-MM-DD'));
      //if(end1.format('YYYY-M-D') != end.format('YYYY-M-D')){
      //  end1 = end;
      //  $('#filter').submit();
      //}
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
    @endsection

