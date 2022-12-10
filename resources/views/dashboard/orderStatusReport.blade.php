<style type="text/css">
  .table-responsive::-webkit-scrollbar {
    display: none;
  };
  #fixed_column thead::-webkit-scrollbar {
    display: none;
  };

</style>@extends('layouts.app')
@section('content')

@php
// echo '<pre>';
// print_r($data['orderStatusReport']['delayedOrders']);
//$findstatus = array('pending', 'order_confirm', 'holded','it','undelivered');
$findstatus = array('pending_payment','shipped');
$redData = array('urgent_shipping', 'qc_fail', 'qc_hold', 'product_na','fraud');
//$oneDaysStatus  = array('cod');
@endphp


<div class="row" style="margin-bottom: 10px;">
  <div class="col-xs-12 col-md-8"><p style="font-size: 20px; color: #6389a8;">Shipping Dashboard will auto refresh in <span id="timer" style="font-size: 24px; font-weight: bold; color: #9c3a7a;"></span></p></div>
  <form action="{{ route('order-status') }}" id="filter" name="filter" method="get">
    <button class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">Apply</button>
    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; margin-right: 15px;">
        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        <span></span> <b class="caret"></b>
    </div>
    <input type="hidden" name="start-date" id="start-date">
    <input type="hidden" name="end-date" id="end-date">

  </form>
</div>

<div class="row">
  <div class="table-responsive col-md-12">
    <h2 style="margin-top:20px; text-align: center;"><img src="https://www.faridagupta.com/skin/frontend/ves_gentshop/default1/images/fg-logo.png" height="50" alt="Farida Gupta"> Shipping Dashboard</h2>

  </div>

</div>

@php
//dd($data['orderStatusReport']);
@endphp

