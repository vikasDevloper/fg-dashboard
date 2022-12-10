<style type="text/css">
.table-fixed {
  width : 100%;
  background-color : #f3f3f3;
}

.table-fixed tbody{
    height : 500px;
    overflow-y : auto;
    width : 100%;
    }

.table-fixed thead, .table-fixed tbody, .table-fixed thead tr, .table-fixed tbody tr, .table-fixed td, .table-fixed th{
    display : block;
  }

.column_width td.col-xs-3.col-md-3 { width: 290px; }

.table-fixed thead tr th {
    float:left;
    background-color: #f39c12;
    border-color:#e67e22;
}
</style>
@extends('layouts.app')
@section('content')
@php
// echo '<pre>';
// print_r($data);
// echo '</pre>';
@endphp
<div class="row" style="margin-bottom: 10px;">
  
  <form action="{{ route('shipped-status') }}" id="filter" name="filter" method="get">
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
<div class="table-responsive col-xs-12 col-md-12"> 
      <h2>Warehouse Pendency Report</h2>
      <table class="table table-fixed" >
        
        <thead>
          <tr>
            <th class="col-xs-2 col-md-2">Date</th>
            <th class="col-xs-2 col-md-2">Order Confirm</th>
            <th class="col-xs-2 col-md-2">Shipped Order </th>
            <th class="col-xs-3 col-md-3">Pendency</th>
            <th class="col-xs-3 col-md-3">Extra Shipped</th>
          </tr>
        </thead>
      
        <tbody class="column_width">
          @php 
           $i              =  0;
           $fivedaysbefore = date("Y-m-d", strtotime("-5 day"));
          @endphp
          @foreach( $data['shippedStatusReport'] as $value)
          @php
                 
              //$date      = date("d-M-Y", strtotime(array_keys($data['shippedStatusReport'])[$i]. '+ 1 days'));
              $timestamp      = strtotime(array_keys($data['shippedStatusReport'])[$i]);
             // $delay5days     = strtotime($fivedaysbefore) >= $timestamp ? "style='color:#FF0000; font-weight: bold'" : '';

              $date           = date("d-M-Y", $timestamp);
              $shipped        = isset($value['orderShipped']) ? $value['orderShipped'] : '0';
              $orderConfirm   = isset($value['orderConfirm']) ? $value['orderConfirm'] : '0';
              $extraShipped   = $shipped - $orderConfirm;
              if($orderConfirm <= $shipped) 
                $pendingShipped = 'No Pendency'; 
              else
                $pendingShipped = $orderConfirm - $shipped; 
             
          @endphp
          <tr>
            <td class="col-xs-2 col-md-2">{{ $date }}</td>
            <td class="col-xs-2 col-md-2">{{ $orderConfirm }}</td> 
            <td class="col-xs-2 col-md-2">{{ $shipped }}</td>
            <td class="col-xs-3 col-md-3">{{ $pendingShipped }}</td>
            <td class="col-xs-3 col-md-3">{{ $extraShipped > 0 ? $extraShipped : 0 }}</td>
          </tr>
           @php  $i++; @endphp
          @endforeach
        </tbody>   
      </table>

</div>
  </div>

<br><br>
@endsection

@section('scripts')
<script type="text/javascript">

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

