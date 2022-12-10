@extends('layouts.app')
@section('content')
<?php ini_set('max_execution_time', 18000);?>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<div class="row" style="margin-bottom: 10px;">
<!--   <h2 class="pull-left" style="width: 65%">Dashboard</h2>  -->
  <div class="col-xs-12 col-md-8"><p style="font-size: 20px; color: #6389a8;">Marketing Dashboard will auto refresh in <span id="timer" style="font-size: 24px; font-weight: bold; color: #9c3a7a;"></span></p></div>
  <form action="{{ route('marketing-dashboard') }}" id="filter" name="filter" method="get">
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

      <table class="table">
        <thead>
           <tr>
            <th>Quantity</th>
            <th>SKUs</th>
            <th>Total Value</th>
        </thead>
        <tbody>
          <tr>

            <td>{{number_format($data['productsQuantities']['quantity'],0)}} </td>
            <td>{{ $data['productsQuantities']['skus']}} </td>
            <td>{{ number_format($data['productsQuantities']['totalValue'], 2)}}</td>

          </tr>
      </table>

      <table class="table">
        <thead>
          <tr>
            <th>Total Orders</th>
            <th>Total Revenue</th>
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
          </tr>
        </thead>
        <tbody>
          <tr>
             <td><?php echo isset($data['customers']['total'])?$data['customers']['total']:0;?></td>
            <td><?php echo isset($data['unDeliveredOrder']['total'])?number_format($data['unDeliveredOrder']['total'], 2):0;?></td>
            <td><?php echo isset($data['analyticsData']['ga:sessions'])?$data['analyticsData']['ga:sessions']:0;?></td>
            <td><?php echo isset($data['analyticsData']['ga:users'])?$data['analyticsData']['ga:users']:0;?></td>
            <td><?php echo isset($data['analyticsData']['ga:pageviews'])?$data['analyticsData']['ga:pageviews']:0;?></td>
            <td><?php
                $ConversionRate = 0;
                $convFont       = '';
                if (isset($data['analyticsData']['ga:sessions']) && isset($data['customers']['uniqueCustomer'])) {
                	$ConversionRate = number_format($data['customers']['uniqueCustomer']/$data['analyticsData']['ga:sessions']*100, 2);
                	$convFont       = '';
                	if ($ConversionRate < .80) {
                		$convFont = 'red';
                	}
                }

                echo '<span style="color: '.$convFont.'">'.$ConversionRate.'%</span>';
                ?>
              </td>
              <td><?php echo isset($data['analyticsData']['ga:sessions']) && isset($data['unDeliveredOrder']['total'])
                ?number_format($data['unDeliveredOrder']['total']/$data['analyticsData']['ga:sessions'], 2):0;?>
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
              </tr>
            </tbody>
          </table>
        </div>
      </div>