<div class="row">

    <div class="table-responsive col-md-12"  >
    <h2>Order Status</h2>
    @if(!empty($data['orderStatusReport']['orderStatusReportView']))
        <table class="table table-bordered" id="fixed_column">
          <thead>
            <tr>
             <tr>
                   <th> Date </th>
                @foreach($data['statusLabel'][0] AS $key => $value)
                  @if($value['Label'] != 'Payment Review' && $value['Label'] != 'Refunded (Store Credits)')
                    <th> {{ $value['Label']}}</th>
                  @endif

                @endforeach
                  <th> Grand Total</th>

                  </tr>

            </tr>
          </thead>

        <tbody>
          <tr>
             @php
                 $i=0;$jr=1;$sum = 0;$res=0;
                 $grandTotal = array();
                 $orderStatusReport= $data['orderStatusReport']['orderStatusReportView'];

                 $fourdaysbefore   = date("Y-m-d", strtotime("-4 day"));
                 $threedaysbefore   = date("Y-m-d", strtotime("-3 day"));
                 $twodaysbefore     = date("Y-m-d", strtotime("-2 day"));
                 $onedaysbefore     = date("Y-m-d", strtotime("-1 day"));
                 $sevendaysbefore     = date("Y-m-d", strtotime("-8 day"));
                 $fivedaysbefore    = date("Y-m-d", strtotime("-6 day"));
                @endphp
                  @foreach($orderStatusReport AS $key => $value)
                  @php

                  $timestamp        = strtotime(array_keys($orderStatusReport)[$i]);
                  $dayval           = date("d", $timestamp);
                  $date             = date("d-M-Y", $timestamp);
                  $delaymore4days   = strtotime($fourdaysbefore) >= strtotime(array_keys($orderStatusReport)[$i]);
                  $delaymore3days   = strtotime($threedaysbefore) >= strtotime(array_keys($orderStatusReport)[$i]);
                  $delaymore2days   = strtotime($twodaysbefore) >= strtotime(array_keys($orderStatusReport)[$i]);
                  $delaymore1days   = strtotime($onedaysbefore) >= strtotime(array_keys($orderStatusReport)[$i]);
                  $delaymore7days  = strtotime($sevendaysbefore) >= strtotime(array_keys($orderStatusReport)[$i]);
                  $delaymore5days   = strtotime($fivedaysbefore) >= strtotime(array_keys($orderStatusReport)[$i]);

                  // if(isset($value['canceled'])){
                  //         $canceled_arr[] = $value['canceled'];
                  //         $a = array_sum($canceled_arr);
                          
                  //       }if(isset($value['cod'])){
                  //           $cod_arr[] = $value['cod'];
                  //           $b = array_sum($cod_arr);
                  //       }
                  //       if(isset($value['processing'])){
                  //           $processing_arr[] = $value['processing'];
                  //           $c = array_sum($processing_arr);
                  //       }
                  //       if(isset($value['order_confirm'])){
                  //           $confrm_arr[] = $value['order_confirm'];
                  //           $d = array_sum($processing_arr);
                  //       }

                  @endphp

                  <tr>
                   <td>{{ $date }}</td>

                   @foreach($data['statusLabel'][0] AS $key => $statuscode)
                   @php $dataval = '';

                  if( ($delaymore4days && ($statuscode['status_code'] == 'holded' )) || ($delaymore3days && (in_array($statuscode['status_code'], $findstatus))) || ($delaymore2days && ($statuscode['status_code'] == 'pending' || $statuscode['status_code'] =='processing' || $statuscode['status_code'] =='order_confirm' || $statuscode['status_code'] =='cod')) || ($delaymore5days && ($statuscode['status_code'] == 'undelivered' || $statuscode['status_code'] == 'it')) ||($delaymore7days && $statuscode['status_code'] == 'exchange_order') || in_array($statuscode['status_code'], $redData)) :
                    $dataval = "style='color:#FF0000; font-weight: bold;'";
                   endif;
                   $sum = 0;
                  @endphp
                  @if($statuscode['Label'] != 'Payment Review'  && $statuscode['Label'] != 'Refunded (Store Credits)')
                      <td>
                        <span  <?php echo $dataval;?>>
                        <?php echo $grandTotal[] = isset($value[$statuscode['status_code']])?$value[$statuscode['status_code']]:'';          
                        ?>
                      </span>
                      </td>
                  @endif
                    @endforeach
                   <td> <?php 
                   $grand_arr[] = array_sum($grandTotal);
                   echo array_sum($grandTotal);?></td>
                    <?php $grandTotal = '';?>
                  </tr>
                  @php  $i++; 
                        if(isset($value['exchange_order'])){
                          $exchange_arr[] = $value['exchange_order'];
                          $a = array_sum($exchange_arr);
                          
                        }if(isset($value['holded'])){
                            $hold_arr[] = $value['holded'];
                            $b = array_sum($hold_arr);
                        }
                        if(isset($value['order_confirm'])){
                            $cnf_arr[] = $value['order_confirm'];
                            $c = array_sum($cnf_arr);
                        }
                        if(isset($value['pending'])){
                            $pending_arr[] = $value['pending'];
                            $d = array_sum($pending_arr);
                        }
                         if(isset($value['pending_payment'])){
                          $paymnt_pending_arr[] = $value['pending_payment'];
                          $e = array_sum($paymnt_pending_arr);
                          
                        }if(isset($value['processing'])){
                            $processing_arr[] = $value['processing'];
                            $f = array_sum($processing_arr);
                        }
                        if(isset($value['shipped'])){
                            $shipped_arr[] = $value['shipped'];
                            $g = array_sum($shipped_arr);
                        }
                        if(isset($value['at_hub'])){
                            $hub_arr[] = $value['at_hub'];
                            $h = array_sum($hub_arr);
                        }
                         if(isset($value['canceled'])){
                          $canceled_arr[] = $value['canceled'];
                          $i = array_sum($canceled_arr);
                          
                        }if(isset($value['closed'])){
                            $closed_arr[] = $value['closed'];
                            $j = array_sum($closed_arr);
                        }
                        if(isset($value['cod'])){
                            $cod_arr[] = $value['cod'];
                            $k = array_sum($cod_arr);
                        }
                        if(isset($value['complete'])){
                            $completed_arr[] = $value['complete'];
                            $l = array_sum($completed_arr);
                        }
                        if(isset($value['delivered'])){
                          $delivered_arr[] = $value['delivered'];
                          $m = array_sum($delivered_arr);
                          
                        }if(isset($value['it'])){
                            $it_arr[] = $value['it'];
                            $n = array_sum($it_arr);
                        }
                        if(isset($value['partial_refund'])){
                            $partial_refund_arr[] = $value['partial_refund'];
                            $o = array_sum($partial_refund_arr);
                        }
                        if(isset($value['product_na'])){
                            $product_na_arr[] = $value['product_na'];
                            $p = array_sum($product_na_arr);
                        }
                         if(isset($value['qc_fail'])){
                          $qc_fail_arr[] = $value['qc_fail'];
                          $q = array_sum($qc_fail_arr);
                          
                        }if(isset($value['qc_hold'])){
                            $processing_arr[] = $value['qc_hold'];
                            $r = array_sum($processing_arr);
                        }
                        if(isset($value['refunded'])){
                            $refunded_arr[] = $value['refunded'];
                            $s = array_sum($refunded_arr);
                        }
                        if(isset($value['refunded_credit'])){
                            $refunded_credit_arr[] = $value['refunded_credit'];
                            $t = array_sum($refunded_credit_arr);
                        }
                         if(isset($value['rto'])){
                          $rto_arr[] = $value['rto'];
                          $u = array_sum($rto_arr);
                          
                        }
                        if(isset($value['fraud'])){
                            $fraud_arr[] = $value['fraud'];
                            $v = array_sum($fraud_arr);
                        }
                        if(isset($value['urgent_shipping'])){
                            $shipped_arr[] = $value['urgent_shipping'];
                            $w = array_sum($shipped_arr);
                        }
                        if(!empty($grand_arr)){
                            $x = array_sum($grand_arr);
                        }
                        

                  @endphp


                 @endforeach

                      <tr>
                        <td>Total</td>
                        @foreach($data['statusLabel'][0] AS $key => $statuscode)
                        @php
                        $con = 1;
                      
                          for( $kr ='a'; $kr <= 'x'; $kr++ ){
                              if( $con == $jr){
                                if( !empty($$kr)){
                                      echo '<td>';
                                  echo $$kr;
                                  echo '</td>';
                                }else{
                                  echo '<td>';
                                  echo '0';
                                  echo '</td>';
                                }
                              }
                              
                              $con++;
                          }
                               
                        @endphp

                      </td>
                 
                          @php $jr++; @endphp
                        @endforeach
                      </tr>
          </tr>

        </tbody>
</table>


      @endif
    </div>
  </div>




@if(!empty($data['orderStatusReport']['delayedOrders']))
  <div class="row">

      <h2>Potential Escalations</h2>
      <table class="table table-fixed">
        <thead>
          <tr>
            <th class="col-md-1 col-lg-1"> Order Id</th>
            <th class="col-md-1 col-lg-1"> Order Date </th>
            <th class="col-md-2 col-lg-2"> Current Status</th>
            <th class="col-md-1 col-lg-1"> Picking Status</th>
            <th class="col-md-2 col-lg-2"> Picking Date </th>
            <th class="col-md-1 col-lg-1"> Last Comment Date </th>
            <th class="col-md-4 col-lg-4"> Last Comment</th>
        </thead>

        <tbody>

          @foreach($data['orderStatusReport']['delayedOrders'] AS $key => $value)
          @php
          if(date("Y", strtotime($value['orderDate'])) <= '2017')
            continue;
          $picking = '';

          $orderDate    = date("d-M-Y", strtotime($value['orderDate']));
          $commentDate  = date("d-M-Y", strtotime($value['commentDate']));
          $pickingDate  = (!empty($value['pickingDate'])) ? date("d-M-Y", strtotime($value['pickingDate'])) : '';
          if($value['pickingStatus'] == '1')
            $picking = 'Picked';

          if($value['pickingStatus'] == '2')
            $picking = 'Packed';

          @endphp
          <tr>
            <td class="col-md-1 col-lg-1">{{ $value['orderId'] }}</td>
            <td class="col-md-1 col-lg-1">{{ $orderDate }}</td>
            <td class="col-md-2 col-lg-2">{{ $value['commentStatus'] }}</td>
            <td class="col-md-1 col-lg-1"><?php echo $picking;?></td>
            <td class="col-md-2 col-lg-2">{{ $pickingDate ? $pickingDate : ''}}</td>
            <td class="col-md-1 col-lg-1">{{ $commentDate }}</td>
            <td class="col-md-4 col-lg-4">{!!html_entity_decode($value['comment'])!!}</td>
          </tr>
          @endforeach

        </tbody>
      </table>


  </div>

   @endif