<div class="row">
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
<?php 
if(isset($data['revenueByCities']))
foreach ($data['revenueByCities'] as $value) {?>
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
  </div>

<div class="row">

  <div class="table-responsive col-md-6">
    <h3>SMS (Today)</h3>
     @if(count($data['mailsSentToday']) > 0)

      <table class="table">
        <thead>
          <tr>
            <th>SMS Type</th>
            <th>Added</th>
            <th>Sent</th>
          </tr>
        </thead>
        <tbody>
        @foreach($data['smsSentToday'] AS $key => $value)
          <tr>
            <td>{{$key}}</td>
            <td>{{$value['totalSms']}}</td>
            <td>{{$value['totalSmsSent']}}</td>
          </tr>
        @endforeach


        </tbody>
      </table>
    @endif

  </div>

  <div class="table-responsive col-md-6">
    <h3>Mails (Today)</h3>

    @if(count($data['mailsSentToday']) > 0)

      <table class="table">
        <thead>
          <tr>
            <th>Mails Type</th>
            <th>Added</th>
            <th>Sent</th>
          </tr>
        </thead>
        <tbody>
        @foreach($data['mailsSentToday'] AS $key => $value)
          <tr>
            <td>{{$key}}</td>
            <td>{{$value['totalEmails']}}</td>
            <td>{{$value['totalMailsSent']}}</td>
          </tr>
        @endforeach

        </tbody>
      </table>

    @endif

  </div>

  <div class="table-responsive col-md-6">
    <h3>Notification Log</h3>
     @if(count($data['notificationLog']) > 0)

      <table class="table">
        <thead>
          <tr>
            <th>Sent Date</th>
            <th>Type</th>
            <th>SMS/Email Tag</th>
            <th>Sent</th>
          </tr>
        </thead>
        <tbody>
        @foreach($data['notificationLog'] AS $key => $value)
          <tr>
            <td>{{$value['sent_at']}}</td>
            <td>{{$value['type']}}</td>
            <td>{{$value['tag']}}</td>
            <td>{{$value['count']}}</td>
          </tr>
          {{-- <tr>
            <td>{{$key}}</td>
            <td>
              <table class="table">
              @foreach($value AS $tag => $tagData)
                <tr>
                  <td>{{$tag}}</td>
                  <td>{{$tagData['type']}}</td>
                  <td>{{$tagData['count']}}</td>
                </tr>
              @endforeach
              </table>
            </td>
          </tr> --}}
        @endforeach


        </tbody>
      </table>
    @endif
  </div>

  <div class="table-responsive col-md-12">
    <h3>Product not sold past 10 days</h3>
    {{-- <h3>Product not sold past 10 days<span style="font-size: 10px;"> (Scroll to see more)</span></h3>
      <div style="height: 250px; overflow: scroll;"> --}}
    <div>
     @if(count($data['productsNotSelling']) > 0)
      <table class="table" id="dataTable">
        <thead>
          <tr>
            <th>Product Id</th>
            <th>Name</th>
            <th>XS</th>
            <th>S</th>
            <th>M</th>
            <th>L</th>
            <th>XL</th>
            <th>XXL</th>
            <th>3XL</th>
          </tr>
        </thead>
        <tbody>
          @php 
             $i = 3; 
             $qty = array();
          @endphp
     
      @foreach($data['productsNotSelling'] AS $key => $value)
      @php
      $stock = 0;
      for($m=3 ; $m<=9 ; $m++){
          if(isset($value['sizes'][$m]['qty']))
            $qty[$m]= $value['sizes'][$m]['qty'];
          else 
            $qty[$m] = 0;
          $stock = $stock + $qty[$m];

      }
      @endphp
        @if( $stock > 0 )
          <tr>
            <td>{{$value['product_id']}}</td>
            <td>
            <a target="_blank" href="https://www.faridagupta.com/{{$value['link']}}">{{$key}}</a>
            </td>
            @foreach($qty as $qtyval)
            <td>{{ isset($qtyval)?$qtyval:0 }}</td>
            @endforeach
            @php unset($qty)
             @endphp
          </tr>
          @endif
        @endforeach
        </tbody>
      </table>
    @endif
    </div>
  </div>
@php

@endphp
{{--
  <div class="table-responsive col-md-6">
    <h3>No. of Sessions created by source<span style="font-size: 10px;"> (Scroll to see more)</span></h3>
      <a style="font-size: 10px;" href="{{ URL::to('downloadExcel/xls') }}">&nbsp;
Export to xls</a>
    @if(count($data['sessionBySource']) > 0)
      <div style="height: 250px; overflow: scroll;">
      <table class="table">
        <thead>
          <tr>
            <th>Source</th>
            <th>No. of Sessions</th>
          </tr>
        </thead>
        <tbody>
        @foreach($data['sessionBySource'] AS $value)
          <tr>
            <td>{{$value['source']}}</td>
            <td>{{$value['session']}}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
      </div>
    @endif
  </div> --}}
</div>
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
    10 + ":" + 00;
    startTimer();
    setTimeout("location.reload(true)", 600000);
  });
</script>

<script src="https://nightly.datatables.net/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js" type="text/javascript"></script>


<script type="text/javascript">
  $(function() {


    $('#dataTable').DataTable();

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