@if(!empty($data['orderStatusReport']['deliveryescalations']))

  <div class="row">

      <h2>Delivery Escalations</h2>
      <table class="table table-fixed">
        <thead>
          <tr>
            <th class="col-md-1 col-lg-1"> Order Id</th>
            <th class="col-md-1 col-lg-1"> Order Date </th>
            <th class="col-md-2 col-lg-2"> Current Status</th>
            <th class="col-md-1 col-lg-1"> Picking Status</th>
            <th class="col-md-2 col-lg-2"> Picking Date </th>
            <th class="col-md-1 col-lg-1"> Last Comment Date </th>
            <th class="col-md-4 col-lg-4"> Last Comment</th>
        </thead>

        <tbody>

          @foreach($data['orderStatusReport']['deliveryescalations'] AS $key => $value)
          @php
          if(date("Y", strtotime($value['orderDate'])) == '2017')
            continue;
          $picking = '';

          $orderDate    = date("d-M-Y", strtotime($value['orderDate']));
          $commentDate  = date("d-M-Y", strtotime($value['commentDate']));
          $pickingDate  = (!empty($value['pickingDate'])) ? date("d-M-Y", strtotime($value['pickingDate'])) : '';
          if($value['pickingStatus'] == '1')
            $picking = 'Picked';

          if($value['pickingStatus'] == '2')
            $picking = 'Packed';

          @endphp
          <tr>
            <td class="col-md-1 col-lg-1">{{ $value['orderId'] }}</td>
            <td class="col-md-1 col-lg-1">{{ $orderDate }}</td>
            <td class="col-md-2 col-lg-2">{{ $value['commentStatus'] }}</td>
            <td class="col-md-1 col-lg-1"><?php echo $picking;?></td>
            <td class="col-md-2 col-lg-2">{{ $pickingDate ? $pickingDate : ''}}</td>
            <td class="col-md-1 col-lg-1">{{ $commentDate }}</td>
            <td class="col-md-4 col-lg-4">{!!html_entity_decode($value['comment'])!!}</td>
          </tr>
          @endforeach

        </tbody>
      </table>
  </div>
 @endif
<br><br>
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
    30 + ":" + 00;
    startTimer();
    setTimeout("location.reload(true)", 1800000);
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
  $('#fixed_column tbody').scroll(function(e) {
    $('#fixed_column thead').css("left", -$("#fixed_column tbody").scrollLeft());
    $('#fixed_column thead th:nth-child(1)').css("left", $("#fixed_column tbody").scrollLeft());
    $('#fixed_column tbody td:nth-child(1)').css("left", $("#fixed_column tbody").scrollLeft());
  });
});

  $(function() {

      var start = moment('{{$data['startDate']}}');
      var end = moment('{{$data['endDate']}}');

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
@endsection

